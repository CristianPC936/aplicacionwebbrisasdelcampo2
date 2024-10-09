
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - E.O.R.M. Brisas del Campo</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="back-button">
        <a href="../index.php">&#8592; Volver</a>
    </div>
    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido a<br>E.O.R.M. Brisas del Campo</h2>
            <form action="../../backend/autenticacion.php" method="POST">
                <input type="text" name="nombreUsuario" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
            </form>

            <!-- Mostrar mensaje de error si existe -->
            <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'bloqueo') {
                    echo "<p style='color:red;'>Su cuenta está bloqueada temporalmente. Intente de nuevo más tarde.</p>";
                } elseif ($_GET['error'] == 'sesion_expirada') {
                    echo "<p style='color:red;'>Su sesión ha expirado por inactividad o duración máxima.</p>";
                } else {
                    echo "<p style='color:red;'>Credenciales incorrectas. Inténtelo de nuevo.</p>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
