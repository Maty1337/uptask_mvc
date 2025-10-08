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

    public static function olvide(Router $router)
    {
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/olvide', [
            'titulo'=>'Recupera tu acceso a UpTask'
        ]);
    }
    public static function restablecer(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        $router->render('auth/restablecer', [
            'titulo'=>'Restablece tu password'
        ]);
    }

    public static function mensaje(Router $router)
    {
           $router->render('auth/mensaje', [
            'titulo'=>'Cuenta creada correctamente'
        ]);
    }
    public static function confirmar(Router $router)
    {
         $router->render('auth/confirmar', [
            'titulo'=>'Confirma tu cuenta'
        ]);
    }
}
