<?php
// controller/OrdenController.php
require_once __DIR__ . '/../model/OrdenModel.php';

class OrdenController {
    private $modelo;

    public function __construct() {
        $this->modelo = new OrdenModel();
    }

    public function obtenerOrdenes(): array {
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
                $falla = trim($_POST['descripcion_falla'] ?? '');
                $diagnostico = trim($_POST['diagnostico'] ?? '');
                $mano_obra = floatval($_POST['costo_mano_obra'] ?? 0.0);
                $estado = trim($_POST['estado'] ?? 'En Proceso');

                if ($vehiculo_id <= 0 || $mecanico_id <= 0 || empty($falla)) {
                    throw new Exception("El vehículo, el mecánico asignado y la descripción de la falla son obligatorios.");
                }

                if ($mano_obra < 0) {
                    throw new Exception("El costo de la mano de obra no puede ser un valor negativo.");
                }

                $datos = [
                    'vehiculo_id' => $vehiculo_id,
                    'mecanico_id' => $mecanico_id,
                    'descripcion_falla' => $falla,
                    'diagnostico' => $diagnostico,
                    'costo_mano_obra' => $mano_obra,
                    'estado' => $estado
                ];

                if ($this->modelo->registrar($datos)) {
                    return "Éxito: Orden de Trabajo abierta correctamente.";
                }
            } catch (Throwable $ex) {
                return "Error: " . $ex->getMessage();
            }
        }
        return '';
    }
}
