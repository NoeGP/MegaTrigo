<?php
include '../conexion/conn.php';
session_start();

// Verificar si el usuario está logueado y es vendedor
if (!isset($_SESSION['id_usu']) || $_SESSION['rol'] != 1) {
    header("Location: ../Ingreso.html");
    exit();
}

// Verificar que los datos necesarios estén presentes
if (!isset($_POST['fecha_emision']) || 
    !isset($_POST['nombre_cliente']) || 
    !isset($_POST['direccion']) || 
    !isset($_POST['total']) || 
    !isset($_POST['firma'])) {
    echo "<script>
        alert('Todos los campos son obligatorios');
        window.history.back();
    </script>";
    exit();
}

$fecha_emision = $_POST['fecha_emision'];
$nombre_cliente = $_POST['nombre_cliente'];
$direccion = $_POST['direccion'];
$total = $_POST['total'];
$firma = $_POST['firma'];
$id_vendedor = $_SESSION['id_usu']; // Asumiendo que el ID del vendedor está en la sesión

// Validación de fecha
if (strtotime($fecha_emision) > strtotime(date('Y-m-d'))) {
    echo "<script>
        alert('La fecha de emisión no puede ser mayor a la fecha actual');
        window.history.back();
    </script>";
    exit();
}

// Insertar la nota de remisión en la tabla `notas_remision`
$sql_nota = "INSERT INTO notas_remision (fecha_emision, nombre_cliente, direccion, total, firma, id_vendedor)
             VALUES ('$fecha_emision', '$nombre_cliente', '$direccion', $total, '$firma', $id_vendedor)";

if ($conn->query($sql_nota) === TRUE) {
    $id_nota = $conn->insert_id; // Obtener el ID de la nota recién insertada

    // Insertar los productos en la tabla `productos_remision`
    $cantidades = $_POST['cantidad'];
    $productos = $_POST['producto'];
    $importes = $_POST['importe'];

    for ($i = 0; $i < count($productos); $i++) {
        $cantidad = $cantidades[$i];
        $producto = $productos[$i];
        $importe = $importes[$i];

        $sql_producto = "INSERT INTO productos_remision (id_nota, cantidad, producto, importe)
                         VALUES ($id_nota, $cantidad, '$producto',  $importe)";
        if (!$conn->query($sql_producto)) {
            echo "Error al insertar producto: " . $conn->error;
        }
    }

        echo "<script>
        alert('Nota de remisión generada exitosamente');
        window.location.href = '../formulario/notas_emicion.php';
             </script>";
} else {
    echo "Error: " . $sql_nota . "<br>" . $conn->error;
}

$conn->close();
?>