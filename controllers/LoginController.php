<?php

namespace Controllers;

use MVC\Router;


class LoginController
{
    public static function login(Router $router)
    {   
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/login', [
            'titulo'=>'Iniciar Sesion'
        ]);
    }

    public static function logout()
    {
        echo "hola";
    }

    public static function crear(Router $router)
    {
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/crear', [
            'titulo'=>'Crea tu cuenta en UpTask'
        ]);
    }

    public static function olvide()
    {
        echo "olvide";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }
    public static function restablecer()
    {
        echo "olvide";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }
    }

    public static function mensaje()
    {
        echo "olvide";
    }
    public static function confirmar()
    {
        echo "olvide";
    }
}
