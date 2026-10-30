<?php
// model/OrdenModel.php
require_once __DIR__ . '/../config/database.php';

class OrdenModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todas las órdenes de trabajo con detalles del vehículo, dueño y mecánico
    public function listarTodo(): array {
        $sql = "SELECT o.*, v.placa, v.marca, v.modelo, c.nombre AS cliente_nombre, m.nombre AS mecanico_nombre
                FROM ordenes_trabajo o
                INNER JOIN vehiculos v ON o.vehiculo_id = v.id
                INNER JOIN clientes c ON v.cliente_id = c.id
                INNER JOIN mecanicos m ON o.mecanico_id = m.id
                ORDER BY o.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Listar vehículos disponibles para asociar a una orden
    public function obtenerVehiculosAux(): array {
        $sql = "SELECT v.id, v.placa, v.marca, v.modelo, c.nombre AS cliente_nombre 
                FROM vehiculos v 
                INNER JOIN clientes c ON v.cliente_id = c.id 
                ORDER BY v.placa ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Listar mecánicos disponibles para la asignación
    public function obtenerMecanicosAux(): array {
        // CORREGIDO: Usamos la columna real 'activo = 1' en lugar de 'estado'
        $sql = "SELECT id, nombre, especialidad FROM mecanicos WHERE activo = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    // Insertar una nueva orden de trabajo de manera limpia
    public function registrar(array $datos): bool {
        $sql = "INSERT INTO ordenes_trabajo (vehiculo_id, mecanico_id, descripcion_falla, diagnostico, costo_mano_obra, estado, fecha_ingreso) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['vehiculo_id'],
            $datos['mecanico_id'],
            $datos['descripcion_falla'],
            $datos['diagnostico'],
            $datos['costo_mano_obra'],
            $datos['estado'] ?? 'En Proceso'
        ]);
    }
}
