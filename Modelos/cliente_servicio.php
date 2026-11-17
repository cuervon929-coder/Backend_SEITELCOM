
<?php

class ClienteServicio
{
    // Atributos
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar servicios contratados
    public function consulta()
    {
        $sql = "SELECT
                    cs.id_cliente_servicio,
                    cs.id_cliente,
                    cs.id_servicio,
                    c.nombre AS cliente,
                    s.tipo,
                    s.plan,
                    s.costo_mensual,
                    cs.fecha_inicio,
                    cs.estado
                FROM cliente_servicio AS cs
                INNER JOIN cliente AS c
                    ON cs.id_cliente = c.id_cliente
                INNER JOIN servicio AS s
                    ON cs.id_servicio = s.id_servicio
                ORDER BY cs.id_cliente_servicio DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception(
                "Error al consultar servicios contratados"
            );
        }

        return mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );
    }

    // Insertar servicio contratado
    public function insertar(
        $id_cliente,
        $id_servicio,
        $fecha_inicio,
        $estado
    ) {
        $sql = "INSERT INTO cliente_servicio
                (id_cliente, id_servicio, fecha_inicio, estado)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iiss",
            $id_cliente,
            $id_servicio,
            $fecha_inicio,
            $estado
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio asignado correctamente"
        ];
    }

    // Editar servicio contratado
    public function editar(
        $id_cliente_servicio,
        $id_cliente,
        $id_servicio,
        $fecha_inicio,
        $estado
    ) {
        $sql = "UPDATE cliente_servicio SET
                    id_cliente = ?,
                    id_servicio = ?,
                    fecha_inicio = ?,
                    estado = ?
                WHERE id_cliente_servicio = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iissi",
            $id_cliente,
            $id_servicio,
            $fecha_inicio,
            $estado,
            $id_cliente_servicio
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio contratado actualizado"
        ];
    }

    // Desactivar servicio contratado
    public function desactivar($id_cliente_servicio)
    {
        $sql = "UPDATE cliente_servicio
                SET estado = 'inactivo'
                WHERE id_cliente_servicio = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id_cliente_servicio
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio contratado desactivado"
        ];
    }
}
?> 