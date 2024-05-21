<?php
include('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $customers_id = $_POST['id'];
    $reservation_date = date('Y-m-d');
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $park_location = $_POST['park_location'];
    $room_type = $_POST['room_type'];
    $num_people = $_POST['num_people'];
    $special_requests = $_POST['special_requests'];
    $license_plate = $_POST['license_plate'];

    $response = array();

    $checkQuery = "SELECT * FROM reservations WHERE start_date = '$start_date' AND end_date = '$end_date' AND room_type = '$room_type' AND customers_id = '$customers_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult) {
        if (mysqli_num_rows($checkResult) > 0) {
            $response['success'] = false;
            $response['message'] = 'This reservation already exists';
        } else {
            // Start transaction to ensure data consistency
            mysqli_begin_transaction($conn);

            // Insert the reservation if it doesn't exist
            $stmt = $conn->prepare("INSERT INTO reservations (customers_id, reservation_date, start_date, end_date, location, room_type, num_people, special_requests, vehicle_license_plate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $customers_id, $reservation_date, $start_date, $end_date, $park_location, $room_type, $num_people, $special_requests, $license_plate);            

            if ($stmt->execute()) {
                // Get the ID of the newly inserted reservation
                $reservation_id = $stmt->insert_id;

                // Insert a record into the plates table with the reservation_id
                $stmt2 = $conn->prepare("INSERT INTO plates (customer_id, reservation_id, plate, active) VALUES (?, ?, ?, false)");
                $stmt2->bind_param("sss", $customers_id, $reservation_id, $license_plate);
                
                if ($stmt2->execute()) {
                    // Commit the transaction if both inserts are successful
                    mysqli_commit($conn);
                    $response['success'] = true;
                    $response['message'] = 'Reservation and plate record added successfully';
                } else {
                    // Rollback the transaction if plate insertion fails
                    mysqli_rollback($conn);
                    $response['success'] = false;
                    $response['message'] = 'Failed to add plate record: ' . $stmt2->error;
                }
            } else {
                // Rollback the transaction if reservation insertion fails
                mysqli_rollback($conn);
                $response['success'] = false;
                $response['message'] = 'Reservation failed. ' . $stmt->error;
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Unable to check the reservation';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
