<?php

class AuthController extends Controller
{
    public function handle($data = null)
    {
        // if (auth_check()) {
        // return redirect(route('pages.index'));
        // }

        return true;
    }

    public function showRegister()
    {
        AuthPolicy::isAuthenticated();

        return view('auth.register');
    }

    public function register()
    {
        AuthPolicy::isAuthenticated();
        $request = RegisterRequest::validate();
        if ($request === false) return;

        if ($request['password'] !== $request['verify-password'])
            return redirect(route('auth.show-register'), [
                'message' => 'Las contraseñas no coinciden',
                'icon' => 'fas fa-times',
                'type' => 'bg-red-600'
            ]);


        if (!empty(User::findByEmail($request['email'])))
            return redirect(route('auth.show-register'), [
                'message' => 'El email ya está registrado',
                'icon' => 'fas fa-times',
                'type' => 'bg-red-600'
            ]);

        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        $result = User::create($data);

        if ($result === 0)
            return redirect(route('auth.show-register'), [
                'message' => 'No se ha agregado el usuario',
                'icon' => 'fas fa-times',
                'type' => 'bg-red-600'
            ]);
        else
            return redirect(route('auth.show-login'), [
                'message' => 'Usuario creado correctamente',
                'icon' => 'fas fa-check',
                'type' => 'bg-green-600'
            ]);
    }

    public function showLogin()
    {
        AuthPolicy::isAuthenticated();
        return view('auth.login');
    }

    public function login()
    {
        AuthPolicy::isAuthenticated();
        $request = LoginRequest::validate();
        if ($request === false) return;

        $user_by_email = User::findByEmail($request['email']);
        $is_valid = false;

        if (!empty($user_by_email))
            $is_valid = password_verify($request['password'], $user_by_email->password);

        if (!$is_valid)
            return redirect(route('auth.show-login'), [
                'message' => 'Correo y/o contraseña incorrectos',
                'icon' => 'fas fa-times',
                'type' => 'bg-red-600'
            ]);

        // Agregamos a la sesion del usuario registrado el usuario
        Session::login($user_by_email);

        // Como show-login es una ruta que algun middleware redirige, si logra pasar de manera correcta procedemos a redireccionar a la url
        // que queria ingresar el usuario 
        Session::nextMiddleware();

        if (isset($request['remember-me']))
            Cookie::generateLoginCookie($user_by_email->id);

        return redirect(route('pages.index'));
    }

    public function logout()
    {
        AuthPolicy::isNotAuthenticated();
        Cookie::deleteLoginCookie(auth()->id);
        Session::logout();
        return redirect(route('pages.index'));
    }
}
