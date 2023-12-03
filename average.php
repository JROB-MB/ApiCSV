<?php
require "config/Conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT AVG(valor) AS average_valor FROM mex_motores"; // Corrected the alias to match the one used in PHP

    $query = $conexion->query($sql);

    if ($query !== false && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        // Get the average value and format as JSON
        $media = $row['average_valor']; // Corrected the array key to match the alias in the SQL query

        header('Content-Type: application/json');
        echo json_encode(array('media_valor' => $media)); // Changed the key to 'media_valor' to align with JavaScript
    } else {
        echo "No se pudo calcular la media de la tabla.";
    }

    $conexion->close(); // Close the database connection
} else {
    echo "Método no válido.";
}
?>
