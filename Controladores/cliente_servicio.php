
<?php

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/cliente_servicio.php";

try {

    $clienteServicio = new ClienteServicio($conexion);

    $control = $_GET["control"] ?? "";

    $datos = json_decode(
        file_get_contents("php://input"),
        true
    ) ?? [];

    switch ($control) {

        case "consulta":
            $respuesta = $clienteServicio->consulta();
            break;

        case "insertar":
            $respuesta = $clienteServicio->insertar(
                $datos["id_cliente"],
                $datos["id_servicio"],
                $datos["fecha_inicio"],
                $datos["estado"] ?? "activo"
            );
            break;

        case "editar":
            $respuesta = $clienteServicio->editar(
                $datos["id_cliente_servicio"],
                $datos["id_cliente"],
                $datos["id_servicio"],
                $datos["fecha_inicio"],
                $datos["estado"]
            );
            break;

        case "desactivar":
            $respuesta = $clienteServicio->desactivar(
                $datos["id_cliente_servicio"]
            );
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
        "mensaje" => "No fue posible procesar la solicitud"
    ], JSON_UNESCAPED_UNICODE);
}
?> 