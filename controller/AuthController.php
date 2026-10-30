<?php
require_once __DIR__ . '/../config/database.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conectar();
    }

    public function ingresar(string $email, string $password): bool {
        if (empty($email) || empty($password)) {
            throw new Exception("Todos los campos son obligatorios.");
        }

        $sql = "SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];
            
            return true;
        }

        return false;
    }

    public function registrar(string $nombre, string $email, string $password): bool {
        if (empty($nombre) || empty($email) || empty($password)) {
            throw new Exception("Para registrarse debe completar todos los campos.");
        }

        if (strlen($password) < 10) {
            throw new Exception("La contraseña debe tener al menos 10 caracteres.");
        }

        // acá valida si el correo no está registrado anteriormente
        $sqlCheck = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            throw new Exception("El correo electrónico ya se encuentra registrado.");
        }

        // encripta la contraseña utilizando el seguro de php
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        $rolPorDefecto = 'Mecanico';

        $sqlInsert = "INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)";
        $stmtInsert = $this->db->prepare($sqlInsert);
        
        return $stmtInsert->execute([$nombre, $email, $passwordHash, $rolPorDefecto]);
    }
}
