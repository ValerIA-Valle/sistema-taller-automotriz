<?php
// views/inventario.php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/../controller/RepuestoController.php';

$controller = new RepuestoController();
$mensaje = $controller->procesarRegistro();
$repuestos = $controller->obtenerRepuestos();
?>

    <style>
        .crud-container { display: grid; grid-template-columns: 14fr 24fr; gap: 2rem; padding: 2rem; }
        .form-card, .table-card { background: #FFFFFF; padding: 2rem; border-radius: 12px; border: 1px solid #D2D7DF; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1rem; }
        .form-group label { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #0A2540; }
        .form-group input { padding: 0.75rem; border: 1px solid #D2D7DF; border-radius: 6px; font-size: 0.9rem; background-color: #FFFFFF; }
        .form-group input:focus { outline: none; border-color: #0A2540; box-shadow: 0 0 0 3px rgba(10,37,64,0.1); }
        .btn-save { width: 100%; background: #0A2540; color: #FFFFFF; padding: 0.85rem; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-save:hover { background: #10355A; }
        .section-title { font-size: 1.1rem; color: #0A2540; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 2px solid #F4F6F8; padding-bottom: 0.5rem; }
        
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background: #0A2540; color: #FFFFFF; padding: 0.75rem; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 0.75rem; border-bottom: 1px solid #D2D7DF; font-size: 0.9rem; }
        tr:hover { background: #F4F6F8; }
        
        .badge-codigo { background: #F4F6F8; border: 1px solid #D2D7DF; padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace; font-weight: 700; color: #0A2540; }
        .stock-critico { color: #D32F2F; font-weight: 700; background: #FDF2F2; padding: 0.25rem 0.5rem; border-radius: 4px; border: 1px solid #FDE8E8; }
        .stock-normal { color: #137333; font-weight: 700; }
        
        .alert { padding: 0.75rem 1rem; border-radius: 6px; font-size: 0.9rem; margin-bottom: 1rem; font-weight: 600; }
        .alert-success { background: #E6F4EA; color: #137333; border: 1px solid #CEEAD6; }
        .alert-error { background: #FCE8E6; color: #C5221F; border: 1px solid #FAD2CF; }
    </style>

    <div class="crud-container">
        <!-- FORMULARIO DE ALTA DE REPUESTOS -->
        <div class="form-card">
            <h2 class="section-title"><i class="fa-solid fa-box-archive"></i> Registrar Repuesto / Pieza</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?= strpos($mensaje, 'Éxito') === 0 ? 'alert-success' : 'alert-error' ?>">
                    <?= e($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre de la Pieza / Artículo</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej. Pastillas de Freno Delanteras">
                </div>
                
                <div class="form-group">
                    <label for="codigo_pieza">Código de Pieza (OEM / SKU)</label>
                    <input type="text" id="codigo_pieza" name="codigo_pieza" required placeholder="Ej. PFB-TOY-2022">
                </div>

                <div class="form-group">
                    <label for="precio_venta">Precio de Venta al Público (S/.)</label>
                    <input type="number" id="precio_venta" name="precio_venta" step="0.01" min="0.10" required placeholder="0.00">
                </div>

                <div class="form-group">
                    <label for="stock">Cantidad / Stock Inicial</label>
                    <input type="number" id="stock" name="stock" min="0" required placeholder="Ej. 15" value="0">
                </div>

                <button type="submit" class="btn-save"><i class="fa-solid fa-boxes-stacked"></i> Ingresar Almacén</button>
            </form>
        </div>

        <!-- TABLA DE LISTADO DE INVENTARIO -->
        <div class="table-card">
            <h2 class="section-title"><i class="fa-solid fa-warehouse"></i> Control de Stock de Almacén</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descripción del Repuesto</th>
                        <th>Código OEM</th>
                        <th>Precio Venta</th>
                        <th>Stock Actual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($repuestos)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #616E7C; padding: 2rem;">No hay repuestos registrados en el inventario.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($repuestos as $r): ?>
                            <tr>
                                <td><span style="color: #616E7C;">#<?= e($r['id']) ?></span></td>
                                <td><strong><?= e($r['nombre']) ?></strong></td>
                                <td><span class="badge-codigo"><?= e($r['codigo_pieza']) ?></span></td>
                                <td><strong>S/. <?= number_format($r['precio_venta'], 2) ?></strong></td>
                                <td>
                                    <!-- Si el stock es menor o igual a 3, alerta de stock crítico -->
                                    <?php if ($r['stock'] <= 3): ?>
                                        <span class="stock-critico"><i class="fa-solid fa-triangle-exclamation"></i> <?= e($r['stock']) ?> Unidades</span>
                                    <?php else: ?>
                                        <span class="stock-normal"><?= e($r['stock']) ?> uds.</span>
                                    <?php endif; ?>
                                </td>
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
