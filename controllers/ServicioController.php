<?php

namespace Controllers;

use MVC\Router;
use Model\Servicio;

class ServicioController
{
    public static function index(Router $router)
    {
        session_start();

        isAdmin();

        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router)
    {
        session_start();

        isAdmin();

        $alertas = [];

        $servicio = new Servicio();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                Servicio::setAlerta('exito', 'Servicio creado corretamente');
                header('Refresh: 3; Url=/servicios');
            }
        }

        $alertas = Servicio::getAlertas();

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }

    public static function actualizar(Router $router)
    {
        session_start();

        isAdmin();

        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if ($id) {
            $servicio = Servicio::find($id);
        } else {
            header('Location: /admin');
        }

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if (empty($alertas)) {
                $servicio->guardar();
                Servicio::setAlerta('exito', 'Servicio actualizado corretamente');
                header('Refresh: 3; Url=/servicios');
            }
        }

        $alertas = Servicio::getAlertas();

        $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);
    }

    public static function eliminar()
    {
        session_start();
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

            if ($id) {
                $servicio = Servicio::find($id);
                $servicio->eliminar();
                header('Location: /admin');
            }
        }
    }
}
