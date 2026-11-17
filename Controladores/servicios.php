
<?php

// Cabeceras para comunicar PHP con Angular
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Responder a solicitudes previas del navegador
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Incluir conexión y modelo
require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/servicios.php";

try {

    // Capturar la operación desde la URL
    $control = $_GET["control"] ?? "";

    // Crear instancia del modelo
    $servicios = new Servicios($conexion);

    // Ejecutar la operación solicitada
    switch ($control) {

        // Consultar servicios
        case "consulta":

            if ($_SERVER["REQUEST_METHOD"] !== "GET") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud GET"
                );
            }

            $vector = $servicios->consulta();
            break;

        // Insertar servicio
        case "insertar":

            if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud POST"
                );
            }

            $json = file_get_contents("php://input");
            $params = json_decode($json, true);

            if (!is_array($params)) {
                throw new InvalidArgumentException(
                    "Los datos JSON no son válidos"
                );
            }

            $campos = [
                "tipo",
                "plan",
                "precio",
                "estado",
                "costo_mensual"
            ];

            foreach ($campos as $campo) {
                if (!isset($params[$campo]) ||
                    $params[$campo] === "") {
                    throw new InvalidArgumentException(
                        "Falta el campo: " . $campo
                    );
                }
            }

            $vector = $servicios->insertar(
                $params["tipo"],
                $params["plan"],
                $params["precio"],
                $params["estado"],
                $params["costo_mensual"]
            );
            break;

        // Editar servicio
        case "editar":

            if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud PUT"
                );
            }

            $id_servicio = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_servicio || $id_servicio < 1) {
                throw new InvalidArgumentException(
                    "El ID del servicio no es válido"
                );
            }

            $json = file_get_contents("php://input");
            $params = json_decode($json, true);

            if (!is_array($params)) {
                throw new InvalidArgumentException(
                    "Los datos JSON no son válidos"
                );
            }

            $campos = [
                "tipo",
                "plan",
                "precio",
                "estado",
                "costo_mensual"
            ];

            foreach ($campos as $campo) {
                if (!isset($params[$campo]) ||
                    $params[$campo] === "") {
                    throw new InvalidArgumentException(
                        "Falta el campo: " . $campo
                    );
                }
            }

            $vector = $servicios->editar(
                $id_servicio,
                $params["tipo"],
                $params["plan"],
                $params["precio"],
                $params["estado"],
                $params["costo_mensual"]
            );
            break;

        // Desactivar servicio sin eliminarlo
        case "desactivar":

            if ($_SERVER["REQUEST_METHOD"] !== "PATCH") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud PATCH"
                );
            }

            $id_servicio = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_servicio || $id_servicio < 1) {
                throw new InvalidArgumentException(
                    "El ID del servicio no es válido"
                );
            }

            $vector = $servicios->desactivar(
                $id_servicio
            );
            break;

        default:

            http_response_code(400);

            $vector = [
                "resultado" => "Error",
                "mensaje" => "Operación no válida"
            ];
            break;
    }

    // Devolver resultados en formato JSON
    echo json_encode(
        $vector,
        JSON_UNESCAPED_UNICODE
    );

} catch (InvalidArgumentException $e) {

    http_response_code(400);

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => "No se pudo completar la operación"
    ], JSON_UNESCAPED_UNICODE);
}
?> 