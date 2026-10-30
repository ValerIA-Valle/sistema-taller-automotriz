<?php
// model/CitaModel.php
require_once __DIR__ . '/../config/database.php';

class CitaModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todas las citas trayendo información del vehículo, dueño y mecánico asignado
    public function listarTodo(): array {
        $sql = "SELECT c.*, v.placa, v.marca, v.modelo, cl.nombre AS cliente_nombre, m.nombre AS mecanico_nombre
                FROM citas c
                INNER JOIN vehiculos v ON c.vehiculo_id = v.id
                INNER JOIN clientes cl ON v.cliente_id = cl.id
                INNER JOIN mecanicos m ON c.mecanico_id = m.id
                ORDER BY c.fecha_hora ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener listas auxiliares para llenar los cuadros de selección (Selects)
    public function obtenerVehiculosAux(): array {
        $sql = "SELECT v.id, v.placa, v.marca, v.modelo, cl.nombre AS cliente_nombre 
                FROM vehiculos v 
                INNER JOIN clientes cl ON v.cliente_id = cl.id 
                ORDER BY v.placa ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerMecanicosAux(): array {
        // Corregido: usamos la columna 'activo' en lugar de 'estado'
        $sql = "SELECT id, nombre, especialidad FROM mecanicos WHERE activo = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    // Registrar la programación de una cita
    public function registrar(array $datos): bool {
        $sql = "INSERT INTO citas (vehiculo_id, mecanico_id, fecha_hora, motivo, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['vehiculo_id'],
            $datos['mecanico_id'],
            $datos['fecha_hora'],
            $datos['motivo'],
            $datos['estado'] ?? 'Programada'
        ]);
    }
}
