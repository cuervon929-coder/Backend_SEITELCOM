
<?php

class Clientes
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar clientes
    public function consulta()
    {
        $sql = "SELECT * FROM cliente
                ORDER BY id_cliente DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception("Error al consultar clientes");
        }

        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    // Insertar cliente
    public function insertar(
        $nombre,
        $tipo_documento,
        $numero_documento,
        $correo,
        $telefono,
        $direccion
    ) {
        $sql = "INSERT INTO cliente
                (nombre, tipo_documento, numero_documento,
                 correo, telefono, direccion)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $nombre,
            $tipo_documento,
            $numero_documento,
            $correo,
            $telefono,
            $direccion
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Cliente insertado correctamente"
        ];
    }

    // Editar cliente
    public function editar(
        $id_cliente,
        $nombre,
        $tipo_documento,
        $numero_documento,
        $correo,
        $telefono,
        $direccion
    ) {
        $sql = "UPDATE cliente SET
                    nombre = ?,
                    tipo_documento = ?,
                    numero_documento = ?,
                    correo = ?,
                    telefono = ?,
                    direccion = ?
                WHERE id_cliente = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $nombre,
            $tipo_documento,
            $numero_documento,
            $correo,
            $telefono,
            $direccion,
            $id_cliente
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Cliente editado correctamente"
        ];
    }

    // Eliminar cliente
    public function eliminar($id_cliente)
    {
        $sql = "DELETE FROM cliente
                WHERE id_cliente = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $id_cliente
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Cliente eliminado correctamente"
        ];
    }
}
?>