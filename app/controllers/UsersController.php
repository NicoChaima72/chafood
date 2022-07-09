<?php

require_once model('User');

class UsersController
{
    public function index()
    {
        $users = User::get();
        var_dump($users);
    }

    public function create()
    {
    }

    public function store(User $request)
    {
    }

    public function show(User $user)
    {
        var_dump($user);
    }

    public function edit()
    {
    }

    public function update(User $request, User $user)
    {
        $user->update($request);
    }

    public function destroy(User $user)
    {
    }
}
