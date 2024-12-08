<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'guardar':
            $nombre = $conn->real_escape_string($_POST['nombre']);
            $apellido = $conn->real_escape_string($_POST['apellido']);
            $email = $conn->real_escape_string($_POST['email']);
            $telefono = $conn->real_escape_string($_POST['telefono']);
            $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);

            $sql = "INSERT INTO usuarios (nombre, apellido, email, telefono, fecha_nacimiento)
                    VALUES ('$nombre', '$apellido', '$email', '$telefono', '$fecha_nacimiento')";

            if ($conn->query($sql) === TRUE) {
                $nuevo_id = $conn->insert_id;
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Registro guardado correctamente.',
                    'nuevo_id' => $nuevo_id
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al guardar: ' . $conn->error
                ]);
            }
            break;

            case 'eliminar':
                $id = intval($_POST['numero_empleado']);
    
                $sql = "DELETE FROM usuarios WHERE id=$id";
    
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(['status' => 'success', 'message' => 'Registro eliminado correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $conn->error]);
                }
                break;

            case 'modificar':
                $id = intval($_POST['numero_empleado']);
                $nombre = $conn->real_escape_string($_POST['nombre']);
                $apellido = $conn->real_escape_string($_POST['apellido']);
                $email = $conn->real_escape_string($_POST['email']);
                $telefono = $conn->real_escape_string($_POST['telefono']);
                $fecha_nacimiento = $conn->real_escape_string($_POST['fecha_nacimiento']);
    
                $sql = "UPDATE usuarios SET nombre='$nombre', apellido='$apellido', email='$email', telefono='$telefono', fecha_nacimiento='$fecha_nacimiento'
                        WHERE id=$id";
    
                if ($conn->query($sql) === TRUE) {
                    echo json_encode(['status' => 'success', 'message' => 'Registro modificado correctamente.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al modificar: ' . $conn->error]);
                }
                break;

             case 'listar':
            
            $sql = "SELECT * FROM usuarios";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $usuarios = [];
                while ($row = $result->fetch_assoc()) {
                    $usuarios[] = $row;
                }
                echo json_encode($usuarios);
            } else {
                echo json_encode([]);
            }
            break;

            case 'nuevo':
                
                $sql = "SELECT MAX(id) AS max_id FROM usuarios";
                $result = $conn->query($sql);
    
                if ($result && $row = $result->fetch_assoc()) {
                    $siguiente_id = $row['max_id'] ? $row['max_id'] + 1 : 1;
                    echo json_encode(['siguiente_id' => $siguiente_id]);
                } else {
                    echo json_encode(['siguiente_id' => 1]);
                }
                break;

        case 'navegar':
            $id = intval($_POST['id']);
            $direccion = $_POST['direccion'];  

            if ($direccion === 'siguiente') {
                $sql = "SELECT * FROM usuarios WHERE id > $id ORDER BY id ASC LIMIT 1";
            } elseif ($direccion === 'anterior') {
                $sql = "SELECT * FROM usuarios WHERE id < $id ORDER BY id DESC LIMIT 1";
            }

            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                echo json_encode($usuario);
            } else {
                echo json_encode([]);
            }
            break;

            case 'obtenerUltimoID':
                $sql = "SELECT * FROM usuarios ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        echo json_encode($usuario);  
    } else {
        echo json_encode([]);  
    }

break; 
                case 'mostrar':
                    $id = $_POST['id'];
                    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        $usuario = $result->fetch_assoc();
                        echo json_encode($usuario); 
                    } else {
                        echo json_encode([]); 
                    }
                    break;
    
                    case 'siguiente':
                        $id = $_POST['id']; 
                        $sql = "SELECT * FROM usuarios WHERE id > '$id' AND id IS NOT NULL ORDER BY id ASC LIMIT 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $usuario = $result->fetch_assoc();
                            echo json_encode($usuario);
                        } else {
                            echo json_encode(["error" => "No hay más registros hacia adelante."]); 
                        }
                        break;
    
                        case 'anterior':
                            $id = $_POST['id']; 
                            $sql = "SELECT * FROM usuarios WHERE id < '$id' AND id IS NOT NULL ORDER BY id DESC LIMIT 1";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $usuario = $result->fetch_assoc();
                                echo json_encode($usuario); 
                            } else {
                                echo json_encode(["error" => "No hay más registros hacia atrás."]); 
                            }
                            break;

        default:
            echo "Acción no reconocida.";
            break;
    }
}
?>

