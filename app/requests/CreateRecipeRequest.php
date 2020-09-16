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
            'image' => "file_required||file_mime:jpg,png,jpeg||file_max:1024"
        ];


        Request::$messages = [
            'title.required' => 'El titulo es requerido',
            'title.unique' => 'Este titulo ya está registrado',
        ];


        return parent::validating($rules, $model);
    }
}
