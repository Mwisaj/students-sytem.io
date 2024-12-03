<?php
    $user_id = $message = $phone_or_email = FALSE;
    // Start session
    session_start();

    // Check if the user is logged in, if not, redirect to login page
    if (isset($_SESSION["logged_in"]) && isset($_SESSION["user_id"]) && isset($_SESSION["user_type"])) {
        if($_SESSION["user_type"] == "student") {
            header("Location: student/");
            exit();
        } else {
            header("Location: add_results/");
            exit();
        }
    }
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "student");
    
    if (!$conn) {
        die("Database connection failed");
    }
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        $phone_or_email = $_POST["email"];
        $entered_password = $_POST["password"];
        $phone_or_email = str_replace(" ", "", $phone_or_email);
        $phone_or_email = str_replace("-", "", $phone_or_email);
        $phone_or_email = str_replace(";", "", $phone_or_email);
        if(empty($phone_or_email) || empty($entered_password)) {
            $message = "All fields must be filled in";
        } else {
            if(filter_var($phone_or_email, FILTER_VALIDATE_EMAIL)) {
                $stmt = $conn->prepare("SELECT eid, email, password FROM student_details WHERE email = ?;");
                $stmt->bind_param("s", $phone_or_email);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                if($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row["eid"];
                        $password = $row["password"];
                        $user_type = "student";
                    }
                } else {
                    $stmt = $conn->prepare("SELECT id, email, password FROM lecturers WHERE email = ?;");
                    $stmt->bind_param("s", $phone_or_email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    if($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $user_id = $row["id"];
                            $password = $row["password"];
                            $user_type = "lecturer";
                        }
                    }
                }
                if($user_id) {

                    // Verify the password using password_verify() (assuming password is hashed)
                    if (/*password_verify($password, $user['password'])*/$entered_password == $password) {
                        // Set session variables upon successful login
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user_type'] = $user_type;
                        $_SESSION['logged_in'] = true;
            
                        if($user_type == "student") {
                            header("Location: student/");
                            exit();
                        } else {
                            header("Location: add_results/");
                            exit();
                        }
                    } else {
                        // Invalid password
                        $message = "Incorrect password";
                    }
                } else { 
                    $message = "No account uses the email address you entered";
                }
            } else { $message = "Invalid email address"; }
        }
    }
?>
<!DOCTYPE HTML>
<html lang="en-zm">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width,maximum-scale=1.0,initial-scale=1.0,minimum-scale=0.9,user-scalable=no" name="viewport">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
    <div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Tabs Titles -->
        <h2 class="active"> Sign In </h2>
        <h2 class="inactive underlineHover">Sign Up </h2>

        <!-- Icon -->
        <div class="fadeIn first">
        <img src="images/01.jpg" id="icon" alt="User Icon" />
        </div>

        <!-- Login Form -->
        <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" name="login" enctype="">
            <input type="text" id="login" class="fadeIn second" name="email" value="<?php echo $phone_or_email; ?>" placeholder="email" required>
            <input type="text" id="password" class="fadeIn third" name="password" placeholder="password" required>
            <p class="notif">
            <?php 
                echo $message;
            ?>
            </p>
            <input type="submit" class="fadeIn fourth" value="Log In">
            <br><br>
        </form>

        <!-- Remind Passowrd -->
        <div id="formFooter">
        <a class="underlineHover" href="#">Forgot Password?</a>
        </div>

    </div>
    </div>
  </body>
</html>