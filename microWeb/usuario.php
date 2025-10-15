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
    <title>Usuario</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="usuario.php">Almacén ABC</a>
            <span class="navbar-text">
                <a class="nav-link" href="logout.php">Logout <?php echo htmlspecialchars($us); ?></a>
            </span>
        </div>
    </nav>

    <form method="post" action="procesar.php">
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Cantidad a llevar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servurl = "http://almacen_microproductos:3002/productos";
                $curl = curl_init($servurl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);

                if ($response === false) {
                    curl_close($curl);
                    die("Error en la conexión");
                }

                curl_close($curl);
                $resp = json_decode($response);
                if (!is_array($resp)) {
                    die("⚠️ No se pudieron obtener los productos (respuesta inválida del servicio).");
                }

                foreach ($resp as $dec) {
                    $id = $dec->id;
                    $nombre = $dec->nombre;
                    $precio = $dec->precio;
                    $cantidad = $dec->cantidad;
                    echo "
                    <tr>
                        <td>$nombre</td>
                        <td>$precio</td>
                        <td>$cantidad</td>
                        <td><input type='number' name='cantidad[$id]' value='0' min='0'></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>

        <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($us); ?>">
        <input type="submit" value="Agregar a la orden" class="btn btn-primary">
    </form>
</body>
</html>

