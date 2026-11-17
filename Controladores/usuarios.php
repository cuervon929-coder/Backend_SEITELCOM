<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { http_response_code(204); exit; }


header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../Modelos/conexion.php";
require_once __DIR__ . "/../Modelos/usuarios.php";

try {

    // Crear objeto del modelo
    $usuarios = new Usuarios($conexion);

    // Obtener la operación solicitada
    $control = $_GET["control"] ?? "";

    switch ($control) {

        case "consulta":

            // Consultar usuarios
            $respuesta = $usuarios->consulta();

            // Evitar mostrar contraseñas o hashes
            foreach ($respuesta as &$usuario) {
                unset($usuario["contrasena_hash"]);
            }
            unset($usuario);

            break;

        default:

            http_response_code(400);

            $respuesta = [
                "resultado" => "Error",
                "mensaje" => "Operación no válida"
            ];

            break;
    }

    echo json_encode(
        $respuesta,
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $error) {

    http_response_code(500);

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => "No fue posible consultar los usuarios"
    ], JSON_UNESCAPED_UNICODE);
}
?> 