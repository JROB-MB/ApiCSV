<?php
require "config/Conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consulta SQL para seleccionar datos de la tabla
    $sql = "SELECT AVG(valor) AS mediana_valor
        FROM (
            SELECT valor
            FROM (
                SELECT valor, @rownum := @rownum + 1 AS row_number, @total_rows := @rownum
                FROM (SELECT @rownum := 0) r, mex_motores
                ORDER BY valor
            ) as numbered_rows
            WHERE row_number IN (
                FLOOR((@total_rows + 1) / 2), FLOOR((@total_rows + 2) / 2)
            )
        ) AS subquery;";

    // Ejecutar la consulta SQL
    $query = $conexion->query($sql);

    if ($query !== false && $query->num_rows > 0) {
        // Obtener los resultados de la consulta
        $row = $query->fetch_assoc();
        $median = $row['mediana_valor'];

        // Devolver el resultado (mediana) en formato JSON
        header('Content-Type: application/json');
        echo json_encode(array('mediana_valor' => $median));
    } else {
        echo "No se pudieron obtener datos para calcular la mediana.";
    }

    $conexion->close(); // Close the database connection
} else {
    echo "Método no válido.";
}

?>