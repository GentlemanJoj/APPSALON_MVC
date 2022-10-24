<?php

namespace Controllers;

use Model\Servicio;
use Model\Citas;
use Model\CitasServicios;

class ApiController
{
    public static function index()
    {
        $servicios = Servicio::all();

        //retorna la representación en json del parámetro
        echo json_encode($servicios);
    }

    public static function guardar()
    {
        $cita = new Citas($_POST);
        $resultado = $cita->guardar();

        //Separar los id de servicios
        $idServicios = explode(',', $_POST['servicios']);

        //Almacenar los servicios con su respectiva cita
        foreach ($idServicios as $idservicio) {
            $args = [
                'citaId' => $resultado['id'],
                'serviciosId' => $idservicio
            ];
            $citaservicios = new CitasServicios($args);
            $citaservicios->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    public static function eliminar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            if (filter_var($id, FILTER_VALIDATE_INT)) {
                $cita = Citas::find($id);
            }

            $cita->eliminar();

            //Redirecionar a la página que vengo
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}
