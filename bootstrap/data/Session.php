<?php

// clase que se encarga al manejo de las sesiones
class Session
{
    // abrimos la sesion si esta no lo está
    private static function openSession()
    {
        if (!isset($_SESSION))
            session_start(
                [
                    'use_only_cookies' => 1,
                    'read_and_close' => false
                ]
            );
    }



    // -------------------------- MENSAJES ------------------------------------

    // registramos los mensajes 
    // recordatorio: estos mensajes se destruiran una vez se recargue la pag
    public static function setMessages($messages = [])
    {
        self::openSession();
        foreach ($messages as $key => $value) {
            $_SESSION['messages'][$key] = $value;
        }
        // definimos el estado del mensaje en 0, lo utilizamos en la funcion destruir
        $_SESSION['state_message'] = 0;
    }

    // devolvemos el valor del mensaje
    public static function getMessage($message)
    {
        self::openSession();
        return isset($_SESSION['messages'][$message]) ? $_SESSION['messages'][$message] : "";
    }

    // eliminamos los mensajes una vez recargada la pagina, logica definida en nuestro /index.php
    public static function deleteMessages()
    {
        // verificamos si hay mensajes
        if (!empty($_SESSION['messages'])) {
            if ($_SESSION['state_message'] === 0)
                $_SESSION['state_message'] = 1;
            else if ($_SESSION['state_message'] === 1) {
                $_SESSION['messages'] = null;
            }
        }
    }

    // verificamos si existe el mensaje
    public static function hasMessage($name)
    {
        self::openSession();
        return isset($_SESSION['messages'][$name]);
    }

    // -------------------------- AUTH ------------------------------------

    public static function login($user)
    {
        self::openSession();
        $_SESSION['auth'] = $user;
        return true;
    }

    public static function logout()
    {
        self::openSession();
        $_SESSION['auth'] = NULL;
        return true;
    }

    public static function authCheck()
    {
        self::openSession();
        if (isset($_SESSION['auth']))
            return !empty($_SESSION['auth']);
        else {
            if (Cookie::hasLoginCookie()) {
                $user = User::findByRememberToken(Cookie::getLoginCookie());
                if (!empty($user))
                    return self::login($user);
            }
        }

        return false;
    }

    public static function getAuth()
    {
        self::openSession();
        return $_SESSION['auth'];
    }





    // ------------------------ ERROR --------------------------

    public static function setErrors($errors = [])
    {
        self::openSession();
        foreach ($errors as $key => $value) {
            $_SESSION['errors'][$key] = $value;
        }
        $_SESSION['state_error'] = 0;
    }

    public static function getError($error)
    {
        self::openSession();
        return isset($_SESSION['errors'][$error]) ? $_SESSION['errors'][$error] : "";
    }

    public static function deleteErrors()
    {
        if (!empty($_SESSION['errors'])) {
            if ($_SESSION['state_error'] === 0)
                $_SESSION['state_error'] = 1;
            else if ($_SESSION['state_error'] === 1) {
                $_SESSION['errors'] = null;
            }
        }
    }

    public static function hasError($error)
    {
        self::openSession();
        return isset($_SESSION['errors'][$error]);
    }


    // ------------------------ OLD --------------------------

    public static function setOlds($olds = [])
    {
        self::openSession();
        foreach ($olds as $key => $value) {
            $_SESSION['olds'][$key] = $value;
        }
        $_SESSION['state_olds'] = 0;
    }

    public static function getOld($old)
    {
        self::openSession();
        return isset($_SESSION['olds'][$old]) ? $_SESSION['olds'][$old] : false;
    }

    public static function deleteOlds()
    {
        self::openSession();
        if (!empty($_SESSION['olds'])) {
            if ($_SESSION['state_olds'] === 0)
                $_SESSION['state_olds'] = 1;
            else if ($_SESSION['state_olds'] === 1) {
                $_SESSION['olds'] = null;
            }
        }
    }

    public static function hasOld($old)
    {
        self::openSession();
        return isset($_SESSION['olds'][$old]);
    }



    // ------------------------------------- MIDDLEWARE ---------------------------------------------------

    // Si es que el programa es interceptado por algun middleware entonces el estado del middleware cambia a 1 para que esté atento a si lo pasa o no
    public static function startMiddleware($middleware_url, $next_url)
    {
        self::openSession();
        $_SESSION['state_middleware'] = 1;
        $_SESSION['middleware_url'] = $middleware_url;
        $_SESSION['next_url'] = $next_url;
    }

    // A cada url que ingresemos desde el navegador nos ejecutará esta funcion para saber si es que el estado del middleware está activo
    // 0 -> middleware inactivo (no hace nada)
    // 1 -> middleware inicializado (esperando accion)
    // 2 -> ha pasado el middleware, por lo que redireccionamos a la url anterior que queria ingresar el usuario

    public static function verifyMiddleware()
    {
        self::openSession();
        if (isset($_SESSION['state_middleware']) && isset($_SESSION['middleware_url']) && isset($_SESSION['next_url'])) {
            if (!is_null($_SESSION['state_middleware']) && !is_null($_SESSION['middleware_url']) && !is_null($_SESSION['next_url'])) {
                // Si el estado es 2 lo redireccionamos no sin antes dejar inactivo el middleware
                if ($_SESSION['state_middleware'] === 2) {
                    $_SESSION['state_middleware'] = 0;
                    return header('location:' . $_SESSION['next_url']);
                }

                // supongamos que empiezo el middleware y lo redirecciono al login, si el usuario se va a otra pagina que no sea el login
                // el middleware pasa a estar en modo inactivo
                if ($_SESSION['state_middleware'] === 1) {
                    if (getCurrentURL() !== $_SESSION['middleware_url'])
                        $_SESSION['state_middleware'] = 0;
                }
            }
        }
    }

    // Esta funcion la mandamos a llamar desde el controlador respectivo de la vista del middleware una vez que haya pasado la prueba
    public static function nextMiddleware()
    {
        self::openSession();

        if (isset($_SESSION['state_middleware']))
            if (!is_null($_SESSION['state_middleware']) && $_SESSION['state_middleware'] == 1)
                $_SESSION['state_middleware'] = 2;
    }
}
