<?php
// model/RepuestoModel.php
require_once __DIR__ . '/../config/database.php';

class RepuestoModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todo el inventario de repuestos
    public function listarTodo(): array {
        $sql = "SELECT * FROM repuestos ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Registrar una nueva pieza o repuesto en el stock
    public function registrar(array $datos): bool {
        $sql = "INSERT INTO repuestos (nombre, codigo_pieza, precio_venta, stock) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['nombre'],
            $datos['codigo_pieza'],
            $datos['precio_venta'],
            $datos['stock']
        ]);
    }
}
