<?php

require_queries('photo');

class Photo extends Model
{
    public static $recipes = [];

    public $id;
    public $recipe_id;
    public $url;
    public $created_at;
    public $updated_at;

    public static function get()
    {
        parent::$query = PHOTO_GET;
        parent::get_query();

        return self::getAttributes();
    }

    public static function find($id)
    {
        parent::$query = PHOTO_FIND . " WHERE id = " . $id;
        parent::get_query();

        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function findByRecipe($recipe_id)
    {
        parent::$query = PHOTO_FIND . " WHERE recipe_id = " . $recipe_id;
        parent::get_query();

        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function create($data)
    {
        $new_data = [
            'recipe_id' => !empty(@$data['recipe_id']) ? @$data['recipe_id'] : null,
            'url' => !empty(@$data['url']) ? @$data['url'] : null
        ];

        parent::$action = 'create';
        parent::$params = $new_data;
        parent::$query = PHOTO_INSERT;

        $result = parent::set_query();

        return $result;
    }

    public function update($data)
    {
        $new_data = [
            'id' => $this->id,
            'url' => !empty(@$data['url']) ? @$data['url'] : null
        ];

        parent::$params = $new_data;
        parent::$query = PHOTO_UPDATE;

        return parent::set_query();
    }

    public function delete()
    {
        $new_data = ['id' => $this->id];

        parent::$params = $new_data;
        parent::$query = PHOTO_DELETE;

        return parent::set_query();
    }

    public function save()
    {
        $new_data = [
            'recipe_id' => !empty($this->recipe) ? $this->recipe->id : null,
            'url' => !empty($this->url) ? $this->url : null
        ];

        parent::$action = 'create';
        parent::$params = $new_data;
        parent::$query = PHOTO_INSERT;

        $result = parent::set_query();

        return $result;
    }

    private static function getAttributes()
    {
        $data = [];
        foreach (parent::$rows as $object) {
            $photo = new Photo();
            $photo->id = @$object->id;
            $photo->recipe_id = @$object->recipe_id;
            $photo->url = @$object->url;
            $photo->created_at = @$object->created_at;
            $photo->updated_at = @$object->updated_at;

            array_push($data, $photo);
        }

        return $data;
    }

    public function recipe()
    {
        if (empty(self::$recipes))
            self::$recipes = Recipe::get();

        return $this->belongsTo(self::$recipes, $this, 'recipe_id');
    }
}
