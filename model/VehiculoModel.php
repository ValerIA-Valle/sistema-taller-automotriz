<?php
// model/VehiculoModel.php
require_once __DIR__ . '/../config/database.php';

class VehiculoModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Listar todos los vehículos con los datos de sus dueños (Clientes)
    public function listarTodo(): array {
        $sql = "SELECT v.*, c.nombre AS cliente_nombre, c.telefono AS cliente_telefono 
                FROM vehiculos v 
                INNER JOIN clientes c ON v.cliente_id = c.id 
                ORDER BY v.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Registrar un cliente y su vehículo en una sola transacción segura
    public function registrarConPropietario(array $datosCliente, array $datosVehiculo): bool {
        try {
            $this->db->beginTransaction();

            // 1. Insertar el Cliente
            $sqlCliente = "INSERT INTO clientes (nombre, dni_ruc, telefono, email) VALUES (?, ?, ?, ?)";
            $stmtC = $this->db->prepare($sqlCliente);
            $stmtC->execute([
                $datosCliente['nombre'],
                $datosCliente['dni_ruc'],
                $datosCliente['telefono'],
                $datosCliente['email']
            ]);
            
            // Obtener el ID generado para el cliente
            $clienteId = $this->db->lastInsertId();

            // 2. Insertar el Vehículo vinculado al cliente
            $sqlVehiculo = "INSERT INTO vehiculos (cliente_id, placa, marca, modelo, anio, color) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtV = $this->db->prepare($sqlVehiculo);
            $stmtV->execute([
                $clienteId,
                strtoupper($datosVehiculo['placa']),
                $datosVehiculo['marca'],
                $datosVehiculo['modelo'],
                $datosVehiculo['anio'],
                $datosVehiculo['color']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Error al registrar en el sistema: " . $e->getMessage());
        }
    }
}
