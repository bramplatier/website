<?php
$base_url = 'https://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/reservation');
session_start();

$api_url = $base_url . '/reservation/includes/dashboard_login.api.php';

// $response = json_decode($api_data_json, true);

if (isset($_SESSION['id'])) {
    header("Location: " . $base_url . "/reservation/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles/dashboard_login.css">
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <main>
        <div class="left-side">
            <h1>Welcome Back!</h1>
            <p>Please log in to your account.</p>
        </div>

        <div class="right-side">
            <form name="loginForm" id='' action='<?php echo $api_url; ?>' onsubmit="return isValid()" method="POST">

                <label for="email">Email</label>
                <input type="email" placeholder="Enter Email" name="email" required/>

                <label for="password">Password</label>
                <input type="password" placeholder="Enter Password" name="password" required />

                <button type="submit" value="Login" name="submit" class="login-btn">Log In</button>

                <?php
                if (isset($_GET['login_error']) && $_GET['login_error'] == 1) {
                    echo '<p class="error-message">Invalid login credentials. Please try again.</p>';
                }
                ?>

                <div class="links">
                    <a href="https://fonteyn-vakantieparken.nl">Back to homepage</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>
