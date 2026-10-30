<?php
// views/mecanicos.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/MecanicoController.php';

$controller = new MecanicoController();
$mensaje = $controller->procesarRegistro();
$mecanicos = $controller->obtenerMecanicos();
?>

    <style>
        .crud-container { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; padding: 2rem; }
        .form-card, .table-card { background: #FFFFFF; padding: 2rem; border-radius: 12px; border: 1px solid #D2D7DF; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-group label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #0A2540; }
        .form-group input, .form-group select { padding: 0.75rem; border: 1px solid #D2D7DF; border-radius: 6px; font-size: 0.9rem; background-color: #FFFFFF; }
        .form-group input:focus, .form-group select:focus { outline: none; border-color: #0A2540; box-shadow: 0 0 0 3px rgba(10,37,64,0.1); }
        .btn-save { width: 100%; background: #0A2540; color: #FFFFFF; padding: 0.85rem; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-save:hover { background: #10355A; }
        .section-title { font-size: 1.1rem; color: #0A2540; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid #F4F6F8; padding-bottom: 0.5rem; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0A2540; color: #FFFFFF; padding: 0.75rem; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 0.75rem; border-bottom: 1px solid #D2D7DF; font-size: 0.9rem; }
        tr:hover { background: #F4F6F8; }
        
        .badge-especialidad { background: #E3F2FD; color: #0D47A1; padding: 0.25rem 0.6rem; border-radius: 4px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; border: 1px solid #BBDEFB; }
        .badge-status { background: #E6F4EA; color: #137333; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600; }
        .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
        .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }
    </style>

    <div class="crud-container">
        <!-- FORMULARIO DE REGISTRO -->
        <div class="form-card">
            <h2 class="section-title"><i class="fa-solid fa-user-gear"></i> Registrar Mecánico</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= strpos($mensaje, 'Éxito') === 0 ? 'alert-success' : 'alert-error' ?>">
                    <?= e($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej. Pedro Alcántara">
                </div>
                
                <div class="form-group">
                    <label for="documento_identidad">DNI / Documento</label>
                    <input type="text" id="documento_identidad" name="documento_identidad" required placeholder="8 dígitos" maxlength="12">
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono / Celular</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Ej. 912345678">
                </div>
                
                <div class="form-group">
                    <label for="especialidad">Especialidad Técnica</label>
                    <select id="especialidad" name="especialidad" required>
                        <option value="">-- Seleccione Especialidad --</option>
                        <option value="Mecánica General">Mecánica General</option>
                        <option value="Electricidad Automotriz">Electricidad Automotriz</option>
                        <option value="Planchado y Pintura">Planchado y Pintura</option>
                        <option value="Inyección Electrónica">Inyección Electrónica</option>
                        <option value="Sistema de Frenos y Suspensión">Sistema de Frenos y Suspensión</option>
                    </select>
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar Técnico</button>
            </form>
        </div>

        <!-- TABLA DE MECÁNICOS -->
        <div class="table-card">
            <h2 class="section-title"><i class="fa-solid fa-users-gear"></i> Staff Técnico del Taller</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Técnico</th>
                        <th>DNI</th>
                        <th>Especialidad asignada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mecanicos)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #616E7C; padding: 2rem;">No hay personal mecánico registrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mecanicos as $m): ?>
                            <tr>
                                <td><span style="color: #616E7C;">#<?= e($m['id']) ?></span></td>
                                <td><strong><?= e($m['nombre']) ?></strong><br><small style="color: #616E7C;"><i class="fa-solid fa-phone text-xs"></i> <?= e($m['telefono']) ?></small></td>
                                <td><?= e($m['documento_identidad']) ?></td>
                                <td><span class="badge-especialidad"><?= e($m['especialidad']) ?></span></td>
                                <td><span class="badge-status"><?= e($m['estado']) ?></span></td>
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
