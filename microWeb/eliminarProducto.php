<?php
$id = $_POST['id'] ?? '';

if ($id === '') {
    header("Location:admin-prod.php");
    exit;
}

$url = "http://almacen_microproductos:3002/productos/$id";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Podés agregar validación de respuesta si querés
header("Location:admin-prod.php");
exit;
?>
