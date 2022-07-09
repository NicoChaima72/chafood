<?php

// esta clase abstracta esta encargada de la conexion con la bd
// tambien contiene funciones que todos los modelos deben tener como get, find, create ...
abstract class Model
{
    // agregamos los atributos para la conexion
    // estos valores los obtiene de las constantes registradas en /config/app.php
    public static $count = 0;
    private static $db_host = DB_HOST;
    private static $db_user = DB_USERNAME;
    private static $db_pass = DB_PASSWORD;
    private static $db_name = DB_DATABASE;
    private static $db_charset = 'utf8';
    // variable para la conexion
    private static $pdo;
    // variable para la consulta que realizará
    public static $query;
    // un insert, update o delete necesita parametros, los obtenemos de esta variable
    protected static $params = array();
    // cuando hagamos un select, los resultados se almacenaran aqui
    public static $rows = array();
    // la accion que vamos a realizar
    protected static $action;

    // definimos las funciones que deben implementar si o si nuestras clases hijas
    abstract static function get();
    abstract static function find($id);
    abstract static function create($data);
    abstract function update($data);
    abstract function delete();
    abstract function save();


    // para la conexion a la bd utilizaremos pdo
    private static function db_open()
    {
        $conn = "mysql:host=" . self::$db_host . ";dbname=" . self::$db_name . ";charset=" . self::$db_charset;
        self::$pdo = new PDO($conn, self::$db_user, self::$db_pass);
    }

    // esta funcion la ocuparemos cuando querramos agregar, editar o eliminar datos
    public static function set_query()
    {
        self::db_open();
        $result = -1;
        $stmt = self::$pdo->prepare(self::$query);
        $stmt->execute(self::$params);
        if ($stmt->rowCount() >= 0)
            // si estamos insertando datos, retornaremos el id creado
            // si no, solo retornamos el valor que indica que lo hizo
            if (self::$action === 'create')
                $result = self::$pdo->lastInsertId();
            else
                $result = $stmt->rowCount();

        $stmt = null;
        return $result;
    }

    // esta funcion la ocuparemos solamente para recuperar info de la bd (select)
    public static function get_query()
    {
        self::$rows = [];
        self::db_open();
        $result = self::$pdo->query(self::$query);
        $result->setFetchMode(PDO::FETCH_OBJ);
        while (self::$rows[] = $result->fetch());

        $result = null;
        self::$count += 1;

        return array_pop(self::$rows);
    }

    // relacion 1:1 donde el modelo que llama contiene en sus atributos la llave
    protected function belongsTo($data_array, $model, $local_key)
    {
        $item = [];
        foreach ($data_array as $data) {
            if ($data->id === $model->$local_key)
                $item = $data;
        }

        return $item;
    }


    // relacion 1:1 donde el modelo que llama no contiene en sus atributos la llave, si no que el modelo asociado lo tiene
    protected function hasOne($data_array, $model, $foreign_key)
    {
        $item = [];
        foreach ($data_array as $data) {
            if ($data->$foreign_key === $model->id)
                $item = $data;
        }
        return $item;
    }


    // relacion 1:N donde el modelo que llama contiene en sus atributos la llave
    protected function hasMany($data_array, $model, $foreign_key)
    {
        $items = [];
        foreach ($data_array as $data) {
            if ($data->$foreign_key === $model->id)
                array_push($items, $data);
        }
        return $items;
    }
}
