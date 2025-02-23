<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header("Location: ../index.html");
    exit();
}

include '../conexion/conn.php';

$id_vendedor = $_SESSION['id_usu'];
$sql = "SELECT n.*, GROUP_CONCAT(p.nom_pro) as productos 
        FROM notas_remision n 
        LEFT JOIN productos_remision pr ON n.id_nota = pr.id_nota 
        LEFT JOIN producto p ON pr.producto = p.id_pro 
        WHERE n.id_vendedor = ? 
        GROUP BY n.id_nota 
        ORDER BY n.fecha_emision DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_vendedor);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Notas de Remisión</title>
    <link rel="stylesheet" href="../estilos/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Mis Notas de Remisión</h2>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Nota</th>
                    <th>Fecha Emisión</th>
                    <th>Cliente</th>
                    <th>Dirección</th>
                    <th>Total</th>
                    <th>Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_nota']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_emision']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_cliente']); ?></td>
                    <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                    <td>$<?php echo htmlspecialchars($row['total']); ?></td>
                    <td><?php echo htmlspecialchars($row['productos']); ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm" 
                                onclick="eliminarNota(<?php echo $row['id_nota']; ?>)">
                            Eliminar
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div>
            <button onclick="window.location.href='notas_emicion.php'">Crear Nueva Nota</button>
            <button onclick="window.location.href='../Ingreso.html'">Salir</button>
        </div>         
    </div>

    <script>
    function eliminarNota(id) {
        if(confirm('¿Está seguro de eliminar esta nota?')) {
            fetch('../consultas/eliminar_nota.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id_nota=' + id
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                if(data.includes('exitosamente')) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la nota');
            });
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>