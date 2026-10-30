<?php
// controller/VehiculoController.php
require_once __DIR__ . '/../model/VehiculoModel.php';

class VehiculoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new VehiculoModel();
    }

    public function obtenerVehiculos(): array {
        return $this->modelo->listarTodo();
    }

    public function procesarRegistro(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                validarCsrf();

                // Validaciones estrictas del backend
                $placa = trim($_POST['placa'] ?? '');
                if (empty($placa) || strlen($placa) < 6) {
                    throw new Exception("La placa del vehículo no es válida.");
                }

                $datosCliente = [
                    'nombre'   => trim($_POST['cliente_nombre'] ?? ''),
                    'dni_ruc'  => trim($_POST['cliente_dni'] ?? ''),
                    'telefono' => trim($_POST['cliente_telefono'] ?? ''),
                    'email'    => trim($_POST['cliente_email'] ?? '')
                ];

                $datosVehiculo = [
                    'placa'  => $placa,
                    'marca'  => trim($_POST['marca'] ?? ''),
                    'modelo' => trim($_POST['modelo'] ?? ''),
                    'anio'   => intval($_POST['anio'] ?? 0),
                    'color'  => trim($_POST['color'] ?? '')
                ];

                if ($this->modelo->registrarConPropietario($datosCliente, $datosVehiculo)) {
                    return "Éxito: Vehículo y propietario registrados correctamente.";
                }
            } catch (Throwable $ex) {
                return "Error: " . $ex->getMessage();
            }
        }
        return '';
    }
}
