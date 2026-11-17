
<?php

// Cabeceras para comunicar PHP con Angular
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Responder a la solicitud previa del navegador
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Llamar la conexión y el modelo
require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/clientes.php";

try {

    // Capturar la variable control desde la URL
    $control = $_GET["control"] ?? "";

    // Crear una instancia del modelo
    $clientes = new Clientes($conexion);

    // Ejecutar la operación solicitada
    switch ($control) {

        case "consulta":
            $vector = $clientes->consulta();
            break;

        case "insertar":

            $json = file_get_contents("php://input");
            $params = json_decode($json, true);

            if (!is_array($params)) {
                throw new InvalidArgumentException(
                    "Los datos enviados no son válidos"
                );
            }

            $vector = $clientes->insertar(
                $params["nombre"] ?? "",
                $params["tipo_documento"] ?? "",
                $params["numero_documento"] ?? "",
                $params["correo"] ?? null,
                $params["telefono"] ?? null,
                $params["direccion"] ?? null
            );
            break;

        case "editar":

            $id_cliente = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_cliente || $id_cliente < 1) {
                throw new InvalidArgumentException(
                    "El ID del cliente no es válido"
                );
            }

            $json = file_get_contents("php://input");
            $params = json_decode($json, true);

            if (!is_array($params)) {
                throw new InvalidArgumentException(
                    "Los datos enviados no son válidos"
                );
            }

            $vector = $clientes->editar(
                $id_cliente,
                $params["nombre"] ?? "",
                $params["tipo_documento"] ?? "",
                $params["numero_documento"] ?? "",
                $params["correo"] ?? null,
                $params["telefono"] ?? null,
                $params["direccion"] ?? null
            );
            break;

        case "eliminar":

            $id_cliente = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_cliente || $id_cliente < 1) {
                throw new InvalidArgumentException(
                    "El ID del cliente no es válido"
                );
            }

            $vector = $clientes->eliminar($id_cliente);
            break;

        default:
            http_response_code(400);

            $vector = [
                "resultado" => "Error",
                "mensaje" => "Operación no válida"
            ];
            break;
    }

    // Convertir la respuesta a JSON
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

    // Registrar el detalle internamente
    error_log($e->getMessage());

    http_response_code(500);

    echo json_encode([
        "resultado" => "Error",
        "mensaje" => "No se pudo completar la operación"
    ], JSON_UNESCAPED_UNICODE);
}
?> 