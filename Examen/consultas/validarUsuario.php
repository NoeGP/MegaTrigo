<?php
// Incluir la conexión
include '../conexion/conn.php';


// Verificar si se enviaron los datos del formulario
if (isset($_POST['email_usu']) && isset($_POST['pass_usu'])) {
    $email_usu = $_POST['email_usu'];
    $pass_usu = $_POST['pass_usu'];

    // Consulta preparada para evitar inyección SQL
    $sql = "SELECT id_usu, pass_usu, email_usu,rol from usuarios WHERE email_usu='$email_usu' AND pass_usu= SHA2('$pass_usu',256)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    echo $result->num_rows;
    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Comparar la contraseña cifrada con la ingresada
        
        
        if (hash('sha256', $pass_usu) === $row['pass_usu']) {
            // Iniciar sesión
            session_start();
            $_SESSION['id_usu'] = $row['id_usu'];
            $_SESSION['email_usu'] = $row['email_usu'];
            $_SESSION['rol'] = $row['rol'];

            // Redirigir al formulario si la autenticación es exitosa
                // Role-based redirection
            switch($_SESSION['rol']) {
                case 1: // Vendor
                    header("Location: ../formulario/gestionar_notas.php");
                    break;
                case 2: // Comprador
                    header("Location: ../formulario/consultar_notas.php");
                    break;
                case 3: // Administrador
                    header("Location: ../formulario/adm.php");
                    break;
                default:
                        echo "<script>alert('Rol no reconocido.'); window.location.href='index.html';</script>";
            }
            
            exit();
        } 
            else {
                echo "<script>alert('Contraseña incorrecta.'); window.location.href='index.html';</script>";
            }
    }
    else {
        echo "<script>alert('Usuario no encontrado.'); window.location.href='index.html';</script>";
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "Por favor, completa todos los campos del formulario.";
}

?>