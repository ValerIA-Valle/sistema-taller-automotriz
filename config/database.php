<?php
class Database
{
    private string $host = 'localhost';
    private string $name = 'taller_automot_db'; 
    private string $user = 'root';
    private string $password = '';

    public function conectar(): PDO
    {
        return new PDO(
            "mysql:host={$this->host};dbname={$this->name};charset=utf8mb4",
            $this->user,
            $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }
} 
if (!function_exists('e')) {
    function e(?string $texto): string {
        return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirigir')) {
    function redirigir(string $pagina): void {
        header("Location: " . $pagina);
        exit();
    }
}

if (!function_exists('csrf')) {
    function csrf(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('validarCsrf')) {
    function validarCsrf(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $tokenPost = $_POST['csrf'] ?? '';
        $tokenSesion = $_SESSION['csrf_token'] ?? '';
        
        if (empty($tokenPost) || !hash_equals($tokenSesion, $tokenPost)) {
            throw new Exception("Error de seguridad: Token CSRF inválido.");
        }
    }
}
