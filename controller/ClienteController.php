<?php
// controller/ClienteController.php
require_once __DIR__ . '/../model/ClienteModel.php';

class ClienteController {
    private $modelo;

    public function __construct() {
        $this->modelo = new ClienteModel();
    }

    public function obtenerClientes(): array {
        return $this->modelo->listarTodo();
    }
}
