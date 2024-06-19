<!DOCTYPE html>
<html lang="es">
<head>
      <!-- BY NEORYU-->
    <meta charset="UTF-8">
    <title>Administrador de Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .formulario, .busqueda {
            display: inline-block;
            width: 45%;
            vertical-align: top;
            margin-right: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .formulario label, .busqueda label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="date"], input[type="email"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .resultado {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
        }
        .resultado p {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<?php
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
function verificarRegistrosAsociados($telefono) {
    global $conn;
    $sql_verificar = "SELECT * FROM registros WHERE TELEFONO = '$telefono'";
    $result_verificar = $conn->query($sql_verificar);
    return ($result_verificar->num_rows > 0); 
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_insertar"])) {
    $telefono = limpiar_entrada($_POST['telefono']);
    $nombre = limpiar_entrada($_POST['nombre']);
    $fecha_nacimiento = limpiar_entrada($_POST['fecha_nacimiento']);
    $nacionalidad = limpiar_entrada($_POST['nacionalidad']);
    $correo = limpiar_entrada($_POST['correo']);
    $alergias = limpiar_entrada($_POST['alergias']);

    
    $sql = "INSERT INTO clientes (TELEFONO, NOMBRE, FECHA_NACIMIENTO, NACIONALIDAD, CORREO, ALERGIAS) 
            VALUES ('$telefono', '$nombre', '$fecha_nacimiento', '$nacionalidad', '$correo', '$alergias')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Datos ingresados correctamente.</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

?>

<span class="formulario">
    <h2>Formulario de Ingreso de Clientes</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>
        
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        
        <label for="nacionalidad">Nacionalidad:</label>
        <input type="text" id="nacionalidad" name="nacionalidad" required>
        
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo">
        
        <label for="alergias">Alergias:</label>
        <input type="text" id="alergias" name="alergias" required>
        
        <input type="submit" name="submit_insertar" value="Guardar">
    </form>
</span>

<span class="busqueda">
    <h2>Búsqueda y Eliminación de Clientes</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="telefono_buscar">Buscar por Teléfono:</label>
        <input type="text" id="telefono_buscar" name="telefono_buscar" required>
        <input type="submit" name="submit_buscar" value="Buscar">
    </form>
    <div class="resultado">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_buscar"])) {
            $telefono_buscar = limpiar_entrada($_POST['telefono_buscar']);

            $sql_buscar = "SELECT * FROM clientes WHERE TELEFONO = '$telefono_buscar'";
            $result = $conn->query($sql_buscar);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<h2>Datos del Cliente</h2>";
                echo "<span>";
                echo "<p><strong>Teléfono:</strong> " . $row["TELEFONO"] . "</p>";
                echo "<p><strong>Nombre:</strong> " . $row["NOMBRE"] . "</p>";
                echo "<p><strong>Fecha de Nacimiento:</strong> " . $row["FECHA_NACIMIENTO"] . "</p>";
                echo "<p><strong>Nacionalidad:</strong> " . $row["NACIONALIDAD"] . "</p>";
                echo "<p><strong>Correo:</strong> " . $row["CORREO"] . "</p>";
                echo "<p><strong>Alergias:</strong> " . $row["ALERGIAS"] . "</p>";
                echo "</span>";
                
                echo "<form action=\"\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"telefono_eliminar\" value=\"" . $telefono_buscar . "\">";
                echo "<input type=\"submit\" name=\"submit_eliminar\" value=\"Eliminar Cliente\">";
                echo "</form>";
            } else {
                echo "<p>No se encontraron datos para el teléfono ingresado.</p>";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_eliminar"])) {
            $telefono_eliminar = limpiar_entrada($_POST['telefono_eliminar']);
        
          
            if (verificarRegistrosAsociados($telefono_eliminar)) {
                echo "<p>El cliente tiene visitas al restaurante. No se puede eliminar.</p>";
            } else {
             
                $sql_eliminar = "DELETE FROM clientes WHERE TELEFONO = '$telefono_eliminar'";
        
                if ($conn->query($sql_eliminar) === TRUE) {
                    echo "<p>Cliente eliminado correctamente.</p>";
                } else {
                    echo "<p>Error al eliminar el cliente: " . $conn->error . "</p>";
                }
            }
        }
        $conn->close();
        ?>
    </div>
</span>
<li><a href="index.HTML" >Regresar a Inicio</a></li>
</body>
</html>
