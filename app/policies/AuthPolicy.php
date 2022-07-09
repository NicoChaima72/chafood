<?php

class AuthPolicy
{
    public static function before()
    {
    }

    public static function isAuthenticated()
    {
        if (auth_check())
            return redirect(route('pages.index'));
    }

    public static function isNotAuthenticated()
    {
        if (!auth_check())
            return redirect(route('pages.index'));
    }
}
