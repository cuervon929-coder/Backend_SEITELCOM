
<?php

class Notificaciones
{
    // Atributo
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar todas las notificaciones
    public function consulta()
    {
        $sql = "SELECT
                    n.id_notificacion,
                    n.id_cliente,
                    c.nombre AS cliente,
                    n.id_pago,
                    n.mensaje,
                    n.fecha_envio,
                    n.tipo,
                    n.estado
                FROM notificacion AS n
                INNER JOIN cliente AS c
                    ON n.id_cliente = c.id_cliente
                ORDER BY n.fecha_envio DESC,
                         n.id_notificacion DESC";

        $resultado = mysqli_query(
            $this->conexion,
            $sql
        );

        if (!$resultado) {
            throw new Exception(
                "Error al consultar notificaciones"
            );
        }

        return mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );
    }

    // Consultar notificaciones de un cliente
    public function consultarPorCliente($id_cliente)
    {
        $sql = "SELECT
                    id_notificacion,
                    id_cliente,
                    id_pago,
                    mensaje,
                    fecha_envio,
                    tipo,
                    estado
                FROM notificacion
                WHERE id_cliente = ?
                ORDER BY fecha_envio DESC,
                         id_notificacion DESC";

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
            $id_cliente
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al consultar el cliente"
            );
        }

        $resultado = mysqli_stmt_get_result($stmt);

        $vector = mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );

        mysqli_stmt_close($stmt);

        return $vector;
    }

    // Registrar una notificación
    public function insertar(
        $id_cliente,
        $id_pago,
        $mensaje,
        $tipo
    ) {
        if (trim($mensaje) === "" || trim($tipo) === "") {
            throw new InvalidArgumentException(
                "El mensaje y el tipo son obligatorios"
            );
        }

        $sql = "INSERT INTO notificacion
                (
                    id_cliente,
                    id_pago,
                    mensaje,
                    fecha_envio,
                    tipo,
                    estado
                )
                VALUES (?, ?, ?, NOW(), ?, 'pendiente')";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                "Error al preparar la notificación"
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            "iiss",
            $id_cliente,
            $id_pago,
            $mensaje,
            $tipo
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al registrar la notificación"
            );
        }

        $id_notificacion = mysqli_insert_id(
            $this->conexion
        );

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Notificación registrada correctamente",
            "id_notificacion" => $id_notificacion
        ];
    }

    // Cambiar el estado de una notificación
    public function cambiarEstado(
        $id_notificacion,
        $estado
    ) {
        $sql = "UPDATE notificacion
                SET estado = ?
                WHERE id_notificacion = ?";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                "Error al preparar el cambio de estado"
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $estado,
            $id_notificacion
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al actualizar la notificación"
            );
        }

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Estado actualizado correctamente"
        ];
    }
}
?> 