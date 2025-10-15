<?php
// Recibir datos del formulario
$user = $_POST["usuario"] ?? '';
$pass = $_POST["password"] ?? '';

// Validar que se recibieron datos
if (empty($user) || empty($pass)) {
    error_log("Faltan datos: usuario o contraseña vacíos");
    header("Location:index.html");
    exit;
}

// Preparar la solicitud al microservicio
$servurl = "http://almacen_microusuarios:3001/usuarios/$user/$pass";
$curl = curl_init($servurl);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// Verificar que se recibió una respuesta válida
if ($response === false || empty($response)) {
    error_log("Error al obtener respuesta del microservicio");
    header("Location:index.html");
    exit;
}

// Decodificar la respuesta como array asociativo
$resp = json_decode($response, true);

// Validar que el JSON se haya decodificado correctamente
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Error al decodificar JSON: " . json_last_error_msg());
    header("Location:index.html");
    exit;
}

// Validar que la respuesta contenga datos
if (is_array($resp) && count($resp) > 0) {
    session_start();
    $_SESSION["usuario"] = $user;

    if ($user === "admin") {
        header("Location:admin.php");
        exit;
    } else {
        header("Location:usuario.php");
        exit;
    }
} else {
    error_log("Usuario no válido o respuesta vacía");
    header("Location:index.html");
    exit;
}
?>
