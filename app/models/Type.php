<?php

require_queries('type');

class Type extends Model
{
    private static $recipes = [];

    public $id;
    public $name;
    public $url;
    public $image;
    public $description;

    public static function get()
    {
        parent::$query = TYPE_GET;
        parent::get_query();
        $data = self::getAttributes();
        return $data;
    }

    public static function getPopulars()
    {
        parent::$query = TYPE_GET_POPULAR;
        parent::get_query();
        $data = self::getAttributes();
        return $data;
    }

    public static function find($id)
    {
    }

    public static function findByUrl($url)
    {
        $query = TYPE_FIND . " WHERE url = '" . $url . "'";
        parent::$query = $query;
        parent::get_query();

        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function create($data)
    {
    }

    public function update($data)
    {
    }

    public function delete()
    {
    }

    public function save()
    {
    }

    private static function getAttributes()
    {
        $data = [];
        foreach (parent::$rows as $object) {
            $type = new Type();
            $type->id = @$object->id;
            $type->name = @$object->name;
            $type->url = @$object->url;
            $type->image = @$object->image;
            $type->description = @$object->description;

            array_push($data, $type);
        }

        return $data;
    }

    public function recipes()
    {
        if (empty(self::$recipes))
            self::$recipes = Recipe::get();

        return $this->hasMany(self::$recipes, $this, 'type_id');
    }
}
