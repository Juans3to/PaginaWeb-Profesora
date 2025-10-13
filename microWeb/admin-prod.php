<?php
session_start();
$us = $_SESSION["usuario"] ?? '';
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
    <title>Panel de Productos</title>
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
                    <li class="nav-item"><a class="nav-link" href="admin.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin-prod.php">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin-ord.php">Ordenes</a></li>
                </ul>
                <span class="navbar-text">
                    <a class="nav-link" href="logout.php">Logout <?php echo htmlspecialchars($us); ?></a>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Gestión de Productos</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Eliminar prod</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servurl = "http://localhost:3002/productos";
                $curl = curl_init($servurl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);

                if ($response === false) {
                    curl_close($curl);
                    die("Error en la conexión");
                }

                curl_close($curl);
                $resp = json_decode($response);
                $long = count($resp);

                for ($i = 0; $i < $long; $i++) {
                    $dec = $resp[$i];
                    $id = $dec->id;
                    $nombre = $dec->nombre;
                    $precio = $dec->precio;
                    $cantidad = $dec->cantidad;

                    // Opcional: ocultar productos sin cantidad definida
                    // if ($cantidad === null || $cantidad === '') continue;

                    echo "<tr>
                            <td>$id</td>
                            <td>$nombre</td>
                            <td>$precio</td>
                            <td>$cantidad</td>
                            <td>
                                <form action='eliminarProducto.php' method='post' onsubmit=\"return confirm('¿Estás seguro de que deseas eliminar este producto?');\">
                                    <input type='hidden' name='id' value='$id'>
                                    <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                                </form>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            CREAR PRODUCTO
        </button>

        <div class="modal" id="exampleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="crearProducto.php" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title">CREAR PRODUCTO</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Precio</label>
                                <input type="text" name="precio" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cantidad</label>
                                <input type="text" name="cantidad" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear Producto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
