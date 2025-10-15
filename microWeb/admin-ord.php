<?php
session_start();

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == "") {
    header("Location: index.html");
    exit(); // <-- importante, para detener la ejecución
}

$us = $_SESSION["usuario"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">Almacen ABC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin-prod.php">Productos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="admin-ord.php">Ordenes</a></li>
                </ul>
                <span class="navbar-text">
                    <a class='nav-link' href='logout.php'>Logout <?= htmlspecialchars($us) ?></a>
                </span>
            </div>
        </div>
    </nav>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Cliente</th>
                <th>Email Cliente</th>
                <th>Total Cuenta</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servurl = "http://almacen_microordenes:3003/ordenes";
            $curl = curl_init($servurl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);

            if ($response === false) {
                curl_close($curl);
                die("Error en la conexión con el servicio.");
            }

            curl_close($curl);
            $resp = json_decode($response);

            if (is_array($resp) || is_object($resp)) {
                foreach ($resp as $dec) {
                    $id = $dec->id;
                    $nombreCliente = $dec->nombreCliente;
                    $emailCliente = $dec->emailCliente;
                    $totalCuenta = $dec->totalCuenta;
                    $fecha = $dec->fecha;
                    echo "
                        <tr>
                            <td>$id</td>
                            <td>$nombreCliente</td>
                            <td>$emailCliente</td>
                            <td>$totalCuenta</td>
                            <td>$fecha</td>
                        </tr>
                    ";
                }
            } else {
                echo "<tr><td colspan='5'>No se encontraron órdenes.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

