<?php
// views/ordenes.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/OrdenController.php';

$controller = new OrdenController();
$mensaje = $controller->procesarRegistro();
$ordenes = $controller->obtenerOrdenes();
$selectVehiculos = $controller->obtenerSelectVehiculos();
$selectMecanicos = $controller->obtenerSelectMecanicos();
?>

    <style>
        .crud-container { display: grid; grid-template-columns: 14fr 24fr; gap: 2rem; padding: 2rem; }
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
        td { padding: 0.75rem; border-bottom: 1px solid #D2D7DF; font-size: 0.9rem; vertical-align: top; }
        tr:hover { background: #F4F6F8; }
        
        .badge-placa { background: #F4F6F8; border: 1px solid #D2D7DF; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: 700; color: #0A2540; }
        .badge-status { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; border: 1px solid; text-transform: uppercase; }
        .status-proceso { background: #E3F2FD; color: #0D47A1; border-color: #BBDEFB; }
        .status-espera { background: #FFF3E0; color: #E65100; border-color: #FFE0B2; }
        .status-finalizado { background: #E6F4EA; color: #137333; border-color: #CEEAD6; }
        
        .alert { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600; }
        .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
        .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }
    </style>

    <div class="crud-container">
        <!-- FORMULARIO DE APERTURA DE ORDEN -->
        <div class="form-card">
            <h2 class="section-title"><i class="fa-solid fa-file-signature"></i> Nueva Orden de Trabajo</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= strpos($mensaje, 'Éxito') === 0 ? 'alert-success' : 'alert-error' ?>">
                    <?= e($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                
                <div class="form-group">
                    <label for="vehiculo_id">Vehículo Ingresado</label>
                    <select id="vehiculo_id" name="vehiculo_id" required>
                        <option value="">-- Seleccionar Placa --</option>
                        <?php foreach ($selectVehiculos as $v): ?>
                            <option value="<?= $v['id'] ?>">
                                <?= e($v['placa']) ?> - <?= e($v['marca']) ?> (<?= e($v['cliente_nombre']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="mecanico_id">Técnico Asignado</label>
                    <select id="mecanico_id" name="mecanico_id" required>
                        <option value="">-- Seleccionar Especialista --</option>
                        <?php foreach ($selectMecanicos as $m): ?>
                            <option value="<?= $m['id'] ?>">
                                <?= e($m['nombre']) ?> [<?= e($m['especialidad']) ?>]
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="descripcion_falla">Descripción del Síntoma / Falla</label>
                    <textarea id="descripcion_falla" name="descripcion_falla" rows="3" required placeholder="Problema reportado por el cliente o grúa..."></textarea>
                </div>

                <div class="form-group">
                    <label for="diagnostico">Diagnóstico Técnico Inicial</label>
                    <textarea id="diagnostico" name="diagnostico" rows="3" placeholder="Evaluación preliminar de los sistemas afectados..."></textarea>
                </div>

                <div class="form-group">
                    <label for="costo_mano_obra">Costo Mano de Obra (S/.)</label>
                    <input type="number" id="costo_mano_obra" name="costo_mano_obra" step="0.01" min="0" placeholder="0.00" value="0.00">
                </div>

                <div class="form-group">
                    <label for="estado">Estado Operativo</label>
                    <select id="estado" name="estado">
                        <option value="En Proceso">En Proceso</option>
                        <option value="Esperando Repuestos">Esperando Repuestos</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-folder-plus"></i> Abrir Orden de Trabajo</button>
            </form>
        </div>

        <!-- TABLA DE SEGUIMIENTO -->
        <div class="table-card">
            <h2 class="section-title"><i class="fa-solid fa-rectangle-list"></i> Monitoreo de Reparaciones</h2>
            <table>
                <thead>
                    <tr>
                        <th>N° Orden</th>
                        <th>Vehículo / Cliente</th>
                        <th>Mecánico</th>
                        <th>Falla / Diagnóstico</th>
                        <th>Mano Obra</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ordenes)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #616E7C; padding: 2rem;">No existen órdenes de trabajo activas en el taller.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ordenes as $o): ?>
                            <?php 
                                $clase_estado = 'status-proceso';
                                if($o['estado'] === 'Esperando Repuestos') $clase_estado = 'status-espera';
                                if($o['estado'] === 'Finalizado') $clase_estado = 'status-finalizado';
                            ?>
                            <tr>
                                <td><strong>#<?= e($o['id']) ?></strong><br><small style="color: #616E7C;"><?= date('d/m/Y', strtotime($o['fecha_ingreso'])) ?></small></td>
                                <td><span class="badge-placa"><?= e($o['placa']) ?></span><br><small><?= e($o['cliente_nombre']) ?></small></td>
                                <td><strong><?= e($o['mecanico_nombre']) ?></strong></td>
                                <td>
                                    <span style="font-size: 0.85rem; display: block;"><strong>Falla:</strong> <?= e($o['descripcion_falla']) ?></span>
                                    <?php if(!empty($o['diagnostico'])): ?>
                                        <span style="font-size: 0.85rem; display: block; color: #616E7C; margin-top: 0.2rem;"><strong>Diag:</strong> <?= e($o['diagnostico']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><strong>S/. <?= number_format($o['costo_mano_obra'], 2) ?></strong></td>
                                <td><span class="badge-status <?= $clase_estado ?>"><?= e($o['estado']) ?></span></td>
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
