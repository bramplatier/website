<?php
$base_url = 'https://' . $_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION['id'])) {
    header("Location: " . $base_url . "/reservation/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reservation</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/reservation.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
<main>
        <div class="left-side"></div>

        <div class="right-side">
            <h2>Reservation</h2><br>
            <form id="reservationForm" method="post">

                <div>
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required><br>
                </div>
                
                <div>
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required><br>
                </div>

                <div>
                    <label for="park_location">Vacation park</label>
                    <select id="park_location" name="park_location" required>
                        <option value="Nederland">Netherlands</option>
                        <option value="Belgie">Belgium</option>
                        <option value="Duitsland">Germany</option>
                    </select><br><br>
                </div>

                <div>
                    <label for="room_type">Room Type</label>
                    <select id="room_type" name="room_type" required>
                        <option value="basic">Basic</option>
                        <option value="luxury">Luxury</option>
                    </select><br><br>
                </div>

                <div>
                    <label for="num_people">Number of People</label>
                    <input type="number" id="num_people" name="num_people" min="1" max="8" required><br>
                </div>

                <div>
                    <label for="license_plate">License plate</label>
                    <input type="text" id="license_plate" name="license_plate"><br>
                </div>

                <div>
                    <label for="special_requests">Special Requests</label>
                    <input id="special_requests" name="special_requests"><br>
                </div>

                <button type="submit" name="submit" class="login-btn">Submit Reservation</button>
                <div class="links">
                    <a href="logout.php">Logout</a>
                </div>
            </form>
        </div>

    </main>

    <script>
        document.getElementById("reservationForm").addEventListener("submit", async function(event) {
            event.preventDefault();

            const form = event.target;
            if (!form.checkValidity()) {
                alert("Please fill out all required fields.");
                return;
            }

            const formData = new FormData(form);
            formData.append('id', <?php echo json_encode($_SESSION['id']); ?>);

            const response = await fetch('/reservation/includes/process_reservation.php', { // Vervang dit door de URL van je API
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                alert("An error occurred while submitting the reservation.");
                return;
            }

            const result = await response.json();
            if (result.success) {
                alert("Reservation submitted successfully!");
                window.location.href = "succes.html";
            } else {
                alert("Failed to submit reservation: " + result.message);
            }
        });
    </script>
</body>
</html>
