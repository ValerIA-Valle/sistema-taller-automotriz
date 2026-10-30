<?php
require_once __DIR__ . '/../controller/AuthController.php';

$error = '';
$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        validarCsrf();
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'registro') {
            $auth->registrar(
                trim($_POST['nombre'] ?? ''),
                trim($_POST['email'] ?? ''),
                $_POST['password'] ?? ''
            );
            $error = 'Usuario creado con éxito. Ahora puedes iniciar sesión.';
        } elseif ($accion === 'login') {
            if ($auth->ingresar(trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) {
                // Usamos header nativo con salida limpia para asegurar la redirección instantánea
                header('Location: home.php');
                exit;
            }
            $error = 'Credenciales incorrectas.';
        }

    } catch (Throwable $ex) {
        $error = $ex->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motor Express | Acceso al Sistema</title>
    <!-- Iconos profesionales para darle el toque Pro -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="../assetes/css/style.css">
</head>
<body class="auth-split-screen">

    <div class="split-container">
        
        <!-- MITAD IZQUIERDA: Imagen de marca / Industrial / Mecánica -->
        <div class="left-panel">
            <div class="left-overlay"></div>
            <div class="left-content">
                <div class="brand-top">
                    <span class="brand-icon"><i class="fa-solid fa-screwdriver-wrench"></i></span>
                    <div>
                        <span class="brand-title">Motor Express</span>
                        <span class="brand-subtitle">Gestión Integrada</span>
                    </div>
                </div>
                <div class="brand-message">
                    <h1>Precisión y Rendimiento en Cada Detalle.</h1>
                    <p>Accede al panel de control para gestionar órdenes de trabajo, inventarios y el estado técnico de los vehículos en reparación.</p>
                </div>
                <p class="copyright">&copy; 2026 Motor Express Inc. Todos los derechos reservados.</p>
            </div>
        </div>

        <!-- MITAD DERECHA: Formulario de Login -->
        <div class="right-panel">
            <div class="auth-box">
                <!-- Branding móvil (se muestra solo cuando la pantalla es chica y el panel izquierdo se oculta) -->
                <div class="brand-mobile">
                    🔧 Motor Express <small>Gestión de Taller Automotriz</small>
                </div>

                <h2>Control de Accesos</h2>

                <!-- Renderizado del contenedor de error -->
                <?php if ($error !== ''): ?>
                    <div class="alert-error" role="status">
                        <i class="fa-solid fa-triangle-exclamation"></i> <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form method="post">
                    <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">

                    <div class="input-group">
                        <label for="nombre">Nombre Completo <small>(Solo para nuevos registros)</small></label>
                        <input id="nombre" name="nombre" placeholder="Ej. Juan Pérez">
                    </div>

                    <div class="input-group">
                        <label for="email">Correo electrónico corporativo</label>
                        <input id="email" name="email" type="email" autocomplete="email" placeholder="usuario@motorexpress.com" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Contraseña de acceso</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••••••" required>
                    </div>

                    <div class="actions">
                        <button type="submit" name="accion" value="login" class="btn-primary">
                            <i class="fa-solid fa-right-to-bracket"></i> Ingresar al Taller
                        </button>
                        
                        <div class="btn-group-secondary">
                            <button type="submit" name="accion" value="registro" class="secondary">
                                <i class="fa-solid fa-user-plus"></i> Registrar Técnico
                            </button>
                            <button type="reset" class="secondary">
                                <i class="fa-solid fa-broom"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </form>
                
                <p class="auth-note">Nota: El acceso y registro de nuevos usuarios está estrictamente restringido al personal técnico autorizado por la administración.</p>
            </div>
        </div>

    </div>

</body>
</html>
