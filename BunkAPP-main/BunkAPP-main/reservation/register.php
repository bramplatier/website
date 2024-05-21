<?php
$base_url = 'https://' . $_SERVER['HTTP_HOST'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User registration</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/register.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
<main>
        <div class="left-side"></div>

        <div class="right-side">
            <form name="loginForm" id='registrationForm'  onsubmit="submitForm(event)">
                <div class="btn-group">
                    <button class="btn">
                        <img class="logo" src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/d1c98974-c62d-4071-8bd2-ab859fc5f4e9" alt="" />
                        <span>Sign up with Google</span>
                    </button>
                    <button class="btn">
                        <img class="logo" src="https://github.com/ecemgo/mini-samples-great-tricks/assets/13468728/59c1561b-8152-4d05-b617-0680a7629a0e" alt="" />
                        <span>Sign up with Apple</span>
                    </button>
                </div>

                <div class="or">OR</div>

                <label for="name">Name</label>
                <input type="text" id="name" placeholder="Enter name" name="name" required/>

                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Enter Email" name="email" required/>

                <label for="name">Phone number (06-)</label>
                <input type="text" id="phone_number" placeholder="Enter Phone number" name="phone_number" required/>

                <label for="password">Password</label>
                <input type="password" id= "password" placeholder="Enter Password" name="password" required/>

                <button type="submit" name="submit" class="login-btn">Register</button>

                <div class="links">
                    <a href="https://fonteyn-vakantieparken.nl">Back to homepage</a>
                    <p><a href="login.php">Already have an account?</a></p>
                </div>
            </form>
        </div>

    </main>

    <script>
        const base_url = '<?php echo $base_url; ?>';

        async function submitForm(event) {
            event.preventDefault();

            const form = document.getElementById('registrationForm');
            const formData = new FormData(form);

            try {
                const response = await fetch(base_url + '/reservation/includes/register.api.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    alert('Registration successful!');
                    window.location.href = base_url + '/reservation/login.php';
                } else {
                    alert('Registration failed: ' + result.message);
                }
            } catch (error) {
                console.error('Error during registration:', error);
                alert('An error occurred during registration.');
            }
        }
    </script>

</body>

</html>

