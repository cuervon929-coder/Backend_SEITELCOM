
<?php

class Pagos
{
    // Atributos
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar pagos con factura y cliente
    public function consulta()
    {
        $sql = "SELECT
                    p.id_pago,
                    p.id_factura,
                    p.id_plataforma,
                    c.nombre AS cliente,
                    p.fecha_pago,
                    p.monto,
                    p.metodo,
                    p.estado
                FROM pago AS p
                INNER JOIN factura AS f
                    ON p.id_factura = f.id_factura
                INNER JOIN cliente AS c
                    ON f.id_cliente = c.id_cliente
                ORDER BY p.id_pago DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception("Error al consultar pagos");
        }

        return mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );
    }

    // Insertar un pago
    public function insertar(
        $id_factura,
        $id_plataforma,
        $fecha_pago,
        $monto,
        $metodo,
        $estado
    ) {
        $sql = "INSERT INTO pago
                (
                    id_factura,
                    id_plataforma,
                    fecha_pago,
                    monto,
                    metodo,
                    estado
                )
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar el pago");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iisdss",
            $id_factura,
            $id_plataforma,
            $fecha_pago,
            $monto,
            $metodo,
            $estado
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al registrar el pago");
        }

        $id_pago = mysqli_insert_id($this->conexion);

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Pago registrado correctamente",
            "id_pago" => $id_pago
        ];
    }

    // Editar información del pago
    public function editar(
        $id_pago,
        $id_factura,
        $id_plataforma,
        $fecha_pago,
        $monto,
        $metodo,
        $estado
    ) {
        $sql = "UPDATE pago SET
                    id_factura = ?,
                    id_plataforma = ?,
                    fecha_pago = ?,
                    monto = ?,
                    metodo = ?,
                    estado = ?
                WHERE id_pago = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la edición");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iisdssi",
            $id_factura,
            $id_plataforma,
            $fecha_pago,
            $monto,
            $metodo,
            $estado,
            $id_pago
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al actualizar el pago");
        }

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Pago actualizado correctamente"
        ];
    }

    // Cambiar el estado de un pago
    public function cambiarEstado($id_pago, $estado)
    {
        $sql = "UPDATE pago
                SET estado = ?
                WHERE id_pago = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar el cambio");
        }

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $estado,
            $id_pago
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al cambiar el estado");
        }

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Estado del pago actualizado"
        ];
    }
}
?> 