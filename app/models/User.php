<?php

require_queries('user');

class User extends Model
{
    private static $recipes = [];

    public $id;
    public $name;
    public $email;
    public $password;
    public $remember_token;
    public $created_at;
    public $updated_at;

    public static function get()
    {
        parent::$query = USER_GET;

        parent::get_query();
        return self::getAttributes();
    }

    public static function find($id)
    {
        $query = USER_FIND . " WHERE id = " . $id;
        parent::$query = $query;

        parent::get_query();
        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function findByEmail($email)
    {
        $query = USER_FIND . " WHERE email = '" . $email . "'";
        parent::$query = $query;

        parent::get_query();
        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function findByRememberToken($token)
    {
        $query = USER_FIND . " WHERE remember_token = '" . $token . "'";
        parent::$query = $query;

        parent::get_query();
        $data = self::getAttributes();

        return (!empty($data)) ? $data[0] : [];
    }

    public static function create($data)
    {
        $new_data = [
            'name' => !empty(@$data['name']) ? @$data['name'] : null,
            'email' => !empty(@$data['email']) ? @$data['email'] : null,
            'password' => !empty(@$data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null,
        ];

        parent::$action = 'create';
        parent::$params = $new_data;
        parent::$query = USER_INSERT;

        return parent::set_query();
    }

    public function update($data)
    {
        $new_data = [
            'id' => $this->id,
            'name' => $data->name
        ];
        parent::$params = $new_data;
        parent::$query = USER_UPDATE;

        return parent::set_query();
    }

    public function updateRememberToken($token)
    {
        $new_data = [
            'id' => $this->id,
            'remember_token' => $token
        ];
        parent::$params = $new_data;
        parent::$query = USER_UPDATE_TOKEN;

        return parent::set_query();
    }

    public function delete()
    {
        $new_data = ['id' => $this->id];
        parent::$params = $new_data;
        parent::$query = USER_DELETE;

        return parent::set_query();
    }

    public function save()
    {
        $new_data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => password_hash($this->password, PASSWORD_BCRYPT)
        ];

        parent::$params = $new_data;
        parent::$query = USER_INSERT;

        return parent::set_query();
    }

    private static function getAttributes()
    {
        $data = [];
        foreach (parent::$rows as $object) {
            $user = new User();
            $user->id = @$object->id;
            $user->name = @$object->name;
            $user->email = @$object->email;
            $user->password = @$object->password;
            $user->created_at = @$object->created_at;
            $user->updated_at = @$object->updated_at;

            array_push($data, $user);
        }

        return $data;
    }

    public function recipes()
    {
        if (empty(self::$recipes))
            self::$recipes = Recipe::get();

        return $this->hasMany(self::$recipes, $this, 'user_id');
    }
}
