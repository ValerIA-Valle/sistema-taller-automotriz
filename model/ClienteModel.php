<?php
// model/ClienteModel.php
require_once __DIR__ . '/../config/database.php';

class ClienteModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todos los clientes con la cantidad de vehículos que tienen registrados
    public function listarTodo(): array {
        $sql = "SELECT c.*, COUNT(v.id) AS total_vehiculos 
                FROM clientes c 
                LEFT JOIN vehiculos v ON c.id = v.cliente_id 
                GROUP BY c.id 
                ORDER BY c.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
