<?php

class AuthMiddleware
{
    public static function handle()
    {
        if (!auth_check()) {
            Session::startMiddleware(route('auth.login'), getCurrentURL());
            return redirect(route('auth.login'));
        }

        return true;
    }
}
