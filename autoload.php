<?php

// clase que se encarga de requerir todos los archivos que queramos cargar automaticamente
class Autoload
{
    public function __construct()
    {
        spl_autoload_register(function ($class_name) {
            $models_path      = "./app/models/" . $class_name . ".php";
            $controllers_path = "./app/controllers/" . $class_name . ".php";
            $middlewares_path = "./app/middlewares/" . $class_name . ".php";
            $policies_path = "./app/policies/" . $class_name . ".php";
            $requests_path = "./app/requests/" . $class_name . ".php";

            if (file_exists($models_path))  require_once $models_path;
            if (file_exists($controllers_path))  require_once $controllers_path;
            if (file_exists($middlewares_path))  require_once $middlewares_path;
            if (file_exists($policies_path))  require_once $policies_path;
            if (file_exists($requests_path))  require_once $requests_path;

            if (file_exists('./bootstrap/database/' . $class_name . '.php'))
                require_once './bootstrap/database/' . $class_name . '.php';

            if (file_exists('./bootstrap/router/' . $class_name . '.php'))
                require_once './bootstrap/router/' . $class_name . '.php';

            if (file_exists('./bootstrap/data/' . $class_name . '.php'))
                require_once './bootstrap/data/' . $class_name . '.php';

            if (file_exists('./bootstrap/files/' . $class_name . '.php'))
                require_once './bootstrap/files/' . $class_name . '.php';
        });

        if (file_exists('./app/helpers.php')) require_once './app/helpers.php';
        if (file_exists('./config/app.php')) require_once './config/app.php';
    }
}
