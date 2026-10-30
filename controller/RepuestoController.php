<?php
// controller/RepuestoController.php
require_once __DIR__ . '/../model/RepuestoModel.php';

class RepuestoController {
    private $modelo;

    public function __construct() {
        $this->modelo = new RepuestoModel();
    }

    public function obtenerRepuestos(): array {
        return $this->modelo->listarTodo();
    }

    public function procesarRegistro(): string {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                validarCsrf();

                $nombre = trim($_POST['nombre'] ?? '');
                $codigo = trim($_POST['codigo_pieza'] ?? '');
                $precio = floatval($_POST['precio_venta'] ?? 0.0);
                $stock = intval($_POST['stock'] ?? 0);

                if (empty($nombre) || empty($codigo)) {
                    throw new Exception("El nombre del repuesto y el código de pieza son obligatorios.");
                }

                if ($precio <= 0) {
                    throw new Exception("El precio de venta debe ser mayor a cero.");
                }

                if ($stock < 0) {
                    throw new Exception("El stock inicial no puede ser un valor negativo.");
                }

                $datos = [
                    'nombre' => $nombre,
                    'codigo_pieza' => strtoupper($codigo),
                    'precio_venta' => $precio,
                    'stock' => $stock
                ];

                if ($this->modelo->registrar($datos)) {
                    return "Éxito: Repuesto registrado en el inventario correctamente.";
                }
            } catch (Throwable $ex) {
                return "Error: " . $ex->getMessage();
            }
        }
        return '';
    }
}
