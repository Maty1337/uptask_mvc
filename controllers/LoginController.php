<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;


class LoginController
{
        public static function login(Router $router)
    {   
        $alertas = []; // <-- agregar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $usuario->email);

               if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    // El usuario existe, verificar el password
                    if(password_verify($_POST['password'], $usuario->password)){
                        //iniciar la sesion
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'Password incorrecto');
                    }
                }
            }
        }
    

        $alertas = Usuario::getAlertas();
        
        $router->render('auth/login', [
            'titulo'=>'Iniciar Sesion',
            'alertas' => $alertas // <-- agregar
        ]);
    }
    public static function logout()
    {
       session_start();
       $_SESSION = [];
       header('Location: /');
    }

   public static function crear(Router $router)
{
    $alertas = [];
    $usuario = new Usuario();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario->sincronizar($_POST);
        $alertas = $usuario->validarNuevaCuenta();

        if (empty($alertas)) {
            // Verificar que el usuario no esté registrado
            $existeUsuario = Usuario::where('email', $usuario->email);

            if ($existeUsuario) {
                Usuario::setAlerta('error', 'El usuario ya está registrado');
                $alertas = Usuario::getAlertas(); // <-- cambio clave
            } else {
                 
                // Hash de password
                $usuario->hashPassword();
                // Eliminar password2
                unset($usuario->password2);
                // Generar token
                $usuario->crearToken();
                
                // Crear usuario
                $resultado = $usuario->guardar();

                // Enviar email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarConfirmacion();

                if ($resultado) {
                    header('Location: /mensaje');
                    exit; // <- importante
                } else {
                    Usuario::setAlerta('error', 'No se pudo guardar el usuario');
                    $alertas = Usuario::getAlertas();
                }
            }
        }
    }

    $router->render('auth/crear', [
        'titulo'  => 'Crea tu cuenta en UpTask',
        'usuario' => $usuario,
        'alertas' => $alertas
    ]);
}

     public static function olvide(Router $router)
    {
        $alertas = []; // <-- agregar

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if (empty($alertas)) {
                // Buscar usuario
                $usuario = Usuario::where('email', $usuario->email);
                
                if ($usuario && $usuario->confirmado === "1") {
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta de exito
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                }
            }
        }

            $alertas = Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo'=>'Recupera tu acceso a UpTask',
            'alertas' => $alertas // <-- agregar
        ]);
    }
      public static function restablecer(Router $router)
    {
        $token = s($_GET['token']);
        $mostar = true;
        if (!$token) header('Location: /');

        $usuario = Usuario::where('token', $token);        
        
        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $mostar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el nuevo password y guardarlo
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();

            if (empty($alertas)) {
                // Hash del nuevo password
                $usuario->hashPassword();
                // Eliminar el token
                $usuario->token = null;
                unset($usuario->password2);

                // Guardar el usuario
                $resultado = $usuario->guardar();
                if ($resultado) {
                    header('Location: /');
                }
            } 
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/restablecer', [
            'titulo'=>'Restablece tu password',
            'alertas' => $alertas,
            'mostar' => $mostar
        ]);
    }

    public static function mensaje(Router $router)
    {
        $alertas = []; // <-- agregar

           $router->render('auth/mensaje', [
            'titulo'=>'Cuenta creada correctamente',
            'alertas' => $alertas // <-- agregar
        ]);
    }
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);
        if (!$token) header('Location: /');

        // Encotrar usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            // Guardar en la base de datos
            $usuario->guardar();
        Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();

         $router->render('auth/confirmar', [
            'titulo'=>'Confirma tu cuenta',
            'alertas' => $alertas // <-- agregar
        ]);
    }
}