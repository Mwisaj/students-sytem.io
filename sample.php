<!-- login.php -->
<form method="POST" action="authenticate.php">
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <button type="submit" name="submit">Login</button>
</form>
<?php
// Start session
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "student");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get email and password from the POST request
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];  // Password is entered in plain text

    // Query to find the user with the provided email
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify the password using password_verify() (assuming password is hashed)
        if (password_verify($password, $user['password'])) {
            // Set session variables upon successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Redirect to a protected page (e.g., dashboard.php)
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            echo "Incorrect password.";
        }
    } else {
        // No user found with the given email
        echo "No user found with this email.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
mysqli_close($conn);
?>
<?php
// Start session
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// User is authenticated, display protected content
echo "Welcome, " . $_SESSION['email'] . "! You are logged in.";
?>

<!-- Optionally, add a logout button -->
<a href="logout.php">Logout</a>
<?php
// Start session
session_start();

// Destroy session and redirect to login page
session_destroy();
header("Location: login.php");
exit();
?>
