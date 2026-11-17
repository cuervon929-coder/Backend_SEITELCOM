
<?php

header("Content-Type: application/json; charset=UTF-8");

// Conexión a la base de datos
require_once __DIR__ . "/../Modelos/conexion.php";

// Modelo de plataformas de pago
require_once __DIR__ . "/../Modelos/plataformas_pago.php";

try {

    // Crear objeto del modelo
    $plataformas = new PlataformasPago($conexion);

    // Obtener la acción
    $control = $_GET["control"] ?? "consulta";

    switch ($control) {

        // Consultar todas las plataformas
        case "consulta":

            $resultado = $plataformas->consulta();

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Consultar plataforma por ID
        case "consultarPorId":

            $id_plataforma = filter_input(
                INPUT_GET,
                "id_plataforma",
                FILTER_VALIDATE_INT
            );

            if (!$id_plataforma || $id_plataforma < 1) {
                throw new InvalidArgumentException(
                    "Debe indicar un ID válido"
                );
            }

            $resultado = $plataformas->consultarPorId(
                $id_plataforma
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Insertar plataforma
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

            $nombre = trim($datos["nombre"] ?? "");
            $version = trim($datos["version"] ?? "");

            if ($nombre === "") {
                throw new InvalidArgumentException(
                    "El nombre es obligatorio"
                );
            }

            $resultado = $plataformas->insertar(
                $nombre,
                $version
            );

            echo json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE
            );

            break;

        // Editar plataforma
        case "editar":

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

            $id_plataforma = filter_var(
                $datos["id_plataforma"] ?? null,
                FILTER_VALIDATE_INT
            );

            $nombre = trim($datos["nombre"] ?? "");
            $version = trim($datos["version"] ?? "");

            if (!$id_plataforma || $id_plataforma < 1) {
                throw new InvalidArgumentException(
                    "Debe indicar un ID válido"
                );
            }

            if ($nombre === "") {
                throw new InvalidArgumentException(
                    "El nombre es obligatorio"
                );
            }

            $resultado = $plataformas->editar(
                $id_plataforma,
                $nombre,
                $version
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