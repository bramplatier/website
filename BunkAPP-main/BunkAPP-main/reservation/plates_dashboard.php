
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
                <a href="dashboard.php"><button class="logout-button">Reservations</button></a>
                <a href="logout.php"><button class="logout-button">Logout</button></a>
            </div>
        </header>
        <main>
        <section class="reservation-dashboard">
            <h2>Plates dashboard</h2>
            <table>
                <thead>
                    <tr>
                        <th>Customer id</th>
                        <th>Name</th>
                        <th>Reservation id</th>
                        <th>Plate id</th>
                        <th>License plate</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include("includes/plates_dashboard.api.php");
                    $data = json_decode(GetData(), true);
                    if ($data === NULL) {
                        echo '<tr><td colspan="7">Er is een fout opgetreden bij het decoderen van de JSON-gegevens.</td></tr>';
                    } elseif (empty($data)) {
                        echo '<tr><td colspan="7">Geen gegevens beschikbaar.</td></tr>';
                    } else {
                        foreach ($data as $entry) {
                            echo '<tr>';
                            echo '<td>' . $entry['customer_id'] . '</td>';
                            echo '<td>' . $entry['name'] . '</td>';
                            echo '<td>' . $entry['reservation_id'] . '</td>';
                            echo '<td>' . $entry['plate_id'] . '</td>';
                            echo '<td>' . $entry['license_plate'] . '</td>';
                            echo '<td>' . $entry['active'] . '</td>';
                            echo '<td><button class="logout-button id="btn" onclick="removePlate(event, ' . $entry['plate_id'] . ')">Delete</button></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>


            <form id="chartForm" action="includes/plates_dashboard.api.php?action=remove" method="post">
                <input type="hidden" name="plate_id" id="plateIdInput">
            </form>

        </main>
    </div>

    <script>
        function removePlate(event, plateId) {
            // Stop het standaardgedrag van het formulier
            event.preventDefault();

            // Maak de data die je wilt verzenden
            let data = new FormData();
            data.append('plate_id', plateId);

            // Verstuur het verzoek
            fetch('includes/plates_dashboard.api.php?action=remove', {
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
    </script>

</body>

</html>
