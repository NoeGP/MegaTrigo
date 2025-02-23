<?php
session_start();
// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 3) {
    header("Location: ../index.html");
    exit();
}

include '../conexion/conn.php';

// Consulta para obtener los datos de auditoría
$sql = "SELECT a.id_auditoria, a.id_nota, a.accion, a.usuario, a.fecha, 
        n.nombre_cliente, n.total 
        FROM auditoria_remision a 
        LEFT JOIN notas_remision n ON a.id_nota = n.id_nota 
        ORDER BY a.fecha DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Auditoría</title>
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registro de Auditoría</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Auditoría</th>
                    <th>ID Nota</th>
                    <th>Acción</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_auditoria']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_nota']); ?></td>
                    <td><?php echo htmlspecialchars($row['accion']); ?></td>
                    <td><?php echo htmlspecialchars($row['usuario']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['total']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <button onclick="window.location.href='../Ingreso.html'">Volver</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
