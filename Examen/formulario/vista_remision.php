<?php
session_start();
require '../conexion/conn.php';

if (!isset($_SESSION['id_usu']) || !isset($_GET['id'])) {
    header("Location: ../Ingreso.html");
    exit();
}

$id_nota = $_GET['id'];

// Query for main note details with products
$sql = "SELECT nr.id_nota, nr.fecha_emision, nr.nombre_cliente, nr.direccion, 
        nr.total, nr.firma, pr.cantidad, p.nom_pro as producto, p.pre_uni_pro as precio_unitario, 
        pr.importe
        FROM notas_remision nr
        JOIN productos_remision pr ON nr.id_nota = pr.id_nota
        JOIN producto p ON pr.producto = p.id_pro
        WHERE nr.id_nota = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_nota);
$stmt->execute();
$result = $stmt->get_result();
$items = [];
$nota = null;

while($row = $result->fetch_assoc()) {
    if(!$nota) {
        $nota = [
            'id_nota' => $row['id_nota'],
            'fecha_emision' => $row['fecha_emision'],
            'nombre_cliente' => $row['nombre_cliente'],
            'direccion' => $row['direccion'],
            'total' => $row['total'],
            'firma' => $row['firma']
        ];
    }
    $items[] = [
        'cantidad' => $row['cantidad'],
        'producto' => $row['producto'],
        'precio_unitario' => $row['precio_unitario'],
        'importe' => $row['importe']
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Nota de Remisión</title>
    <link rel="stylesheet" href="../estilos/style.css">


</head>
<body>
    <h1>Detalle de Nota de Remisión</h1>
    
    <!-- Información principal -->
    <table class="info-principal">
        <tr>
            <th>Nota de Remisión:</th>
        </tr>
        <tr>
            <th>Fecha:</th>
            <td><?php echo htmlspecialchars($nota['fecha_emision']); ?></td>
        </tr>
        <tr>
            <th>Cliente:</th>
            <td><?php echo htmlspecialchars($nota['nombre_cliente']); ?></td>
        </tr>
        <tr>
            <th>Dirección:</th>
            <td><?php echo htmlspecialchars($nota['direccion']); ?></td>
        </tr>
    </table>

    <!-- Detalles de productos -->
    <h2>Productos</h2>
    <table class="detalles-productos">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Precio Unitario</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($item['producto']); ?></td>
                <td><?php echo htmlspecialchars($item['precio_unitario']); ?></td>
                <td><?php echo htmlspecialchars($item['importe']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Firma y total -->
    <div class="firma-total">
        <p>Firma: <?php echo htmlspecialchars($nota['firma']); ?></p>
        <p>Total: <?php echo htmlspecialchars($nota['total']); ?></p>
    </div>

    <button onclick="window.location.href='consultar_notas.php'">Volver</button>
</body>
</html>
