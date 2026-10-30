<?php
// controller/CitaController.php
require_once __DIR__ . '/../model/CitaModel.php';

class CitaController {
    private $modelo;

    public function __construct() {
        $this->modelo = new CitaModel();
    }

    public function obtenerCitas(): array {
        return $this->modelo->listarTodo();
    }

    public function obtenerSelectVehiculos(): array {
        return $this->modelo->obtenerVehiculosAux();
    }

    public function obtenerSelectMecanicos(): array {
        return $this->modelo->obtenerMecanicosAux();
    }

    public function procesarRegistro(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                validarCsrf();

                $vehiculo_id = intval($_POST['vehiculo_id'] ?? 0);
                $mecanico_id = intval($_POST['mecanico_id'] ?? 0);
                $fecha_hora = trim($_POST['fecha_hora'] ?? '');
                $motivo = trim($_POST['motivo'] ?? '');

                if ($vehiculo_id <= 0 || $mecanico_id <= 0 || empty($fecha_hora)) {
                    throw new Exception("Debe seleccionar un vehículo, un mecánico y especificar fecha y hora.");
                }

                $datos = [
                    'vehiculo_id' => $vehiculo_id,
                    'mecanico_id' => $mecanico_id,
                    'fecha_hora' => $fecha_hora,
                    'motivo' => $motivo,
                    'estado' => 'Programada'
                ];

                if ($this->modelo->registrar($datos)) {
                    return "Éxito: Cita programada correctamente en el taller.";
                }
            } catch (Throwable $ex) {
                return "Error: " . $ex->getMessage();
            }
        }
        return '';
    }
}
