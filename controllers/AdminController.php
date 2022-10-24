<?php

namespace Controllers;

use DateTimeZone;
use MVC\Router;
use Model\AdminCita;

class AdminController
{
    public static function index(Router $router)
    {
        session_start();

        isAdmin();

        $fecha = $_GET['fecha'] ?? date('Y-m-d');

        //debuguear($fecha);

        $fecha_aux = explode('-', $fecha);

        if (!checkdate($fecha_aux[1], $fecha_aux[2], $fecha_aux[0])) {
            header('Location: /404');
        } else {
            //Consultar la base de datos
            $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
            $consulta .= " usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
            $consulta .= " FROM citas  ";
            $consulta .= " LEFT OUTER JOIN usuarios ";
            $consulta .= " ON citas.usuarioId = usuarios.id  ";
            $consulta .= " LEFT OUTER JOIN citasservicios ";
            $consulta .= " ON citasservicios.citaId = citas.id ";
            $consulta .= " LEFT OUTER JOIN servicios ";
            $consulta .= " ON servicios.id = citasservicios.serviciosId ";
            $consulta .= " WHERE fecha =  '${fecha}' ";

            $citas = AdminCita::SQL($consulta);
        }

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}
