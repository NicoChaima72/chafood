<?php
// en este archivo podras agregar tus funciones 

// convierte Mi titULo a mi-titulo
function to_slug($string)
{
    $out = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $out = preg_replace("/[^-\/+|\w ]/", '', $out);
    $out = strtolower(trim($out, '-'));
    $out = preg_replace("/[\/_| -]+/", '-', $out);

    return $out;
}


// arreglo de objetos
// Elimina los elementos con id duplicados por lo que quedan solo elementos con id's distintos
function remove_duplicates($array_class)
{
    $new_array = [];
    $id_list = [];
    foreach ($array_class as $item) {
        if (!in_array($item->id, $id_list)) {
            array_push($new_array, $item);
            array_push($id_list, $item->id);
        }
    }

    return $new_array;
}


// verifica si la ruta como parametro es la misma del navegador
function getActiveRoute($route_name)
{
    $current_route = getCurrentRoute()['name'];
    $last_char = substr($route_name, -1);

    if ($last_char === '*') $route_name = substr($route_name, 0, strpos($route_name, '*'));

    if (substr($route_name, -1) === '.') $route_name = substr($route_name, 0, strlen($route_name) - 1);


    $is_active_route = true;

    $route_name = explode('.', $route_name);
    $current_route = explode('.', $current_route);

    if ($last_char === '*') {

        for ($i = 0; $i < count($route_name); $i++)
            if ($route_name[$i] !== $current_route[$i])
                return false;
    } else {
        if (count($current_route) !== count($route_name)) return false;

        for ($i = 0; $i < count($current_route); $i++)
            if ($route_name[$i] !== $current_route[$i])
                return false;
    }




    return true;
}



















// redireccinamos a la url que pasamos
// si pasamos algo en args estos se almacenaran en $_SESSION['messages'][]
// estos se destruiran al momento de recargar la pagina
function redirect($path, $args = [])
{
    if (!empty($args))
        Session::setMessages($args);

    return header('location: ' . $path);
}


// pasaremos como parametro el alias de la url y nos devuelve el texto de la url correspondiente
// si esta url contiene un parametro, le pasamos el 2do argumento y genera la url correspondiente
function route($route, $param = "")
{
    $output = "";
    // obtenemos la lista de los alias de las rutas registradas
    $routes = Route::$route_name;
    foreach ($routes as $value) {
        if ($value['name'] === $route)
            $output = $value['url'];
    }
    if (empty($output)) throw new Exception("Nombre de ruta no registrado");

    // si no es una url con parametro devolvemos la url correspondiente
    if ($param === "") {
        return APP_HOST . APP_URI . $output;
    }

    // si es con parametro reemplazamos {parametro} con el parametro del argumento
    $route = explode("/", $output);
    $new_route = [];
    foreach ($route as $value) {
        if (strpos($value, "{") === 0)
            // reemplazamos el {parametro} por el parametro
            array_push($new_route, $param);
        else
            array_push($new_route, $value);
    }

    $route = implode("/", $new_route);

    return APP_HOST . APP_URI . $route;
}




























// nos carga una pagina registrada en /views/
// le podemos pasar un arreglo con variables ('mi_variable' => 'mi valor')
// si este es el caso, podremos acceder a la variable $mi_variable desde la vista
function view($path, $var = array())
{
    if (!empty($var)) {
        foreach ($var as $key => $value) {
            ${$key} = $value;
        }
    }

    $path = './resources/views/' . str_replace('.', '/', $path) . '.phtml';
    return require_once $path;
}


// nos devuelve la ruta completa de nuestros archivos de la carpeta de /public/
// ejemplo: asset('css/app.css') 
function asset($path)
{
    return APP_HOST . APP_URI . "/public/" . $path;
}

// podemos registrar componentes en /view/components/
// si este es el caso, lo podemos invocar en una vista con esta funcion
// ejemplo: component('navbar') /views/components/navbar.phtml
function component($component, $var = null, $route = null)
{
    $component = str_replace('.', '/', $component);
    if (!is_null($var))
        $data = $var;

    if (!is_null($route))
        $route_path = $route;
    return  require_once './resources/views/components/' . $component . '.phtml';
}

// pasamos el nombre del controlador y nos devuelve la ruta completa
function controller_path($controller)
{
    return './app/controllers/' . $controller . '.php';
}

// pasamos el nombre del modelo y nos devuelve la ruta completa
function model_path($model)
{
    return './app/models/' . $model . '.php';
}

// pasamos el nombre del middleware y nos devuelve la ruta completa
function middleware_path($middleware)
{
    $middleware = ucfirst($middleware) . 'Middleware.php';
    return './app/middlewares/' . $middleware;
}

// nos rellena el nombre para el middleware
// ej: param: probando    salida: ProbandoMiddleware
function middleware_name($middleware)
{
    return ucfirst($middleware) . 'Middleware';
}

// Cada modelo contiene las consultas necesarias para la base de datos
// para que las consultas no estén por los modelos de manera desordenada, las abstraemos a archivos y solo las mandamos a llamar
// esta funcio importa las consultas de x modelo
function require_queries($model)
{
    return require_once "./database/$model.php";
}

























// nosotros podemos pasar mensajes ('titulo' => valor) que duran hasta que se recarga la pagina
// para saber si un mensaje existe utilizamos esta funcion y pasamos el titulo
function hasMessage($message)
{
    return Session::hasMessage($message);
}

// para obtener el valor del mensaje utilizamos esta funcion
function getMessage($message)
{
    return Session::getMessage($message);
}


// valida si un archivo es de tipo file
function has_file($file)
{
    if (!empty($file))
        return !empty($file['tmp_name']);
    return false;
}

// por cada formulario podemos crear un Request para este
// si un input no cumple con las condiciones, se almacena el error de este en sessiones de manera temporal
// para saber si el input contiene un error ocupamos esta funcion
function has_error($field)
{
    return Session::hasError($field);
}

// obtenemos el error como tal
function error($field)
{
    return Session::getError($field);
}


// cuando haya algun error en un formulario, queremos que se nos avise de nuestro error y que la info que hayamos ingresado no se pierda
// esta funcion almacena temporalmente el valor de los inputs cuando ha ocurrido un error
function old($field, $value_default = '')
{
    $value = Session::getOld($field);
    if ($value === false)
        $value = $value_default;

    return $value;
}



// verificamos si algun usuario ha iniciado sesion (login)
function auth_check()
{
    return Session::authCheck();
}

// obtenemos al usuario logueado
function auth()
{
    return Session::getAuth();
}








function getCurrentRoute()
{
    return Route::$current_path;
}

function getCurrentURL()
{
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
