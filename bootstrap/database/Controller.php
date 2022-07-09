<?php

abstract class Controller
{
    // cuando llamamos al controlador en la clase Route y es una url con parametro
    // evaluamos que ese parametro (en este caso la url de la receta) exista
    // si esta no existe, nos devolverá un error 404
    abstract function handle($data = null);
}
