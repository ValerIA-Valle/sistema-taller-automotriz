<?php
// views/vehiculos.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/VehiculoController.php';

$controller = new VehiculoController();
$mensaje = $controller->procesarRegistro();
$vehiculos = $controller->obtenerVehiculos();
?>
    <!-- Eliminamos el tag <html>, <head> y <body> repetidos para no duplicar la estructura -->
    <style>
        .crud-container { display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; padding: 2rem; }
        .form-card, .table-card { background: #FFFFFF; padding: 2rem; border-radius: 12px; border: 1px solid #D2D7DF; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-group label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #0A2540; }
        .form-group input { padding: 0.75rem; border: 1px solid #D2D7DF; border-radius: 6px; font-size: 0.9rem; }
        .form-group input:focus { outline: none; border-color: #0A2540; box-shadow: 0 0 0 3px rgba(10,37,64,0.1); }
        .btn-save { width: 100%; background: #0A2540; color: #FFFFFF; padding: 0.85rem; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
        .btn-save:hover { background: #10355A; }
        .section-title { font-size: 1.1rem; color: #0A2540; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid #F4F6F8; padding-bottom: 0.5rem; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0A2540; color: #FFFFFF; padding: 0.75rem; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 0.75rem; border-bottom: 1px solid #D2D7DF; font-size: 0.9rem; }
        tr:hover { background: #F4F6F8; }
        .badge-placa { background: #F4F6F8; border: 1px solid #D2D7DF; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: 700; color: #0A2540; }
        .alert { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600; }
        .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
        .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }
    </style>

    <div class="crud-container">
        <!-- FORMULARIO DE REGISTRO INTEGRADO -->
        <div class="form-card">
            <h2 class="section-title"><i class="fa-solid fa-car-wrench"></i> Registrar Ingreso</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= strpos($mensaje, 'Éxito') === 0 ? 'alert-success' : 'alert-error' ?>">
                    <?= e($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                
                <h3 style="font-size: 0.9rem; color: #616E7C; margin-bottom: 0.8rem;">Datos del Cliente</h3>
                <div class="form-group">
                    <label for="cliente_nombre">Nombre Completo</label>
                    <input type="text" id="cliente_nombre" name="cliente_nombre" required placeholder="Ej. Carlos Mendoza">
                </div>
                <div class="form-group">
                    <label for="cliente_dni">DNI / RUC</label>
                    <input type="text" id="cliente_dni" name="cliente_dni" required placeholder="8 o 11 dígitos">
                </div>
                <div class="form-group">
                    <label for="cliente_telefono">Teléfono</label>
                    <input type="tel" id="cliente_telefono" name="cliente_telefono" required placeholder="Ej. 987654321">
                </div>
                <div class="form-group">
                    <label for="cliente_email">Correo Electrónico</label>
                    <input type="email" id="cliente_email" name="cliente_email" placeholder="cliente@gmail.com">
                </div>

                <h3 style="font-size: 0.9rem; color: #616E7C; margin-top: 1.5rem; margin-bottom: 0.8rem;">Datos del Vehículo</h3>
                <div class="form-group">
                    <label for="placa">Placa Vehicular</label>
                    <input type="text" id="placa" name="placa" required placeholder="Ej. ABC-123" maxlength="8">
                </div>
                <div class="form-group">
                    <label for="marca">Marca</label>
                    <input type="text" id="marca" name="marca" required placeholder="Ej. Toyota">
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo</label>
                    <input type="text" id="modelo" name="modelo" required placeholder="Ej. Hilux">
                </div>
                <div class="form-group">
                    <label for="anio">Año de Fabricación</label>
                    <input type="number" id="anio" name="anio" required placeholder="Ej. 2022" min="1980" max="2027">
                </div>
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" id="color" name="color" placeholder="Ej. Gris Plata">
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Guardar Registro</button>
            </form>
        </div>

        <!-- TABLA DE LISTADO DINÁMICO -->
        <div class="table-card">
            <h2 class="section-title"><i class="fa-solid fa-list-check"></i> Vehículos en el Taller</h2>
            <table>
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Vehículo</th>
                        <th>Año / Color</th>
                        <th>Propietario</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($vehiculos)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #616E7C; padding: 2rem;">No hay vehículos registrados en el taller.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($vehiculos as $v): ?>
                            <tr>
                                <td><span class="badge-placa"><?= e($v['placa']) ?></span></td>
                                <td><strong><?= e($v['marca']) ?></strong> <?= e($v['modelo']) ?></td>
                                <td><?= e($v['anio']) ?> - <small><?= e($v['color']) ?></small></td>
                                <td><?= e($v['cliente_nombre']) ?></td>
                                <td><i class="fa-solid fa-phone text-xs"></i> <?= e($v['cliente_telefono']) ?></td>
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
