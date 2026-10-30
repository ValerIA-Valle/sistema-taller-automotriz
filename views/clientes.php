<?php
// views/clientes.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/ClienteController.php';

$controller = new ClienteController();
$clientes = $controller->obtenerClientes();
?>
    
    <style>
        .page-container { padding: 2.5rem; max-width: 1400px; margin: 0 auto; }
        .table-card { background: #FFFFFF; padding: 2rem; border-radius: 12px; border: 1px solid #D2D7DF; box-shadow: 0 4px 10px rgba(10, 37, 64, 0.02); }
        .table-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #F4F6F8; padding-bottom: 0.75rem; }
        .section-title { font-size: 1.3rem; color: #0A2540; font-weight: 700; margin: 0; }
        .btn-action-top { background: #0A2540; color: #FFFFFF; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; transition: background 0.2s; }
        .btn-action-top:hover { background: #10355A; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; margin-top: 0.5rem; }
        th { background: #0A2540; color: #FFFFFF; padding: 0.85rem 1rem; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
        td { padding: 1rem; border-bottom: 1px solid #D2D7DF; font-size: 0.95rem; color: #333333; }
        tr:hover { background: #F4F6F8; }
        
        .badge-doc { background: #E5E7EB; color: #1F2937; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: 600; font-size: 0.85rem; }
        .badge-count { background: rgba(10, 37, 64, 0.1); color: #0A2540; padding: 0.25rem 0.6rem; border-radius: 50%; font-weight: 700; font-size: 0.85rem; }
        .text-muted { color: #616E7C; font-size: 0.85rem; }
    </style>

    <div class="page-container">
        <div class="table-card">
            
            <div class="table-header-flex">
                <h2 class="section-title"><i class="fa-solid fa-users"></i> Directorio de Clientes</h2>
                <!-- Botón pro que redirige al flujo de ingreso unificado -->
                <a href="vehiculos.php" class="btn-action-top">
                    <i class="fa-solid fa-user-plus"></i> Nuevo Registro de Ingreso
                </a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre / Razón Social</th>
                        <th>DNI / RUC</th>
                        <th>Teléfono de Contacto</th>
                        <th>Correo Electrónico</th>
                        <th style="text-align: center;">Autos en Taller</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #616E7C; padding: 3rem;">
                                <i class="fa-solid fa-folder-open" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                No se encontraron clientes registrados en el sistema.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                            <tr>
                                <td><span class="text-muted">#<?= e($c['id']) ?></span></td>
                                <td><strong><?= e($c['nombre']) ?></strong></td>
                                <td><span class="badge-doc"><?= e($c['dni_ruc']) ?></span></td>
                                <td><i class="fa-solid fa-phone" style="font-size: 0.8rem; color: #616E7C; margin-right: 0.3rem;"></i> <?= e($c['telefono']) ?></td>
                                <td><?= !empty($c['email']) ? e($c['email']) : '<em class="text-muted">No registrado</em>' ?></td>
                                <td style="text-align: center;"><span class="badge-count"><?= e($c['total_vehiculos']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <footer class="pie-pagina">
        <p>&copy; 2026 Motor Express - Sistema de Gestión de Taller Integrado. Proyecto de Práctica Profesional | SENATI.</p>
    </footer>
</body>
</html>
