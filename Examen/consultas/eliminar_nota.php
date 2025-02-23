<?php
session_start();

// Verificar autenticación y autorización del usuario
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    header('HTTP/1.1 403 Forbidden');
    exit(json_encode(['success' => false, 'message' => 'Acceso no autorizado']));
}

include '../conexion/conn.php';

// Validar entrada
if (!isset($_POST['id_nota']) || !is_numeric($_POST['id_nota'])) {
    header('HTTP/1.1 400 Bad Request');
    exit(json_encode(['success' => false, 'message' => 'ID de nota no válido']));
}

$id_nota = intval($_POST['id_nota']);
$id_vendedor = $_SESSION['id_usu'];

try {
    // Iniciar transacción
    $conn->begin_transaction();

    // Verificar la propiedad de la nota
    $stmt = $conn->prepare("SELECT id_nota FROM notas_remision WHERE id_nota = ? AND id_vendedor = ?");
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }
    $stmt->bind_param("ii", $id_nota, $id_vendedor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Eliminar productos_remision primero
        $delete_products = $conn->prepare("DELETE FROM productos_remision WHERE id_nota = ?");
        $delete_products->bind_param("i", $id_nota);
        $delete_products->execute();

        // Eliminar la nota
        $delete_stmt = $conn->prepare("DELETE FROM notas_remision WHERE id_nota = ? AND id_vendedor = ?");
        if (!$delete_stmt) {
            throw new Exception("Error al preparar la consulta de eliminación: " . $conn->error);
        }
        $delete_stmt->bind_param("ii", $id_nota, $id_vendedor);
        
        if ($delete_stmt->execute()) {
            $conn->commit();
            echo "Nota eliminada exitosamente";
        } else {
            throw new Exception("Error al eliminar la nota: " . $delete_stmt->error);
        }
    } else {
        throw new Exception("No tiene permiso para eliminar esta nota");
    }

} catch (Exception $e) {
    $conn->rollback();
    header('HTTP/1.1 500 Internal Server Error');
    echo $e->getMessage();
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($delete_products)) $delete_products->close();
    if (isset($delete_stmt)) $delete_stmt->close();
    $conn->close();
}
?>