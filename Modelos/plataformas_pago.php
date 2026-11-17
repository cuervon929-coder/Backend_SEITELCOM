
<?php

class PlataformasPago
{
    // Atributo
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar plataformas de pago
    public function consulta()
    {
        $sql = "SELECT
                    id_plataforma,
                    nombre,
                    version
                FROM plataforma_pago
                ORDER BY id_plataforma DESC";

        $resultado = mysqli_query(
            $this->conexion,
            $sql
        );

        if (!$resultado) {
            throw new Exception(
                "Error al consultar plataformas de pago"
            );
        }

        return mysqli_fetch_all(
            $resultado,
            MYSQLI_ASSOC
        );
    }

    // Insertar plataforma de pago
    public function insertar($nombre, $version)
    {
        if (trim($nombre) === "") {
            throw new InvalidArgumentException(
                "El nombre de la plataforma es obligatorio"
            );
        }

        $sql = "INSERT INTO plataforma_pago
                (nombre, version)
                VALUES (?, ?)";

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
            "ss",
            $nombre,
            $version
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al registrar la plataforma"
            );
        }

        $id_plataforma = mysqli_insert_id(
            $this->conexion
        );

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Plataforma registrada correctamente",
            "id_plataforma" => $id_plataforma
        ];
    }

    // Editar plataforma de pago
    public function editar(
        $id_plataforma,
        $nombre,
        $version
    ) {
        if (trim($nombre) === "") {
            throw new InvalidArgumentException(
                "El nombre de la plataforma es obligatorio"
            );
        }

        $sql = "UPDATE plataforma_pago SET
                    nombre = ?,
                    version = ?
                WHERE id_plataforma = ?";

        $stmt = mysqli_prepare(
            $this->conexion,
            $sql
        );

        if (!$stmt) {
            throw new Exception(
                "Error al preparar la edición"
            );
        }

        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $nombre,
            $version,
            $id_plataforma
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(
                "Error al actualizar la plataforma"
            );
        }

        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Plataforma actualizada correctamente"
        ];
    }

    // Consultar una plataforma específica
    public function consultarPorId($id_plataforma)
    {
        $sql = "SELECT
                    id_plataforma,
                    nombre,
                    version
                FROM plataforma_pago
                WHERE id_plataforma = ?";

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
            $id_plataforma
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $datos = mysqli_fetch_assoc($resultado);

        mysqli_stmt_close($stmt);

        return $datos ?: null;
    }
}
?> 