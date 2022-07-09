<?php

class Route
{
    // recibe la url que visita el usuario (navegador)
    public $url;
    // lista de todas las url registradas en web.php
    public $url_list = array();
    // lista de la url y el alias que recibe
    public static $route_name = array();

    public static $current_path = null;

    public function __construct()
    {
        // si nuestra app esta en tupagina.com/pagina/ corta la url para acceder solamente a las rutas registrada
        $this->url = substr($_SERVER['REQUEST_URI'], strlen(APP_URI));
        // si envia parametros get (que empiezan con ?) recuperamos solo la ruta antes de eso, lo demas no interesa
        if (strpos($this->url, '?') !== false)
            $this->url = substr($this->url, 0, strpos($this->url, '?'));
        // si la url del navegador termina en / lo eliminamos para evitar confictos
        if (substr($this->url, strlen($this->url) - 1) === '/' && strlen($this->url) > 1)
            $this->url = substr($this->url, 0, strlen($this->url) - 1);
    }

    // agrega las rutas get
    public function get($url, $controller, $name = '', $middlewares = array())
    {
        // si la url registrada termina en / lo eliminamos para evitar confictos
        if (substr($url, strlen($url) - 1) === '/' && strlen($url) > 1)
            $url = substr($url, 0, strlen($url) - 1);

        // generamos un obj con las propiedades
        $path = [
            'url' => $url,
            'controller' => $controller,
            'method' => 'GET',
            'name' => $name
        ];

        // agregamos el alias de la ruta con la url a $route_name
        if (!empty($name)) {
            array_push(self::$route_name, ['name' => $name, 'url' => $url]);
        }

        if (!empty($middlewares))
            $path['middlewares'] = $middlewares;

        // agregamos el obj a nuestra $url_list
        array_push($this->url_list, $path);
    }

    public function post($url, $controller, $name = '', $middlewares = array())
    {
        // si la url registrada termina en / lo eliminamos para evitar confictos
        if (substr($url, strlen($url) - 1) === '/' && strlen($url) > 1)
            $url = substr($url, 0, strlen($url) - 1);

        // generamos un obj con las propiedades
        $path = [
            'url' => $url,
            'controller' => $controller,
            'method' => 'POST',
            'name' => $name
        ];

        // agregamos el alias de la ruta con la url a $route_name
        if (!empty($name)) {
            array_push(self::$route_name, ['name' => $name, 'url' => $url]);
        }

        if (!empty($middlewares))
            $path['middlewares'] = $middlewares;

        // agregamos el obj a nuestra $url_list
        array_push($this->url_list, $path);
    }


    public function redirect()
    {
        // se almacenara la ruta
        $path = [];

        $is_valid = false;
        // para validar si contiene parametros {ejemplo}
        // la url del navegador la dividimos por /
        $url_browser = explode('/', $this->url);

        $path = $this->sameNumberOfElements($url_browser, $this->url_list);

        $path_aux = $this->findUrlWithoutParams($url_browser, $path);

        if (empty($path_aux))
            $path_aux = $this->findUrlWithParams($url_browser, $path);


        $path = $path_aux;

        if (empty($path))   throw new Exception("ERROR 404");

        $path = $this->getUrlWithSameMethodHTTP($path);

        if (empty($path))   throw new Exception("ERROR METODO");


        // en web.php registramos lorem@ipsum donde lorem es el controlador e ipsum el metodo
        $controller = $this->getControllerRoute($path);
        $method = $this->getMethodRoute($path);

        // verificamos que existe el controlador (con la ayuda del helper para obtener la ruta)
        if (!file_exists(controller_path($controller)))
            throw new Exception("EL CONTROLADOR NO EXISTE");

        require_once controller_path($controller);

        // podriamos tambien llamar a admin/controlador@metodo por lo que si es el caso eliminamos admin/
        $controller = substr($controller, (strrpos($controller, '/') > 0) ? strpos($controller, '/') + 1 : 0);

        $controller = new $controller();

        // llamamos a la funcion encargada de controlar el acceso al controlador
        // si contiene todo lo necesario para acceder puede hacerlo, si no arrojamos error 404 o 403


        if (!$this->hasParamsInUrl($url_browser, $path))
            $is_valid = $controller->handle();
        else
            $is_valid = $controller->handle($this->getParamsInUrl($url_browser, $path));

        if (!$is_valid)
            throw new Exception("ERROR 404");




        // Verificamos si existe una ruta de middleware activa
        Session::verifyMiddleware();



        // verificamos que cumpla los middlewares
        if (isset($path['middlewares']))
            if (!$this->verifyMiddlewares($path['middlewares']))
                return;


        // Guardamos la ruta actual en la variable estatica
        self::$current_path = $path;

        // si no una url con parametros
        if (!$this->hasParamsInUrl($url_browser, $path))
            return $controller->$method();


        return $controller->$method($this->getParamsInUrl($url_browser, $path));
    }














    private function sameNumberOfElements($url, $list)
    {
        $data = [];

        for ($i = 0; $i < count($list); $i++) {
            $url_list = explode('/', $list[$i]['url']);
            // si la url del navegador como la registrada contienen la misma cantidad de argumentos
            // esto lo hacemos solo para reducir el tamaño de las posibles rutas y simplificar la comprension
            if (count($url) === count($url_list))
                array_push($data, $list[$i]);
        }

        return $data;
    }

    private function findUrlWithoutParams($browser_url, $path_list)
    {
        $data = [];

        foreach ($path_list as $path) {
            // la dividimos
            $url = explode('/', $path['url']);
            $is_match = true;
            // recorremos sus argumentos
            for ($i = 0; $i < count($url); $i++) {
                // si todos sus argumentos son iguales entonces coincide
                if ($url[$i] !== $browser_url[$i])
                    $is_match = false;
            }

            // si encontramos coincidencias, agregamos la ruta a $path_list_normal
            if ($is_match)
                array_push($data, $path);
        }

        return $data;
    }

    private function findUrlWithParams($browser_url, $path_list)
    {
        $data = [];

        foreach ($path_list as $path) {
            $url = explode('/', $path['url']);
            $is_match = true;
            for ($i = 0; $i < count($url); $i++) {
                // si algun argumento es distinto verificamos que la url registrada empiece por { lo que quiere decir que es un parametro 
                if ($url[$i] !== $browser_url[$i])
                    if (strpos($url[$i], '{') === false)
                        $is_match = false;
                    else {
                        $has_param = true;
                        $param = $browser_url[$i];
                    }
            }
            if ($is_match)
                array_push($data, $path);
        }

        return $data;
    }

    private function hasParamsInUrl($browser_url, $path)
    {
        $has_params = false;

        $url = explode('/', $path['url']);
        $is_match = true;
        for ($i = 0; $i < count($url); $i++) {
            // si algun argumento es distinto verificamos que la url registrada empiece por { lo que quiere decir que es un parametro 
            if ($url[$i] !== $browser_url[$i])
                if (strpos($url[$i], '{') !== false)
                    $has_params = true;
        }

        return $has_params;
    }

    private function getParamsInUrl($browser_url, $path)
    {
        $params = null;

        $url = explode('/', $path['url']);
        $is_match = true;
        for ($i = 0; $i < count($url); $i++) {
            // si algun argumento es distinto verificamos que la url registrada empiece por { lo que quiere decir que es un parametro 
            if ($url[$i] !== $browser_url[$i])
                if (strpos($url[$i], '{') !== false)
                    $params = $browser_url[$i];
        }
        return $params;
    }

    private function getUrlWithSameMethodHTTP($path_list)
    {
        $data = [];
        foreach ($path_list as $path) {
            if ($this->isValidMethodHTTP($path['method'])) {
                $data = $path;
            }
        }

        return $data;
    }

    private function isValidMethodHTTP($browser_method)
    {
        return $browser_method === $_SERVER['REQUEST_METHOD'];
    }

    private function getControllerRoute($route)
    {
        return substr($route['controller'], 0, strpos($route['controller'], '@'));
    }

    private function getMethodRoute($route)
    {
        return substr($route['controller'], strpos($route['controller'], '@') + 1);
    }

    private function verifyMiddlewares($middlewares_list)
    {
        if (empty($middlewares_list))
            return true;

        foreach ($middlewares_list as $middleware) {
            if (!file_exists(middleware_path($middleware)))
                throw new Exception('Middleware: ' . middleware_path($middleware) . ' no existe');

            $name_middleware = middleware_name($middleware);
            return $name_middleware::handle();
        }
    }
}
