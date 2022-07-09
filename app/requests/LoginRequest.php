<?php

class LoginRequest extends Request
{
    public static function validate($model = null)
    {
        $rules = [
            'email' => "required||pattern:/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/",
            'password' => "required",
        ];


        Request::$messages = [
            'email.required' => 'Ingresa un email',
            'email.pattern' => 'Email no valido',
            'password.required' => 'Ingresa una contraseña',
        ];


        return parent::validating($rules, $model);
    }
}
