<?php
// model/MecanicoModel.php
require_once __DIR__ . '/../config/database.php';

class MecanicoModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todos los técnicos mecánicos
    public function listarTodo(): array {
        $sql = "SELECT * FROM mecanicos ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Registrar un nuevo mecánico con su especialidad técnica
    public function registrar(array $datos): bool {
        $sql = "INSERT INTO mecanicos (nombre, documento_identidad, telefono, especialidad, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['nombre'],
            $datos['documento_identidad'],
            $datos['telefono'],
            $datos['especialidad'],
            $datos['estado'] ?? 'Activo'
        ]);
    }
}
