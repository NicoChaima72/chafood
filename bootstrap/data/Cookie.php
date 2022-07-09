<?php

class Cookie
{
    public static function generateLoginCookie($user_id)
    {
        $param = uniqid();
        User::find($user_id)->updateRememberToken($param);
        setcookie(Encrypt::encryption('remember_token'), $param, strtotime('+30 days'), '/', false, false);
    }

    public static function deleteLoginCookie($user_id)
    {
        if (isset($_COOKIE[Encrypt::encryption('remember_token')])) {
            User::find($user_id)->updateRememberToken(null);
            setcookie(Encrypt::encryption('remember_token'), '', time() - 3600, "/", false, false);
        }
    }

    public static function hasLoginCookie()
    {
        if (isset($_COOKIE[Encrypt::encryption('remember_token')]))
            if (!empty($_COOKIE[Encrypt::encryption('remember_token')]))
                return true;

        return false;
    }

    public static function getLoginCookie()
    {
        $user_id = $_COOKIE[Encrypt::encryption('remember_token')];
        return $user_id;
    }
}
