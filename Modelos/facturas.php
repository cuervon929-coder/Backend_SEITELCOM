
<?php

class Facturas
{
    // Atributos
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar facturas con nombre del cliente
    public function consulta()
    {
        $sql = "SELECT
                    f.*,
                    c.nombre AS cliente
                FROM factura AS f
                INNER JOIN cliente AS c
                    ON f.id_cliente = c.id_cliente
                ORDER BY
                    f.fecha_emision DESC,
                    f.id_factura DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception("Error al consultar facturas");
        }

        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    // Insertar factura
    public function insertar(
        $id_cliente,
        $id_usuario,
        $fecha_emision,
        $fecha_vencimiento,
        $monto_total,
        $estado
    ) {
        $sql = "INSERT INTO factura
                (
                    id_cliente,
                    id_usuario,
                    fecha_emision,
                    fecha_vencimiento,
                    monto_total,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar inserción");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iissds",
            $id_cliente,
            $id_usuario,
            $fecha_emision,
            $fecha_vencimiento,
            $monto_total,
            $estado
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Factura registrada correctamente"
        ];
    }

    // Editar factura
    public function editar(
        $id_factura,
        $id_cliente,
        $id_usuario,
        $fecha_emision,
        $fecha_vencimiento,
        $monto_total,
        $estado
    ) {
        $sql = "UPDATE factura SET
            id_cliente = ?,
            id_usuario = ?,
            fecha_emision = ?,
            fecha_vencimiento = ?,
            monto_total = ?,
            estado = ?
        WHERE id_factura = ?
          AND estado = 'pendiente'";

        if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    throw new Exception("Error al actualizar la factura");
}

$filasAfectadas = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);

if ($filasAfectadas === 0) {
    return [
        "resultado" => "Error",
        "mensaje" => "No se realizaron cambios. Verifique que la factura exista y esté pendiente."
    ];
}
        return [
            "resultado" => "Ok",
            "mensaje" => "Factura actualizada correctamente"
        ];
    }

    // Anular factura sin eliminarla
    
public function anular($id_factura)
{
    $sql = "UPDATE factura
            SET estado = 'anulada'
            WHERE id_factura = ?
              AND estado = 'pendiente'";

    $stmt = mysqli_prepare($this->conexion, $sql);

    if (!$stmt) {
        throw new Exception("Error al preparar anulación");
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id_factura
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        throw new Exception("Error al anular factura");
    }

    $filasAfectadas = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($filasAfectadas === 0) {
        return [
            "resultado" => "Error",
            "mensaje" => "No se puede anular la factura. Verifique que exista y esté pendiente."
        ];
    }

    return [
        "resultado" => "Ok",
        "mensaje" => "Factura anulada correctamente"
    ];
}
} 
