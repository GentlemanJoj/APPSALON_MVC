<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuarios;

class LoginController
{

    public static function login(Router $router)
    {
        $usuario = new Usuarios();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarLogin();

            if (empty($alertas)) {
                //Comprobar información
                //Consulta la DB
                $resultado = $usuario->existeUsuario();
                //Confirmar resultado
                if ($resultado->num_rows) {
                    //Obtener arreglo asociativo
                    $resultado = $resultado->fetch_assoc();
                    //Comparar email y contraseña
                    $auth = $usuario->comprobarDatos($resultado);

                    if (!$auth) {
                        $alertas = Usuarios::getAlertas();
                    } else {
                        session_start();

                        $_SESSION['id'] = $resultado['id'];
                        $_SESSION['nombre'] = $resultado['nombre'] . " " . $resultado['apellido'];
                        $_SESSION['email'] = $resultado['email'];
                        $_SESSION['login'] = true;

                        //Redireccionar
                        if ($resultado['admin'] === "1") {
                            $_SESSION['admin'] = $resultado['admin'] ?? null;

                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    Usuarios::setAlerta('error', 'correo no registrado');
                    $alertas = Usuarios::getAlertas();
                }
            }
        }

        $router->render('auth/login', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuarios($_POST);

            $alertas = $usuario->validarOlvide();

            if (empty($alertas)) {
                $resultado = Usuarios::where('email', $usuario->email);

                if (!empty($resultado) && $resultado->confirmado === '1') {
                    Usuarios::setAlerta('exito', 'Revise su correo');

                    //Crear el token de confirmación
                    $resultado->crearToken();
                    //Guardar en la DB
                    $resultado->guardar();

                    //Enviar correo
                    $email = new Email($resultado->email, $resultado->nombre, $resultado->token);

                    $email->enviarRecuperar();
                } else {
                    Usuarios::setAlerta('error', 'Email no registrado o no confirmado');
                }
            }
        }

        $alertas = Usuarios::getAlertas();

        $router->render('auth/olvide', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        $usuario = Usuarios::where('token', $token);

        if (!$usuario) {
            Usuarios::setAlerta('error', 'Token inválido');
            $error = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Usuarios($_POST);

            $alertas = $auth->validarRestablecer();

            if (empty($alertas)) {
                $usuario->password = $auth->password;
                $usuario->hashPassword();
                $usuario->token = '';
                $resultado = $usuario->guardar();
                if ($resultado) {
                    Usuarios::setAlerta('exito', 'Password Actualizado Correctamente');

                    header('Refresh: 3; url=/');
                }
            }
        }

        $alertas = Usuarios::getAlertas();

        $router->render('auth/restablecer', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuarios();
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validar();

            if (empty($alertas)) {
                //Verrificar si el usuario ya existe
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    //Crea una nueva instancia de errores para llenar errores y pasarlo a la vista
                    $alertas['error'][] = 'Usuario ya registrado';
                } else {
                    //No registrado
                    //Hash al password 
                    $usuario->hashPassword();

                    //Generar un token único
                    $usuario->crearToken();

                    //Enviar el email de confirmación 
                    //Instanciar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/crear', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router)
    {
        //Verificar si el token es válido o no
        $alertas = [];

        //Sanitizar la información
        $token = s($_GET['token']);

        $usuario = Usuarios::where('token', $token);

        if (empty($usuario) || $usuario->token === "") {
            //Mensaje de error
            Usuarios::setAlerta('error', 'Token no valido');
            $confirmado = false;
        } else {
            //Modificar el usuario confirmado
            $usuario->confirmado = "1";
            $usuario->token = "";
            $usuario->guardar();

            Usuarios::setAlerta('exito', 'Usuario comprobado correctamente');
            $confirmado = true;
        }

        $alertas = Usuarios::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas,
            'confirmado' => $confirmado
        ]);
    }
}
