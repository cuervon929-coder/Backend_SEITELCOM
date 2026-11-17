
<?php

class HistorialPagos
{
    // Atributo
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar historial de pagos
    public function consulta()
    {
        $sql = "SELECT
                    h.id_historial,
                    h.id_pago,
                    h.fecha_registro,
                    h.descripcion,
                    p.id_factura,
                    p.monto,
                    p.metodo,
                    p.estado AS estado_pago,
                    c.nombre AS cliente
                FROM historial_pagos AS h
                INNER JOIN pago AS p
                    ON h.id_pago = p.id_pago
                INNER JOIN factura AS f
                    ON p.id_factura = f.id_factura
                INNER JOIN cliente AS c
                    ON f.id_cliente = c.id_cliente
                ORDER BY h.fecha_registro DESC,
                         h.id_historial DESC";

        $resultado = mysqli_query(
            $this->conexion,
            $sql
        );

        if (!$resultado) {
            throw new Exception(
                "Error al consultar el historial de pagos"
            );
        }

        return mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );
    }

    // Consultar historial de un pago específico
    public function consultarPorPago($id_pago)
    {
        $sql = "SELECT
                    id_historial,
                    id_pago,
                    fecha_registro,
                    descripcion
                FROM historial_pagos
                WHERE id_pago = ?
                ORDER BY fecha_registro DESC,
                         id_historial DESC";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                "Error al preparar la consulta"
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id_pago
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $vector = mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );

        mysqli_stmt_close($stmt);

        return $vector;
    }

    // Registrar un movimiento en el historial
    public function insertar($id_pago, $descripcion)
    {
        $sql = "INSERT INTO historial_pagos
                (id_pago, fecha_registro, descripcion)
                VALUES (?, NOW(), ?)";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                "Error al preparar el registro"
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $id_pago,
            $descripcion
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al registrar el historial"
            );
        }

        $id_historial = mysqli_insert_id(
            $this->conexion
        );

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Historial registrado correctamente",
            "id_historial" => $id_historial
        ];
    }
}
?> 