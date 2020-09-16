<?php

class UpdateRecipeRequest extends Request
{
    public static function validate($model = null)
    {
        $rules = [
            'title' => "required", //||unique:recipes,title,id
            'description' => "required",
            'ingredients' => "required",
            'steps' => "required",
            'duration' => "required",
            'persons' => "required",
            'image' => "file_mime:jpg,png,jpeg||file_max:1024"
        ];


        return parent::validating($rules, $model);
    }
}
