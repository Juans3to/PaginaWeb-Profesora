<?php
session_start();
$us = $_SESSION["usuario"] ?? '';

// Validar sesión activa
if ($us === '') {
    header("Location: index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Panel Admin</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Almacen ABC</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link active" href="admin.php">Usuarios</a></li>
            <li class="nav-item"><a class="nav-link" href="admin-prod.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="admin-ord.php">Ordenes</a></li>
        </ul>
        <span class="navbar-text">
            <a class="nav-link" href="logout.php">Logout <?php echo htmlspecialchars($us); ?></a>
        </span>
        </div>
    </div>
    </nav>

    <div class="container mt-4">
        <h3>Bienvenido, <?php echo htmlspecialchars($us); ?> 👋</h3>

        <table class="table mt-3">
        <thead>
            <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Email</th>
            <th scope="col">Usuario</th>
            <th scope="col">Password</th>
            </tr>
        </thead>
        <tbody>
        <?php
            $servurl = "http://almacen_microusuarios:3001/usuarios";
            $curl = curl_init($servurl);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);

            if ($response === false) {
                curl_close($curl);
                die("Error en la conexión con el microservicio");
            }

            curl_close($curl);
            $resp = json_decode($response);
            if (is_array($resp)) {
                foreach ($resp as $dec) {
                    $nombre = $dec->nombre ?? '';
                    $email = $dec->email ?? '';
                    $usuario = $dec->usuario ?? '';
                    $password = $dec->password ?? '';
                    echo "<tr>
                            <td>$nombre</td>
                            <td>$email</td>
                            <td>$usuario</td>
                            <td>$password</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No se pudo cargar la lista de usuarios.</td></tr>";
            }
        ?>
        </tbody>
        </table>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            CREAR USUARIO
        </button>

        <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CREAR USUARIO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="crearUsuario.php" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Usuario</label>
                    <input type="text" name="usuario" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</body>
</html>

