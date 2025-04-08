<?php
session_start();

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_dbname";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'"; // In real app, hash passwords!
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['email'] = $email;
        header("Location: welcome.php"); // Redirect to welcome page
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}


// Handle registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // In real app, hash passwords!

    // Check if email already exists
    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_sql);
    if($check_email_result->num_rows > 0) {
        $error_message_reg = "Email already exists. Please login.";
    } else {

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')"; // In real app, hash passwords!

        if ($conn->query($sql) === TRUE) {
           $success_message_reg = "Registration successful. Please login.";
        } else {
            $error_message_reg = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login/Registration</title>
</head>
<body>

<h2>Login</h2>
<?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" name="login" value="Login">
</form>

<h2>Register</h2>
<?php if (isset($error_message_reg)) { echo "<p style='color:red;'>$error_message_reg</p>"; } ?>
<?php if (isset($success_message_reg)) { echo "<p style='color:green;'>$success_message_reg</p>"; } ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" name="register" value="Register">
</form>

</body>
</html>

<?php
$conn->close();
?>