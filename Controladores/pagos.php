<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { http_response_code(204); exit; }


header("Content-Type: application/json; charset=UTF-8");

// Conexión a la base de datos
require_once __DIR__ . "/../modelos/conexion.php";

// Modelo de pagos
require_once __DIR__ . "/../modelos/pagos.php";

try {
    // Crear objeto
    $pagos = new Pagos($conexion);

    // Recibir la operación solicitada
    $control = $_GET["control"] ?? "";

    // Recibir datos enviados en formato JSON
    $datos = json_decode(
        file_get_contents("php://input"),
        true
    ) ?? [];

    switch ($control) {

        case "consulta":
            $respuesta = $pagos->consulta();
            break;

        case "insertar":
            $respuesta = $pagos->insertar(
                $datos["id_factura"],
                $datos["id_plataforma"] ?? null,
                $datos["fecha_pago"],
                $datos["monto"],
                $datos["metodo"],
                $datos["estado"]
            );
            break;

        case "editar":
            $respuesta = $pagos->editar(
                $datos["id_pago"],
                $datos["id_factura"],
                $datos["id_plataforma"] ?? null,
                $datos["fecha_pago"],
                $datos["monto"],
                $datos["metodo"],
                $datos["estado"]
            );
            break;

        case "anular":
            $respuesta = $pagos->anular(
                $datos["id_pago"]
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