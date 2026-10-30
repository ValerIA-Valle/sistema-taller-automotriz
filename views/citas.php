<?php
// views/citas.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/CitaController.php';

$controller = new CitaController();
$mensaje = $controller->procesarRegistro();
$citas = $controller->obtenerCitas();
$selectVehiculos = $controller->obtenerSelectVehiculos();
$selectMecanicos = $controller->obtenerSelectMecanicos();
?>

    <style>
        .crud-container { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; padding: 2rem; }
        .form-card, .table-card { background: #FFFFFF; padding: 2rem; border-radius: 12px; border: 1px solid #D2D7DF; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-group label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #0A2540; }
        .form-group input, .form-group select, .form-group textarea { padding: 0.75rem; border: 1px solid #D2D7DF; border-radius: 6px; font-size: 0.9rem; background-color: #FFFFFF; font-family: sans-serif; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #0A2540; box-shadow: 0 0 0 3px rgba(10,37,64,0.1); }
        .btn-save { width: 100%; background: #0A2540; color: #FFFFFF; padding: 0.85rem; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-save:hover { background: #10355A; }
        .section-title { font-size: 1.1rem; color: #0A2540; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid #F4F6F8; padding-bottom: 0.5rem; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0A2540; color: #FFFFFF; padding: 0.75rem; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 0.75rem; border-bottom: 1px solid #D2D7DF; font-size: 0.9rem; }
        tr:hover { background: #F4F6F8; }
        
        .badge-placa { background: #F4F6F8; border: 1px solid #D2D7DF; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: 700; color: #0A2540; }
        .badge-status { background: #FFF3E0; color: #E65100; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; border: 1px solid #FFE0B2; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600; }
        .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
        .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }
    </style>

    <div class="crud-container">
        <!-- FORMULARIO DE PROGRAMACIÓN -->
        <div class="form-card">
            <h2 class="section-title"><i class="fa-solid fa-calendar-plus"></i> Programar Cita</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= strpos($mensaje, 'Éxito') === 0 ? 'alert-success' : 'alert-error' ?>">
                    <?= e($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                
                <div class="form-group">
                    <label for="vehiculo_id">Seleccionar Vehículo / Cliente</label>
                    <select id="vehiculo_id" name="vehiculo_id" required>
                        <option value="">-- Busque Placa o Dueño --</option>
                        <?php foreach ($selectVehiculos as $v): ?>
                            <option value="<?= $v['id'] ?>">
                                <?= e($v['placa']) ?> - <?= e($v['marca']) ?> <?= e($v['modelo']) ?> (<?= e($v['cliente_nombre']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="mecanico_id">Asignar Mecánico Responsable</label>
                    <select id="mecanico_id" name="mecanico_id" required>
                        <option value="">-- Seleccione Especialista --</option>
                        <?php foreach ($selectMecanicos as $m): ?>
                            <option value="<?= $m['id'] ?>">
                                <?= e($m['nombre']) ?> [<?= e($m['especialidad']) ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fecha_hora">Fecha y Hora de la Cita</label>
                    <input type="datetime-local" id="fecha_hora" name="fecha_hora" required>
                </div>
                
                <div class="form-group">
                    <label for="motivo">Falla Reportada / Síntomas</label>
                    <textarea id="motivo" name="motivo" rows="3" placeholder="Ej. El motor tiembla al ralentí y tiene pérdida de potencia..."></textarea>
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-calendar-check"></i> Reservar Agenda</button>
            </form>
        </div>

        <!-- TABLA DE CITAS AGENDADAS -->
        <div class="table-card">
            <h2 class="section-title"><i class="fa-solid fa-calendar-days"></i> Citas Agendadas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Vehículo / Cliente</th>
                        <th>Mecánico Asignado</th>
                        <th>Síntoma / Diagnóstico</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($citas)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #616E7C; padding: 2rem;">No hay citas agendadas para los próximos días.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($citas as $c): ?>
                            <tr>
                                <td><strong><?= date('d/m/Y', strtotime($c['fecha_hora'])) ?></strong><br><small style="color:#616E7C;"><?= date('H:i A', strtotime($c['fecha_hora'])) ?></small></td>
                                <td><span class="badge-placa"><?= e($c['placa']) ?></span><br><small><?= e($c['cliente_nombre']) ?></small></td>
                                <td><strong><?= e($c['mecanico_nombre']) ?></strong></td>
                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($c['motivo']) ?></td>
                                <td><span class="badge-status"><?= e($c['estado']) ?></span></td>
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
