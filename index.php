<?php

// cargamos el autoload para autocargar los archivos que necesitemos
require_once './autoload.php';
$autoload = new Autoload();

// en este archivo registraremos las rutas que utilizará la aplicacion
require_once('./routes/web.php');


// eliminamos los mensajes enviados por session despues de recargar la pagina
Session::deleteMessages();
// eliminamos el valor de los inputs enviados por session despues de recargar la pagina
Session::deleteOlds();
// eliminamos los errores enviados por session despues de recargar la pagina
Session::deleteErrors();
