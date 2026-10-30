<?php
// views/home.php
require_once __DIR__ . '/header.php';

// Conexión nativa a la base de datos para los contadores dinámicos del taller
$database = new Database();
$db = $database->conectar();

try {
    // 1. Contador real de Reparaciones Pendientes (En Proceso o Esperando Repuestos)
    $sqlPendientes = "SELECT COUNT(*) FROM ordenes_trabajo WHERE estado IN ('En Proceso', 'Esperando Repuestos')";
    $stmtPendientes = $db->query($sqlPendientes);
    $totalPendientes = $stmtPendientes->fetchColumn();

    // 2. Contador real de Ingresos del Día (Sumatoria de Pagos recibidos hoy)
    $sqlIngresos = "SELECT COALESCE(SUM(monto_total), 0) FROM pagos WHERE DATE(fecha_pago) = CURDATE()";
    $stmtIngresos = $db->query($sqlIngresos);
    $totalIngresos = $stmtIngresos->fetchColumn();

    // 3. Contador real de Vehículos Listos (Orden finalizada lista para entrega)
    $sqlListos = "SELECT COUNT(*) FROM ordenes_trabajo WHERE estado = 'Finalizado'";
    $stmtListos = $db->query($sqlListos);
    $totalListos = $stmtListos->fetchColumn();

    // 4. Listado dinámico de las últimas 5 órdenes abiertas en el taller
    $sqlRecientes = "SELECT o.id, v.placa, v.marca, v.modelo, o.estado, o.fecha_ingreso 
                     FROM ordenes_trabajo o
                     INNER JOIN vehiculos v ON o.vehiculo_id = v.id
                     ORDER BY o.id DESC LIMIT 5";
    $stmtRecientes = $db->query($sqlRecientes);
    $ordenesRecientes = $stmtRecientes->fetchAll();

} catch (Exception $e) {
    // Valores de respaldo en caso de que falte poblar alguna tabla
    $totalPendientes = 0;
    $totalIngresos = 0;
    $totalListos = 0;
    $ordenesRecientes = [];
}
?>

    <div class="contenedor-layout">
        
        <main class="contenido-dashboard">
            <div class="dashboard-header">
                <h2>Panel de Estado Operativo</h2>
                <p>Monitoreo en tiempo real de los servicios automotrices en el taller.</p>
            </div>
            
            <div class="grid-tarjetas">
                <!-- Tarjeta: Pendientes -->
                <div class="tarjeta-card">
                    <div class="card-icon">⚙️</div>
                    <div class="card-info">
                        <h3><?= e($totalPendientes) ?></h3>
                        <p>Reparaciones Pendientes</p>
                        <small>Vehículos en cola o proceso técnico</small>
                    </div>
                </div>

                <!-- Tarjeta: Ingresos -->
                <div class="tarjeta-card">
                    <div class="card-icon">💰</div>
                    <div class="card-info">
                        <h3>S/. <?= number_format($totalIngresos, 2) ?></h3>
                        <p>Ingresos del Día</p>
                        <small>Efectivo, Tarjetas y Yape procesados</small>
                    </div>
                </div>

                <!-- Tarjeta: Listos -->
                <div class="tarjeta-card">
                    <div class="card-icon">🚗</div>
                    <div class="card-info">
                        <h3><?= e($totalListos) ?></h3>
                        <p>Vehículos Listos</p>
                        <small>Unidades listas para entrega al cliente</small>
                    </div>
                </div>
            </div>

            <!-- Tabla Resumen Dinámica -->
            <div class="seccion-tabla-resumen" style="margin-top: 2rem; background: #FFFFFF; padding: 1.5rem; border-radius: 8px; border: 1px solid #D2D7DF;">
                <h3 style="color: #0A2540; font-size: 1.1rem; margin-bottom: 1rem; font-weight: 700;"><i class="fa-solid fa-clock-rotate-left"></i> Últimos Vehículos Ingresados</h3>
                
                <?php if (empty($ordenesRecientes)): ?>
                    <div class="tabla-placeholder" style="text-align: center; color: #616E7C; padding: 1rem;">
                        <p>No se registran órdenes de reparación abiertas en el sistema.</p>
                    </div>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                        <thead>
                            <tr style="border-bottom: 2px solid #F4F6F8; color: #0A2540;">
                                <th style="padding: 0.5rem; font-weight: 700;">N° Orden</th>
                                <th style="padding: 0.5rem; font-weight: 700;">Placa Vehicular</th>
                                <th style="padding: 0.5rem; font-weight: 700;">Marca / Modelo</th>
                                <th style="padding: 0.5rem; font-weight: 700;">Fecha Ingreso</th>
                                <th style="padding: 0.5rem; font-weight: 700;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordenesRecientes as $rec): ?>
                                <tr style="border-bottom: 1px solid #F4F6F8;">
                                    <td style="padding: 0.6rem 0.5rem; color: #616E7C;">#<?= e($rec['id']) ?></td>
                                    <td style="padding: 0.6rem 0.5rem;"><span style="background: #F4F6F8; border: 1px solid #D2D7DF; padding: 0.2rem 0.4rem; border-radius: 4px; font-family: monospace; font-weight: 700; color: #0A2540;"><?= e($rec['placa']) ?></span></td>
                                    <td style="padding: 0.6rem 0.5rem;"><strong><?= e($rec['marca']) ?></strong> <?= e($rec['modelo']) ?></td>
                                    <td style="padding: 0.6rem 0.5rem; color: #616E7C;"><?= date('d/m/Y H:i', strtotime($rec['fecha_ingreso'])) ?></td>
                                    <td style="padding: 0.6rem 0.5rem;">
                                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">
                                            <?= e($rec['estado']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>

        <aside class="menu-vertical-derecho">
            <h3>Acciones del Sistema</h3>
            <div class="grupo-botones-menu">
                <button class="btn-menu-lateral" onclick="location.href='ordenes.php'">🔧 Nueva Orden de Trabajo</button>
                <button class="btn-menu-lateral" onclick="location.href='citas.php'">📅 Programar Cita</button>
                <button class="btn-menu-lateral" onclick="location.href='vehiculos.php'">👥 Registrar Cliente / Auto</button>
                <button class="btn-menu-lateral" onclick="location.href='inventario.php'">📦 Gestionar Repuestos</button>
                <button class="btn-menu-lateral btn-destacado" onclick="location.href='pagos.php'">💳 Registrar Pago Directo</button>
            </div>
        </aside>

    </div>

    <footer class="pie-pagina">
        <p>&copy; 2026 Motor Express - Sistema de Gestión de Taller Integrado. Proyecto de Práctica Profesional | SENATI.</p>
    </footer>

</body>
</html>
