<?php

class CreateRecipeRequest extends Request
{
    public static function validate($model = null)
    {
        $rules = [
            'title' => "required",
            'description' => "required",
            'ingredients' => "required",
            'steps' => "required",
            'duration' => "required",
            'persons' => "required",
            'image' => "required"
        ];


        Request::$messages = [
            'title.required' => 'El titulo es requerido',
            'title.unique' => 'Este titulo ya está registrado',
            'image.required' => 'La url de imagen es requerida',
        ];


        return parent::validating($rules, $model);
    }
}
