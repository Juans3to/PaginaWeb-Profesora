<?php
session_start();

// Validar sesión
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
            <thead class="table-light">
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
                // ��� URL del microservicio de productos (en red Docker Swarm)
                $servurl = "http://almacen_microproductos:3002/productos";
                $curl = curl_init($servurl);

                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT => 5,  // 5 segundos de espera máxima
                    CURLOPT_TIMEOUT => 10,        // 10 segundos total
                ]);

                $response = curl_exec($curl);
                $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if ($response === false) {
                    echo "<tr><td colspan='5' class='text-danger'>❌ Error en la conexión con el microservicio de productos.</td></tr>";
                } elseif ($httpCode !== 200) {
                    echo "<tr><td colspan='5' class='text-danger'>⚠️ El servicio respondió con código HTTP $httpCode.</td></tr>";
                } else {
                    $resp = json_decode($response);

                    if (is_array($resp) && count($resp) > 0) {
                        foreach ($resp as $dec) {
                            $id = $dec->id ?? '';
                            $nombre = $dec->nombre ?? '';
                            $precio = $dec->precio ?? '';
                            $cantidad = $dec->cantidad ?? '';

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
                    } else {
                        echo "<tr><td colspan='5'>No se encontraron productos o el servicio devolvió datos vacíos.</td></tr>";
                    }
                }

                curl_close($curl);
                ?>
            </tbody>
        </table>

        <!-- Botón para crear producto -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            CREAR PRODUCTO
        </button>

        <!-- Modal de creación -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
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
                                <input type="number" name="precio" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" name="cantidad" class="form-control" required>
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

