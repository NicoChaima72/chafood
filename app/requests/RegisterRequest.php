<?php

class RegisterRequest extends Request
{
    public static function validate($model = null)
    {
        $rules = [
            'name' => "required",
            'email' => "required||unique:users,email||pattern:/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/",
            'password' => "required||min:8",
            'verify-password' => "required",
        ];


        Request::$messages = [
            'email.unique' => 'Email ya registrado',
            'email.required' => 'Ingresa un email',
            'email.pattern' => 'Email no valido',
            'password.required' => 'Ingresa una contraseña',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ];


        return parent::validating($rules, $model);
    }
}
