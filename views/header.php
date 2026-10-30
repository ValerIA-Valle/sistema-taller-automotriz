<?php
// views/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Control de seguridad común para todas las vistas
if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_nombre'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../config/database.php';

// Detectar la página actual para marcar la pestaña activa dinámicamente
$pagina_actual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Express | Sistema del Taller</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="../assetes/css/style_home.css">
</head>
<body>

    <header class="header-taller">
        <div class="nivel-superior">
            <div class="container-flex">
                <span>📞 Soporte Técnico Interno: +51 999 888 777</span>
                <span>📍 Sede Central - Motor Express</span>
            </div>
        </div>
        <div class="nivel-inferior">
            <div class="container-flex">
                <div class="logo">🔧 MOTOR EXPRESS</div>
                <div class="usuario-info">
                    <span>Usuario: <strong><?= e($_SESSION['usuario_nombre']) ?></strong> (<?= e($_SESSION['usuario_rol']) ?>)</span>
                    <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar-principal">
        <div class="container-nav">
                <!-- Reemplaza el bloque <ul> dentro de views/header.php para corregir el botón de Inicio -->
    <ul>
        <li><a href="home.php" class="<?= $pagina_actual === 'home.php' ? 'active' : '' ?>">Inicio</a></li>
        <li><a href="clientes.php" class="<?= $pagina_actual === 'clientes.php' ? 'active' : '' ?>">Clientes</a></li>
        <li><a href="vehiculos.php" class="<?= $pagina_actual === 'vehiculos.php' ? 'active' : '' ?>">Vehículos</a></li>
        <li><a href="mecanicos.php" class="<?= $pagina_actual === 'mecanicos.php' ? 'active' : '' ?>">Mecánicos</a></li>
        <li><a href="citas.php" class="<?= $pagina_actual === 'citas.php' ? 'active' : '' ?>">Citas</a></li>
        <li><a href="ordenes.php" class="<?= $pagina_actual === 'ordenes.php' ? 'active' : '' ?>">Órdenes de Trabajo</a></li>
        <li><a href="inventario.php" class="<?= $pagina_actual === 'inventario.php' ? 'active' : '' ?>">Inventario Repuestos</a></li>
    </ul>

            
     
        </div>
    </nav>
