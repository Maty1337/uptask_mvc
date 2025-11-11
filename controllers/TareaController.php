<?php 

namespace Controllers;

use Model\Tarea;
use Model\Proyecto;


class TareaController {
    public static function index() {
        // Lógica para obtener y devolver las tareas
    }
    public static function crear() {
        // Lógica para crear una tarea
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $proyectoId = $_POST['proyectoId'];

            $proyecto = Proyecto::where('url', $proyectoId);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error al agregar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }            
                //Crear la tarea
                $tarea = new Tarea($_POST);
                $tarea->proyectoId = $proyecto->id;
                $resultado = $tarea->guardar();
                $respuesta = [
                    "tipo" => "exito",
                    "id" => $resultado['id'],
                    "mensaje" => "Tarea Creada Correctamente"
                ];
                echo json_encode($respuesta);            
        }
    }

    public static function actualizar() {
        // Lógica para actualizar una tarea
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }
    }

    public static function eliminar() {
        // Lógica para eliminar una tarea
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }
    }
}