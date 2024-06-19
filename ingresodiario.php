<!DOCTYPE html>
<html lang="es">
<head>
      <!-- BY NEORYU-->
    <meta charset="UTF-8">
    <title>Registro de Visitas al Restaurante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .formulario {
            width: 45%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .formulario label {
            display: block;
            margin-bottom: 10px;
        }
        .formulario input[type="text"], .formulario select {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .formulario input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .formulario input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php
// Función para limpiar la entrada
function limpiar_entrada($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

$servername = "localhost";  
$username = "root";         
$password = "";             
$database = "SHIZEN";       

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


function verificarTelefonoExistente($telefono) {
    global $conn;
    $sql_verificar = "SELECT TELEFONO FROM clientes WHERE TELEFONO = '$telefono'";
    $result_verificar = $conn->query($sql_verificar);
    return ($result_verificar->num_rows > 0);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_registrar"])) {
    $telefono = limpiar_entrada($_POST['telefono']);
    $fecha_registro = limpiar_entrada($_POST['fecha_registro']);
    $turno = limpiar_entrada($_POST['turno']);


    if (!verificarTelefonoExistente($telefono)) {
    
        header("Location: registro.php");
        exit();
    }

    $sql_insertar = "INSERT INTO registros (TELEFONO, FECHA_REGISTRO, TURNO) 
                     VALUES ('$telefono', '$fecha_registro', '$turno')";

    if ($conn->query($sql_insertar) === TRUE) {
        echo "<p>Datos registrados correctamente.</p>";
    } else {
        echo "<p>Error al registrar los datos: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<div class="formulario">
    <h2>Registro de Visitas al Restaurante</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
        
        <label for="fecha_registro">Fecha de Registro:</label>
        <input type="date" id="fecha_registro" name="fecha_registro" required>
        
        <label for="turno">Turno:</label>
        <select id="turno" name="turno" required>
            <option value="TARDE">TARDE</option>
            <option value="NOCHE">NOCHE</option>
        </select>
        
        <input type="submit" name="submit_registrar" value="Registrar Visita">
    </form>
</div>
<li><a href="index.HTML" >Regresar a Inicio</a></li>
</body>
</html>
