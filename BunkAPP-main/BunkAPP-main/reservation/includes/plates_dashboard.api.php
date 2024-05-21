<?php

function GetData()
{
    include("dbconnection.php");
    $response = array();
    
    if ($conn) {
        $sql = "SELECT plates.*, customers.name AS name
                FROM plates
                LEFT JOIN customers ON plates.customer_id = customers.id";

        $result = mysqli_query($conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) { // Controleer of er gegevens zijn opgehaald
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $response[$i]['customer_id'] = $row['customer_id'] ?? null;
                $response[$i]['name'] = $row['name'] ?? null;
                $response[$i]['reservation_id'] = $row['reservation_id'] ?? null;
                $response[$i]['plate_id'] = $row['id'] ?? null;
                $response[$i]['license_plate'] = $row['plate'] ?? null;
                $response[$i]['active'] = $row['active'] ?? null;
                
                $i++;
            }
            return json_encode($response, JSON_PRETTY_PRINT);
        } else {
            return json_encode($response); // Return een lege array als er geen gegevens zijn opgehaald
        }
    } else {
        echo "Database connection failed";
    }
}

function removePlate($conn, $plate_id) {
    include("dbconnection.php");
    
    $query = "DELETE FROM plates WHERE id = '$plate_id'";
    mysqli_query($conn, $query);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['plate_id']) && isset($_GET['action']) && $_GET['action'] == 'remove') {
        removePlate($conn, $_POST['plate_id']); // Zorg ervoor dat $conn correct is geïnitialiseerd en beschikbaar is
    }
}


?>