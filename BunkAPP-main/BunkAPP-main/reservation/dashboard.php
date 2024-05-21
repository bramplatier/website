<?php
$base_url = 'https://' . $_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION['id'])) {
    header("Location: " . $base_url . "/reservation/dashboard_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/dashboard.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Dashboard</h1>
            <div class="logout-container">
                <a href="#" onclick="refreshStatus(); return false;"><button class="logout-button">Refresh status</button></a>
                <a href="plates_dashboard.php"><button class="logout-button">Plates</button></a>
                <a href="logout.php"><button class="logout-button">Logout</button></a>
            </div>
        </header>
        <main>
        <section class="reservation-dashboard">
            <h2>Reservation dashboard</h2>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>People</th>
                        <th>Special</th>
                        <th>Status</th>
                        <th>License plate</th>
                        <th>Remove</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("includes/dashboard.api.php");
                    $data = json_decode(GetData(), true);

                    if ($data === NULL) {
                        echo '<tr><td colspan="11">Er is een fout opgetreden bij het decoderen van de JSON-gegevens.</td></tr>';
                    } elseif (empty($data)) {
                        echo '<tr><td colspan="11">Geen gegevens beschikbaar.</td></tr>';
                    } else {
                        foreach ($data as $entry) {
                            echo '<tr>';
                            echo '<td>' . $entry['reservation_id'] . '</td>';
                            echo '<td>' . $entry['name'] . '</td>';
                            echo '<td>' . $entry['start_date'] . '</td>';
                            echo '<td>' . $entry['end_date'] . '</td>';
                            echo '<td>' . $entry['location'] . '</td>';
                            echo '<td>' . $entry['room_type'] . '</td>';
                            echo '<td>' . $entry['num_people'] . '</td>';
                            echo '<td>' . $entry['special_requests'] . '</td>'; 
                            echo '<td>' . $entry['status'] . '</td>';
                            echo '<td>' . $entry['vehicle_license_plate'] . '</td>';
                            echo '<td><button class="logout-button" id="btn" onclick="removeData(event, ' . $entry['reservation_id'] . ')">Delete</button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>


            <form id="chartForm" action="includes/dashboard.api.php?action=remove" method="post">
                <input type="hidden" name="reservation_id" id="reservationIdInput">
            </form>

        </main>
    </div>

    <script>
        function removeData(event, reservationId) {
            // Stop het standaardgedrag van het formulier
            event.preventDefault();

            // Maak de data die je wilt verzenden
            let data = new FormData();
            data.append('reservation_id', reservationId);

            // Verstuur het verzoek
            fetch('includes/dashboard.api.php?action=remove', {
                method: 'POST',
                body: data
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Netwerk antwoord was niet ok.');
                }
                return response.text();
            }).then(data => {
                // Hier kun je iets doen met het antwoord van de server
                console.log('Data:', data);

                // Vernieuw de pagina
                location.href = location.href;
            }).catch(error => {
                // Hier kun je iets doen met de fout
                console.error('Er is een fout opgetreden:', error);
            });
        }

        function refreshStatus() {
        // Maak een XMLHttpRequest-object
        var xhttp = new XMLHttpRequest();

        // Configureer het verzoek
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Verwerk het antwoord indien nodig
                console.log(this.responseText);
                // Hier kun je extra logica toevoegen om het antwoord te verwerken
            }
        };

        // Verstuur het verzoek met behulp van GET-methode naar de opgegeven URL
        xhttp.open("GET", "includes/autoactivate.api.php", true);
        xhttp.send();

        location.href = location.href;
    }
    </script>

</body>

</html>
