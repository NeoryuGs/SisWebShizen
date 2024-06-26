<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shizen";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de datos</title>
    <link rel="stylesheet" type="text/css" href="stylemostrardatos.css">
    <script>
        function filterTables() {
            const input = document.getElementById('filterInput');
            const filter = input.value.toUpperCase();

            // Filtrar tabla de clientes
            filterTable('clientesTable', filter);

            // Filtrar tabla de registros
            filterTable('registrosTable', filter);

            // Redirigir o recargar la página si el filtro está vacío
            if (filter.trim() === '') {
                window.location.reload();
          
            }
        }

        function filterTable(tableId, filter) {
            const table = document.getElementById(tableId);
            const rows = table.getElementsByTagName('tr');
            let found = false;

            for (let i = 1; i < rows.length; i++) {
                const td = rows[i].getElementsByTagName('td');
                let rowVisible = false;

                for (let j = 0; j < td.length; j++) {
                    if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                        rowVisible = true;
                        break;
                    }
                }

                if (rowVisible) {
                    rows[i].style.display = '';
                    found = true;
                } else {
                    rows[i].style.display = 'none';
                }
            }

            // Mostrar mensaje de "No hay datos" si no se encontraron resultados
            const noDataMessage = table.querySelector('.no-data-message');
            if (!found && !noDataMessage) {
                const messageElement = document.createElement('p');
                messageElement.className = 'no-data-message';
                messageElement.textContent = 'No hay datos';
                table.parentNode.insertBefore(messageElement, table.nextSibling);
            } else if (found && noDataMessage) {
                noDataMessage.remove();
            }
        }
    </script>
</head>
<body>
<li><a href="index.HTML" >Regresar a Inicio</a></li>
    <h2>Datos de Clientes y Registros</h2>
    <input type="text" id="filterInput" oninput="filterTables()" placeholder="Filtrar por cualquier campo...">
    <div class="container">
        <div class="table-container">
            <h3>Datos de Clientes</h3>
            <?php
            $sql = "SELECT * FROM clientes";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table id='clientesTable' border='1'><tr>";
                echo "<th>Teléfono</th>";
                echo "<th>Nombre</th>";
                echo "<th>Fecha de Nacimiento</th>";
                echo "<th>Nacionalidad</th>";
                echo "<th>Correo</th>";
                echo "<th>Alergias</th>";
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TELEFONO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NOMBRE']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['FECHA_NACIMIENTO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NACIONALIDAD']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['CORREO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['ALERGIAS']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay datos en la tabla clientes</p>";
            }
            ?>
        </div>

        <div class="table-container">
            <h3>Datos de Registros</h3>
            <?php
            $sql = "SELECT * FROM registros";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table id='registrosTable' border='1'><tr>";
                echo "<th>ID</th>";
                echo "<th>Teléfono</th>";
                echo "<th>Fecha de Registro</th>";
                echo "<th>Turno</th>";
                echo "</tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TELEFONO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['FECHA_REGISTRO']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TURNO']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No hay datos en la tabla registros</p>";
            }
            ?>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>

