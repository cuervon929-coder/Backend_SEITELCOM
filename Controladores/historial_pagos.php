<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { http_response_code(204); exit; }


header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../Modelos/conexion.php";
require_once __DIR__ . "/../Modelos/historial_pagos.php";

try {

    // Crear el objeto del modelo
    $historial = new HistorialPagos($conexion);

    // Obtener la acción solicitada
    $control = $_GET["control"] ?? "consulta";

    switch ($control) {

        // Consultar todo el historial
        case "consulta":

            $resultado = $historial->consulta();

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Consultar historial de un pago
        case "consultarPorPago":

            $id_pago = filter_input(
                INPUT_GET,
                "id_pago",
                FILTER_VALIDATE_INT
            );

            if (!$id_pago || $id_pago < 1) {
                throw new Exception(
                    "Debe indicar un id_pago válido"
                );
            }

            $resultado = $historial->consultarPorPago(
                $id_pago
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Registrar un movimiento
        case "insertar":

            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                http_response_code(405);

                throw new Exception(
                    "Esta acción requiere POST"
                );
            }

            $datos = json_decode(
                file_get_contents("php://input"),
                true
            );

            if (!is_array($datos)) {
                $datos = $_POST;
            }

            $id_pago = filter_var(
                $datos["id_pago"] ?? null,
                FILTER_VALIDATE_INT
            );

            $descripcion = trim(
                $datos["descripcion"] ?? ""
            );

            if (!$id_pago || $id_pago < 1) {
                throw new Exception(
                    "Debe indicar un id_pago válido"
                );
            }

            if ($descripcion === "") {
                throw new Exception(
                    "La descripción es obligatoria"
                );
            }

            $resultado = $historial->insertar(
                $id_pago,
                $descripcion
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
        http_response_code(400);
    }

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

?>