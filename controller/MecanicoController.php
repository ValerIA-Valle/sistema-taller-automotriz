<?php
// controller/MecanicoController.php
require_once __DIR__ . '/../model/MecanicoModel.php';

class MecanicoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new MecanicoModel();
    }

    public function obtenerMecanicos(): array {
        return $this->modelo->listarTodo();
    }

    public function procesarRegistro(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                validarCsrf();

                $nombre = trim($_POST['nombre'] ?? '');
                $doc = trim($_POST['documento_identidad'] ?? '');
                $tel = trim($_POST['telefono'] ?? '');
                $especialidad = trim($_POST['especialidad'] ?? '');

                if (empty($nombre) || empty($doc) || empty($especialidad)) {
                    throw new Exception("El nombre, documento y especialidad son campos obligatorios.");
                }

                $datos = [
                    'nombre' => $nombre,
                    'documento_identidad' => $doc,
                    'telefono' => $tel,
                    'especialidad' => $especialidad,
                    'estado' => 'Activo'
                ];

                if ($this->modelo->registrar($datos)) {
                    return "Éxito: Personal técnico registrado correctamente.";
                }
            } catch (Throwable $ex) {
                return "Error: " . $ex->getMessage();
            }
        }
        return '';
    }
}
