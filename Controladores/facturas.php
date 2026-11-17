<?php

// Cabeceras para la comunicación con Angular
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Responder a las solicitudes previas del navegador
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Incluir conexión y modelo
require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/facturas.php";

try {

    // Capturar la operación enviada por la URL
    $control = $_GET["control"] ?? "";

    // Crear una instancia del modelo
    $facturas = new Facturas($conexion);

    // Ejecutar la operación solicitada
    switch ($control) {

        // Consultar facturas
        case "consulta":

            if ($_SERVER["REQUEST_METHOD"] !== "GET") {
                throw new InvalidArgumentException(
                    "Método HTTP no permitido"
                );
            }

            $vector = $facturas->consulta();
            break;

        // Insertar factura
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
                "id_cliente",
                "id_usuario",
                "fecha_emision",
                "fecha_vencimiento",
                "monto_total",
                "estado"
            ];

            foreach ($campos as $campo) {
                if (!isset($params[$campo]) ||
                    $params[$campo] === "") {
                    throw new InvalidArgumentException(
                        "Falta el campo: " . $campo
                    );
                }
            }

            $vector = $facturas->insertar(
                $params["id_cliente"],
                $params["id_usuario"],
                $params["fecha_emision"],
                $params["fecha_vencimiento"],
                $params["monto_total"],
                $params["estado"]
            );
            break;

        // Editar factura
        case "editar":

            if ($_SERVER["REQUEST_METHOD"] !== "PUT") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud PUT"
                );
            }

            $id_factura = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_factura || $id_factura < 1) {
                throw new InvalidArgumentException(
                    "El ID de la factura no es válido"
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
                "id_cliente",
                "id_usuario",
                "fecha_emision",
                "fecha_vencimiento",
                "monto_total",
                "estado"
            ];

            foreach ($campos as $campo) {
                if (!isset($params[$campo]) ||
                    $params[$campo] === "") {
                    throw new InvalidArgumentException(
                        "Falta el campo: " . $campo
                    );
                }
            }


// Validar que los identificadores sean correctos
$id_cliente = filter_var(
    $params["id_cliente"],
    FILTER_VALIDATE_INT
);

$id_usuario = filter_var(
    $params["id_usuario"],
    FILTER_VALIDATE_INT
);

if (!$id_cliente || $id_cliente < 1 ||
    !$id_usuario || $id_usuario < 1) {
    throw new InvalidArgumentException(
        "El cliente o el usuario no es válido"
    );
}

// Validar el valor de la factura
$monto = filter_var(
    $params["monto_total"],
    FILTER_VALIDATE_FLOAT
);

if ($monto === false || !is_finite($monto) || $monto <= 0) {
    throw new InvalidArgumentException(
        "El valor de la factura debe ser mayor que cero"
    );
}

// Validar fechas
$emision = DateTimeImmutable::createFromFormat(
    "!Y-m-d",
    $params["fecha_emision"]
);

$vencimiento = DateTimeImmutable::createFromFormat(
    "!Y-m-d",
    $params["fecha_vencimiento"]
);

if (
    !$emision ||
    !$vencimiento ||
    $emision->format("Y-m-d") !== $params["fecha_emision"] ||
    $vencimiento->format("Y-m-d") !== $params["fecha_vencimiento"]
) {
    throw new InvalidArgumentException(
        "Las fechas de la factura no son válidas"
    );
}

if ($vencimiento < $emision) {
    throw new InvalidArgumentException(
        "La fecha de vencimiento no puede ser anterior a la emisión"
    );
}

// La edición administrativa no puede registrar pagos
if ($params["estado"] !== "pendiente") {
    throw new InvalidArgumentException(
        "Solo se permite guardar facturas en estado pendiente"
    );
}

            $vector = $facturas->editar(
                $id_factura,
                $params["id_cliente"],
                $params["id_usuario"],
                $params["fecha_emision"],
                $params["fecha_vencimiento"],
                $params["monto_total"],
                $params["estado"]
            );
            break;

        // Anular factura sin eliminarla
        case "anular":

            if ($_SERVER["REQUEST_METHOD"] !== "PATCH") {
                throw new InvalidArgumentException(
                    "Se requiere una solicitud PATCH"
                );
            }

            $id_factura = filter_input(
                INPUT_GET,
                "id",
                FILTER_VALIDATE_INT
            );

            if (!$id_factura || $id_factura < 1) {
                throw new InvalidArgumentException(
                    "El ID de la factura no es válido"
                );
            }

            $vector = $facturas->anular($id_factura);
            break;

        default:

            http_response_code(400);

            $vector = [
                "resultado" => "Error",
                "mensaje" => "Operación no válida"
            ];
            break;
    }

    // Devolver los resultados en formato JSON
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