
<?php

class Usuarios
{
    // Atributo
    private $conexion;

    // Constructor
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Consultar usuarios sin mostrar contraseñas
    public function consulta()
    {
        $sql = "SELECT id_usuario, usuario
                FROM usuario
                ORDER BY id_usuario DESC";

        $resultado = mysqli_query($this->conexion, $sql);

        if (!$resultado) {
            throw new Exception("Error al consultar usuarios");
        }

        return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    }

    // Registrar usuario
    public function insertar($usuario, $contrasena)
    {
        if (trim($usuario) === "" || $contrasena === "") {
            throw new InvalidArgumentException(
                "El usuario y la contraseña son obligatorios"
            );
        }

        // Cifrar mediante hash seguro
        $contrasena_hash = password_hash(
            $contrasena,
            PASSWORD_DEFAULT
        );

        $sql = "INSERT INTO usuario
                (usuario, contrasena_hash)
                VALUES (?, ?)";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $usuario,
            $contrasena_hash
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Usuario registrado correctamente"
        ];
    }

    // Editar nombre de usuario
    public function editar($id_usuario, $usuario)
    {
        $sql = "UPDATE usuario
                SET usuario = ?
                WHERE id_usuario = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $usuario,
            $id_usuario
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Usuario actualizado correctamente"
        ];
    }

    // Cambiar contraseña
    public function cambiarContrasena(
        $id_usuario,
        $contrasena
    ) {
        if ($contrasena === "") {
            throw new InvalidArgumentException(
                "La contraseña no puede estar vacía"
            );
        }

        $contrasena_hash = password_hash(
            $contrasena,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE usuario
                SET contrasena_hash = ?
                WHERE id_usuario = ?";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $contrasena_hash,
            $id_usuario
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return [
            "resultado" => "Ok",
            "mensaje" => "Contraseña actualizada correctamente"
        ];
    }

    // Verificar credenciales de inicio de sesión
    public function verificar($usuario, $contrasena)
    {
        $sql = "SELECT id_usuario, usuario, contrasena_hash
                FROM usuario
                WHERE usuario = ?
                LIMIT 1";

        $stmt = mysqli_prepare($this->conexion, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $usuario
        );

        mysqli_stmt_execute($stmt);

        $resultado = mysqli_stmt_get_result($stmt);
        $datos = mysqli_fetch_assoc($resultado);

        mysqli_stmt_close($stmt);

        if (
            $datos &&
            password_verify(
                $contrasena,
                $datos["contrasena_hash"]
            )
        ) {
            return [
                "resultado" => "Ok",
                "mensaje" => "Credenciales correctas",
                "usuario" => [
                    "id_usuario" => $datos["id_usuario"],
                    "usuario" => $datos["usuario"]
                ]
            ];
        }

        return [
            "resultado" => "Error",
            "mensaje" => "Usuario o contraseña incorrectos"
        ];
    }
}
?>