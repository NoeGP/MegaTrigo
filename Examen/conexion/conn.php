<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root"; // Cambiar si es necesario
$clave = "";       // Cambiar si es necesario
$base_datos = "megatrigo"; // Cambia el nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
} 
// else {
//     echo "Conexión exitosa";
// }

// mysqli_close($conn);}

?>

