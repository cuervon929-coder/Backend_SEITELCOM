<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { http_response_code(204); exit; }


header("Content-Type: application/json; charset=UTF-8");

// Conexión a la base de datos
require_once __DIR__ . "/../Modelos/conexion.php";

// Modelo de notificaciones
require_once __DIR__ . "/../Modelos/notificaciones.php";

try {

    // Crear objeto del modelo
    $notificaciones = new Notificaciones($conexion);

    // Obtener la acción solicitada
    $control = $_GET["control"] ?? "consulta";

    // Leer datos enviados mediante POST
    $datos = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (!is_array($datos)) {
        $datos = $_POST;
    }

    switch ($control) {

        // Consultar todas las notificaciones
        case "consulta":

            $resultado = $notificaciones->consulta();

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Consultar notificaciones de un cliente
        case "consultarPorCliente":

            $id_cliente = filter_input(
                INPUT_GET,
                "id_cliente",
                FILTER_VALIDATE_INT
            );

            if (!$id_cliente || $id_cliente < 1) {
                throw new InvalidArgumentException(
                    "Debe indicar un ID de cliente válido"
                );
            }

            $resultado = $notificaciones->consultarPorCliente(
                $id_cliente
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Registrar una notificación
        case "insertar":

            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                http_response_code(405);
                throw new Exception(
                    "Esta acción requiere POST"
                );
            }

            $id_cliente = filter_var(
                $datos["id_cliente"] ?? null,
                FILTER_VALIDATE_INT
            );

            $id_pago = $datos["id_pago"] ?? null;

            if ($id_pago === "") {
                $id_pago = null;
            }

            if ($id_pago !== null) {
                $id_pago = filter_var(
                    $id_pago,
                    FILTER_VALIDATE_INT
                );

                if (!$id_pago || $id_pago < 1) {
                    throw new InvalidArgumentException(
                        "El ID de pago no es válido"
                    );
                }
            }

            $mensaje = trim($datos["mensaje"] ?? "");
            $tipo = trim($datos["tipo"] ?? "");

            if (!$id_cliente || $id_cliente < 1) {
                throw new InvalidArgumentException(
                    "Debe indicar un ID de cliente válido"
                );
            }

            if ($mensaje === "" || $tipo === "") {
                throw new InvalidArgumentException(
                    "El mensaje y el tipo son obligatorios"
                );
            }

            $resultado = $notificaciones->insertar(
                $id_cliente,
                $id_pago,
                $mensaje,
                $tipo
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Cambiar el estado de una notificación
        case "cambiarEstado":

            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                http_response_code(405);
                throw new Exception(
                    "Esta acción requiere POST"
                );
            }

            $id_notificacion = filter_var(
                $datos["id_notificacion"] ?? null,
                FILTER_VALIDATE_INT
            );

            $estado = trim($datos["estado"] ?? "");

            if (!$id_notificacion || $id_notificacion < 1) {
                throw new InvalidArgumentException(
                    "Debe indicar un ID de notificación válido"
                );
            }

            if (!in_array(
                $estado,
                ["pendiente", "leida"],
                true
            )) {
                throw new InvalidArgumentException(
                    "El estado debe ser pendiente o leida"
                );
            }

            $resultado = $notificaciones->cambiarEstado(
                $id_notificacion,
                $estado
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        default:

            http_response_code(400);

            echo json_encode([
                "resultado" => "Error",
                "mensaje" => "Acción no válida"
            ]);

            break;
    }

} catch (Throwable $e) {

    if (http_response_code() === 200) {
        http_response_code(
            $e instanceof InvalidArgumentException ? 400 : 500
        );
    }

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

?>