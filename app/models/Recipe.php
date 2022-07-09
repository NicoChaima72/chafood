<?php

// mandamos a llamar a todas las consultas a la bd de las recetas, estas están asociadas en /database/
require_queries('recipe');
// extendemos de model donde está la conexion a la bd y contiene funciones abstractas
class Recipe extends Model
{
    // para una relacion que esté asociado el modelo creamos una variable para así obtener la info asociada
    private static $users = [];
    private static $types = [];
    private static $photos = [];


    // los atributos del modelo
    public $id;
    public $user_id;
    public $type_id;
    public $url;
    public $title;
    public $description;
    public $ingredients;
    public $steps;
    public $duration;
    public $persons;

    // obtener todos las recetas
    public static function get()
    {
        // Obtenemos la consulta RECIPE_GET de las consultas almacenadas
        parent::$query = RECIPE_GET;
        parent::get_query();

        return self::getAttributes();
    }

    public static function getLatests()
    {
        parent::$query = RECIPE_GET_LATEST;
        parent::get_query();

        return self::getAttributes();
    }

    // buscamos alguna receta por el id
    public static function find($id)
    {
        $query = RECIPE_FIND . " WHERE id = " . $id;
        parent::$query = $query;
        parent::get_query();

        $data = self::getAttributes();

        // si se ha encontrado, entonces enviamos el objeto, si no, enviamos un obj vacio
        return (!empty($data)) ? $data[0] : [];
    }

    // buscamos por url porque queremos reemplazar el id en la ruta por la url
    public static function findByUrl($url)
    {
        $query = RECIPE_FIND . " WHERE url = '" . $url . "'";
        parent::$query = $query;
        parent::get_query();

        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function findByTitle($title)
    {
        $query = RECIPE_FIND . " WHERE title LIKE '%$title%' ORDER BY id DESC";
        parent::$query = $query;
        parent::get_query();

        $data = self::getAttributes();

        return $data;
    }

    public static function create($data)
    {
        // validamos si algun valor que viene desde el controlador está vacio, si este es el caso enviamos null
        // con la finalidad de que la bd se encargue de verificar si puede ser o no nulo
        $new_data = [
            'user_id' => !empty(@$data['user_id']) ? @$data['user_id'] : null,
            'type_id' => !empty(@$data['type_id']) ? @$data['type_id'] : null,
            'title' => !empty(@$data['title']) ? @$data['title'] : null,
            'description' => !empty(@$data['description']) ? @$data['description'] : null,
            'ingredients' => !empty(@$data['ingredients']) ? @$data['ingredients'] : null,
            'steps' => !empty(@$data['steps']) ? @$data['steps'] : null,
            'duration' => !empty(@$data['duration']) ? @$data['duration'] : null,
            'persons' => !empty(@$data['persons']) ? @$data['persons'] : null,
        ];

        parent::$action = 'create';
        parent::$params = $new_data;
        parent::$query = RECIPE_INSERT;
        // var_dump(RECIPE_INSERT);
        // var_dump($new_data);
        // return;

        $result = parent::set_query();
        // si enviamos un dato -que en la bd es not null- como null, nos devolverá un 0 (de que no se ha insertado) 
        // de lo contrario nos devolverá el id con el que se ha guardado
        // generamos una url (no necesario)
        if ($result > 0)
            self::generateUrl($result);

        return $result;
    }

    public function update($data)
    {
        $new_data = [
            'id' => $this->id,
            'user_id' => !empty(@$data['user_id']) ? @$data['user_id'] : null,
            'type_id' => !empty(@$data['type_id']) ? @$data['type_id'] : null,
            'title' => !empty(@$data['title']) ? @$data['title'] : null,
            'description' => !empty(@$data['description']) ? @$data['description'] : null,
            'ingredients' => !empty(@$data['ingredients']) ? @$data['ingredients'] : null,
            'steps' => !empty(@$data['steps']) ? @$data['steps'] : null,
            'duration' => !empty(@$data['duration']) ? @$data['duration'] : null,
            'persons' => !empty(@$data['persons']) ? @$data['persons'] : null,
        ];
        parent::$params = $new_data;
        parent::$query = RECIPE_UPDATE;

        return parent::set_query();
    }

    // eliminar registro de la bd
    public function delete()
    {
        $new_data = ['id' => $this->id];
        parent::$params = $new_data;
        parent::$query = RECIPE_DELETE;

        return parent::set_query();
    }

    // tambien tenemos la funcion save por si estamos construyendo el modelo y lo queremos agregar
    public function save()
    {
        $new_data = [
            'user_id' => !empty($this->user_id) ? $this->user_id : null,
            'type_id' => !empty($this->type_id) ? $this->type_id : null,
            'url' => !empty($this->url) ? $this->url : null,
            'title' => !empty($this->title) ? $this->title : null,
            'description' => !empty($this->description) ? $this->description : null,
            'ingredients' => !empty($this->ingredients) ? $this->ingredients : null,
            'steps' => !empty($this->steps) ? $this->steps : null,
            'duration' => !empty($this->duration) ? $this->duration : null,
            'persons' => !empty($this->persons) ? $this->persons : null,
        ];

        parent::$action = 'create';
        parent::$params = $new_data;
        parent::$query = RECIPE_INSERT;

        return parent::set_query();
    }


    private static function getAttributes()
    {
        $data = [];
        foreach (parent::$rows as $object) {
            $recipe = new Recipe();
            $recipe->id = @$object->id;
            $recipe->user_id = @$object->user_id;
            $recipe->type_id = @$object->type_id;
            $recipe->url = @$object->url;
            $recipe->title = @$object->title;
            $recipe->description = @$object->description;
            $recipe->ingredients = @$object->ingredients;
            $recipe->steps = @$object->steps;
            $recipe->duration = @$object->duration;
            $recipe->persons = @$object->persons;
            $recipe->created_at = @$object->created_at;
            $recipe->updated_at = @$object->updated_at;

            array_push($data, $recipe);
        }

        return remove_duplicates($data);
    }

    // esta funcion es opcional, como queremos trabajar con url en vez de id, necesitamos generar una url  
    // en este caso la url será el titulo de la receta
    // si esta url ya está registrada, agregamos un -id al final para que sea unica
    private static function generateUrl($id)
    {
        $recipe = self::getTitleForUrl($id, "id");
        $url = to_slug($recipe->title);
        $count = self::getTitleForUrl($url, "url");

        if (!empty($count)) $url .= "-$id";

        // cuando ya tenemos nuestra url unica, la agregamos
        return self::registerUrl($id, $url);
    }

    private static function getTitleForUrl($param, $arg = "id")
    {
        parent::$query = RECIPE_GET_TITLE . ($arg === "id" ? " WHERE id = $param" : " WHERE url = '$param'");
        parent::get_query();

        $data = [];
        foreach (parent::$rows as $object) {
            $recipe = new Recipe();
            $recipe->id = @$object->recipe_id;
            $recipe->title = @$object->recipe_title;
            array_push($data, $recipe);
        }
        return (!empty($data)) ? $data[0] : [];
    }

    private static function registerUrl($id, $url)
    {
        $data = ["url" => $url, "id" => $id];

        parent::$params = $data;
        parent::$query = RECIPE_UPDATE_URL;

        return parent::set_query();
    }


    // verifica si el autor de esta receta es el usuario logueado
    public function owner()
    {
        if (auth_check())
            if (auth()->id === $this->user_id)
                return true;

        return false;
    }



    // obtenemos el usuario relacionado a esta receta
    public function user()
    {
        // si la variable statica $users está vacia mandamos a llamar a todos los usuarios
        if (empty(self::$users))
            self::$users = User::get();

        // retornamos al usuario asociado que será una relacion 1:1
        return $this->belongsTo(self::$users, $this, 'user_id');
    }

    // obtenemos el tipo relacionado a esta receta
    public function type()
    {
        if (empty(self::$types))
            self::$types = Type::get();

        return $this->belongsTo(self::$types, $this, 'type_id');
    }

    // obtenemos la foto relacionada a esta receta
    public function photo()
    {
        if (empty(self::$photos))
            self::$photos = Photo::get();

        return $this->hasOne(self::$photos, $this, 'recipe_id');
    }
}
