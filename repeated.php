<?php
require "config/Conexion.php";

// // Consulta SQL para identificar valores repetidos en 'valor'
// $sql = "SELECT valor, COUNT(*) AS cantidad_repeticiones
//         FROM mex_motores
//         GROUP BY valor
//         HAVING COUNT(*) > 1";

// $query = $conexion->query($sql);

// if ($query !== false && $query->num_rows > 0) {
//     // Mostrar los valores repetidos y la cantidad de repeticiones
//     while ($row = $query->fetch_assoc()) {
//         echo "Valor: " . $row['valor'] . " - Repeticiones: " . $row['cantidad_repeticiones'] . "<br>";
//     }
// } else {
//     echo "No se encontraron valores repetidos.";
// }

// $conexion->close(); // Cerrar la conexión a la base de datos
$sql = "SELECT valor, COUNT(*) AS cantidad_repeticiones
        FROM mex_motores
        GROUP BY valor
        HAVING COUNT(*) > 1
        LIMIT 10";

$query = $conexion->query($sql);

if ($query !== false && $query->num_rows > 0) {
    // Crear un array para almacenar los datos
    $repeatedValues = array();

    // Almacenar los valores repetidos en el array
    while ($row = $query->fetch_assoc()) {
        $repeatedValues[] = $row;
    }

    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($repeatedValues);
} else {
    echo "No se encontraron valores repetidos.";
}

$conexion->close(); // Cerrar la conexión a la base de datos
?>
