
<?php

// Datos de conexión a MySQL
$servidor = "localhost";
$usuario = "root";
$clave = "";
$basedatos = "seitelcom_facturacion";

// Conectar al servidor MySQL
$conexion = mysqli_connect(
    $servidor,
    $usuario,
    $clave
);

if (!$conexion) {
    throw new Exception(
        "No se pudo conectar al servidor MySQL"
    );
}

// Seleccionar la base de datos
if (!mysqli_select_db($conexion, $basedatos)) {
    throw new Exception(
        "No se pudo seleccionar la base de datos"
    );
}

// Configurar caracteres especiales y tildes
if (!mysqli_set_charset($conexion, "utf8mb4")) {
    throw new Exception(
        "No se pudo configurar la codificación"
    );
}
?> 