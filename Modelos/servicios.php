
<?php

class Servicios
{
    // Atributo
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar servicios
    public function consulta()
    {
        $sql = "SELECT * FROM servicio
                ORDER BY id_servicio DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception("Error al consultar servicios");
        }

        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    // Insertar servicio
    public function insertar(
        $tipo,
        $plan,
        $precio,
        $estado,
        $costo_mensual
    ) {
        $sql = "INSERT INTO servicio
                (tipo, plan, precio, estado, costo_mensual)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssdsd",
            $tipo,
            $plan,
            $precio,
            $estado,
            $costo_mensual
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio registrado correctamente"
        ];
    }

    // Editar servicio
    public function editar(
        $id_servicio,
        $tipo,
        $plan,
        $precio,
        $estado,
        $costo_mensual
    ) {
        $sql = "UPDATE servicio SET
                    tipo = ?,
                    plan = ?,
                    precio = ?,
                    estado = ?,
                    costo_mensual = ?
                WHERE id_servicio = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssdsdi",
            $tipo,
            $plan,
            $precio,
            $estado,
            $costo_mensual,
            $id_servicio
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio actualizado correctamente"
        ];
    }

    // Desactivar servicio sin eliminarlo
    public function desactivar($id_servicio)
    {
        $sql = "UPDATE servicio
                SET estado = 'inactivo'
                WHERE id_servicio = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id_servicio
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Servicio desactivado correctamente"
        ];
    }
}
?> 