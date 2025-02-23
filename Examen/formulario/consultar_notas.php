<?php
session_start();
require '../conexion/conn.php';

$id_usuario = $_SESSION['id_usu'];

$sql = "SELECT nr.id_nota, nr.fecha_emision, nr.nombre_cliente, nr.direccion, nr.total, u.nom_usu as vendedor 
        FROM notas_remision nr
        JOIN usuarios u ON nr.id_vendedor = u.id_usu
        WHERE nr.nombre_cliente = (SELECT nom_usu FROM usuarios WHERE id_usu = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$notas = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $notas[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar Notas de Remisión</title>
    <link rel="stylesheet" href="../estilos/style.css">


</head>
<body>
    <h1>Notas de Remisión</h1>
    <table>
        <thead>
            <tr>
                <th>Fecha de Emisión</th>
                <th>Nombre del Cliente</th>
                <th>Dirección</th>
                <th>Total</th>
                <th>Vendedor</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($notas)): ?>
                <?php foreach ($notas as $nota): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nota['fecha_emision']); ?></td>
                        <td><?php echo htmlspecialchars($nota['nombre_cliente']); ?></td>
                        <td><?php echo htmlspecialchars($nota['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($nota['total']); ?></td>
                        <td><?php echo htmlspecialchars($nota['vendedor']); ?></td>
                        <td>
                            <a href="vista_remision.php?id=<?php echo $nota['id_nota']; ?>" class="btn-ver">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay notas de remisión disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button onclick="window.location.href='../Ingreso.html'" style="margin-top: 20px;">Salir</button>
</body>
</html>