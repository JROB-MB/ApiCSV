<?php
// require "config/Conexion.php";

// //print_r($_SERVER['REQUEST_METHOD']);
// switch ($_SERVER['REQUEST_METHOD']) {
//     case 'GET':
//         // Consulta SQL para seleccionar datos de la tabla
//         $sql = "SELECT cve_entidad, cve_municipio, desc_municipio, ano, valor FROM mex_motores LIMIT 10";

//         $query = $conexion->query($sql);

//         if ($query->num_rows > 0) {
//             $data = array();
//             while ($row = $query->fetch_assoc()) {
//                 $data[] = $row;
//             }
//             // Devolver los resultados en formato JSON
//             header('Content-Type: application/json');
//             echo json_encode($data);
//         } else {
//             echo "No se encontraron registros en la tabla.";
//         }

//         $conexion->close();
//         break;

//     case 'POST': 
//         $sql = "SELECT AVG(valor) AS media_valor FROM mex_motores";

//         $query = $conexion->query($sql);

//         if ($query !== false && $query->num_rows > 0) {
//             $row = $query->fetch_assoc();
//             // Devolver el resultado (media) en formato JSON
//             $media = $row['media_valor'];

//             header('Content-Type: application/json');
//             echo json_encode(array('media_valor' => $media));
//         } else {
//             echo "No se pudo calcular la media de la tabla.";
//         }

//         $conexion->close();
//         break;

//     default:
//         // Handle other HTTP methods or cases if needed
//         echo "Método no válido.";
//         break;
// }


?>
<?php
require "config/Conexion.php";

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Consulta SQL para seleccionar datos de la tabla
        $sql = "SELECT cve_entidad, cve_municipio, desc_municipio, ano, valor FROM mex_motores LIMIT 10";

        $query = $conexion->query($sql);

        if ($query->num_rows > 0) {
            $data = array();
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            // Devolver los resultados en formato JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "No se encontraron registros en la tabla.";
        }

        $conexion->close();
        break;

    case 'POST':
        // Consulta SQL para calcular la mediana del campo 'valor'
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

        $conexion->close();
        break;

    default:
        echo "Método no válido.";
        break;
}
?>