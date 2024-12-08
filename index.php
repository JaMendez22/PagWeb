<?php include 'conexion.php'; 

$sql = "SELECT MAX(id) AS ultimo_id FROM usuarios";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$ultimo_id = $row['ultimo_id']; 


if ($ultimo_id === NULL) {
    $ultimo_id = 1;  
}


$siguiente_id = $ultimo_id + 1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP, jQuery y Ajax</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="bg-primary vh-100 vw-100">
        <div class="d-grid justify-content-center align-content-center vh-100 text-white">
            <legend><strong>Registro de usuarios</strong></legend>
            <p>
            <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Anterior" id="anterior">
            <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Siguiente" id="siguiente">
            </p>
            <form id="form-crud">
                <p>
                    <label style="color: white;" for="numero_empleado">Número de empleado: </label>
                    <input type="text" class="border-warning" name="numero_empleado" id="numero_empleado" readonly>
                </p>

                <p>
                    <label style="color: white;" for="nombre">Nombre: </label>
                    <input type="text" class="border-warning" name="nombre" id="nombre" required>
                </p>

                <p>
                    <label style="color: white;" for="apellido">Apellido: </label>
                    <input type="text" class="border-warning" name="apellido" id="apellido" required>
                </p>

                <p>
                    <label style="color: white;" for="email">E-mail: </label>
                    <input type="email" class="border-warning" name="email" id="email" required>
                </p>

                <p>
                    <label style="color: white;" for="telefono">Teléfono: </label>
                    <input type="text" class="border-warning" name="telefono" id="telefono" required>
                </p>

                <p>
                    <label style="color: white;" for="fecha_nacimiento">Fecha de nacimiento: </label>
                    <input type="date" class="border-warning" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </p>

                <p>
                    <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Guardar" id="guardar">
                    <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Modificar" id="modificar">
                    <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Eliminar" id="eliminar">
                    <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Listar" id="listar">
                    <input class="btn btn-warning btn-sm" style="color: white; font-weight: bold;" type="button" value="Nuevo" id="nuevo">
                </p>
            </form>

            <div id="resultado" style="display: none;"></div>
        </div>
    </div>
    
    <script>
    var current_id = 0; 
    var ultimo_id = <?php echo $ultimo_id; ?>;  
    </script>

  <script>
 function mostrarUsuario(id) {
            $.ajax({
                url: 'acciones.php',
                type: 'POST',
                data: { accion: 'mostrar', id: id },
                success: function(response) {
                    var usuario = JSON.parse(response);
                    if (usuario) {
                        $('#numero_empleado').val(usuario.id);
                        $('#nombre').val(usuario.nombre);
                        $('#apellido').val(usuario.apellido);
                        $('#email').val(usuario.email);
                        $('#telefono').val(usuario.telefono);
                        $('#fecha_nacimiento').val(usuario.fecha_nacimiento);
                        current_id = usuario.id;  
                        actualizarBotones();  
                    }
                }
            });
        }

        
        function actualizarBotones() {
            
            if (current_id <= 1) {
                $('#anterior').prop('disabled', true);
            } else {
                $('#anterior').prop('disabled', false);
            }

           
            if (current_id >= ultimo_id) {
                $('#siguiente').prop('disabled', true);
            } else {
                $('#siguiente').prop('disabled', false);
            }
        }

      
        $('#siguiente').click(function() {
    if (current_id < ultimo_id) {
        $.ajax({
            url: 'acciones.php',
            type: 'POST',
            data: { accion: 'siguiente', id: current_id },
            success: function(response) {
                var usuario = JSON.parse(response);
                if (usuario.error) {
                    alert(usuario.error);
                } else {
                    // Actualizar campos con el nuevo usuario
                    $('#numero_empleado').val(usuario.id);
                    $('#nombre').val(usuario.nombre);
                    $('#apellido').val(usuario.apellido);
                    $('#email').val(usuario.email);
                    $('#telefono').val(usuario.telefono);
                    $('#fecha_nacimiento').val(usuario.fecha_nacimiento);
                    current_id = usuario.id;  // Actualizar el ID actual
                }
                actualizarBotones();
            }
        });
    }
});

      
$('#anterior').click(function() {
    if (current_id > 1) {
        $.ajax({
            url: 'acciones.php',
            type: 'POST',
            data: { accion: 'anterior', id: current_id },
            success: function(response) {
                var usuario = JSON.parse(response);
                if (usuario.error) {
                    alert(usuario.error);
                } else {
                    // Actualizar campos con el nuevo usuario
                    $('#numero_empleado').val(usuario.id);
                    $('#nombre').val(usuario.nombre);
                    $('#apellido').val(usuario.apellido);
                    $('#email').val(usuario.email);
                    $('#telefono').val(usuario.telefono);
                    $('#fecha_nacimiento').val(usuario.fecha_nacimiento);
                    current_id = usuario.id;  // Actualizar el ID actual
                }
                actualizarBotones();
            }
        });
    }
});

    
        mostrarUsuario(current_id);

       
        $('#nuevo').click(function() {
            var siguiente_id = ultimo_id + 1;
            $('#numero_empleado').val(siguiente_id);
            $('#nombre').val('');
            $('#apellido').val('');
            $('#email').val('');
            $('#telefono').val('');
            $('#fecha_nacimiento').val('');
            current_id = siguiente_id;
            actualizarBotones(); 
        });

   
        $('#guardar').click(function() {
            var formData = $('#form-crud').serialize() + '&accion=guardar';
            $.ajax({
                url: 'acciones.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert(data.message);
                        ultimo_id++; 
                        mostrarUsuario(current_id); 
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Error al guardar los datos.');
                }
            });
        });

        $('#modificar').click(function() {
            var formData = $('#form-crud').serialize() + '&accion=modificar';
            $.ajax({
                url: 'acciones.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    mostrarUsuario(current_id); 
                },
                error: function() {
                    alert('Error al modificar los datos.');
                }
            });
        });

        $('#eliminar').click(function() {
            var formData = $('#form-crud').serialize() + '&accion=eliminar';
            $.ajax({
                url: 'acciones.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    alert(data.message);
                    mostrarUsuario(current_id);  
                },
                error: function() {
                    alert('Error al eliminar los datos.');
                }
            });
        });

      
        $('#listar').click(function() {
    $.ajax({
        url: 'acciones.php',
        type: 'POST',
        data: { accion: 'listar' },
        success: function(response) {
            var usuarios = JSON.parse(response);
            if (usuarios.length > 0) {
                var html = '<table class="table table-striped"><thead><tr><th>N°Empleado</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>Fecha de Nacimiento</th></tr></thead><tbody>';
                usuarios.forEach(function(usuario) {
                    html += '<tr><td>' + usuario.id + '</td><td>' + usuario.nombre + '</td><td>' + usuario.apellido + '</td><td>' + usuario.email + '</td><td>' + usuario.telefono + '</td><td>' + usuario.fecha_nacimiento + '</td></tr>';
                });
                html += '</tbody></table>';
                $('#resultado').html(html).toggle();  
            } else {
                $('#resultado').html('<p>No hay usuarios registrados.</p>').toggle(); 
            }
        },
        error: function() {
            alert('Error al obtener los datos.');
        }
    });
});

    </script>
</body>
</html>
