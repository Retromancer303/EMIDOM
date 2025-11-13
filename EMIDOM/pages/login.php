<?php
// Initialize the session
session_start();

require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";


// Count failed login attempts for a username in the last 10 minutes
function count_failed_attempts($mysqli, $username) {
    $sql = "SELECT COUNT(*) FROM login_attempts 
            WHERE username = ? 
            AND attempt_time > (NOW() - INTERVAL 10 MINUTE)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);

    $count = 0; // Initialize to avoid unassigned variable warning
    if ($stmt->execute()) {
        $stmt->bind_result($count);
        $stmt->fetch();
    }
    $stmt->close();
    return $count;
}

// Log a failed login attempt
function log_failed_attempt($mysqli, $username) {
    $sql = "INSERT INTO login_attempts (username, ip_address, attempt_time) VALUES (?, ?, NOW())";
    $stmt = $mysqli->prepare($sql);
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt->bind_param("ss", $username, $ip);
    $stmt->execute();
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials if no input errors
    if (empty($username_err) && empty($password_err)) {

        // Check rate limit
        $failed_attempts = count_failed_attempts($mysqli, $username);
        if ($failed_attempts >= 5) {
            $login_err = "Too many failed login attempts. Please try again after 10 minutes.";
        } else {
            // Prepare statement to get user info
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("s", $param_username);
                $param_username = $username;

                if ($stmt->execute()) {
                    $stmt->store_result();

                    if ($stmt->num_rows == 1) {
                        $stmt->bind_result($id, $username, $hashed_password);
                        if ($stmt->fetch()) {
                            // Verify password
                            if (password_verify($password, $hashed_password)) {
                                // Password correct: login success
                                session_start();

                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                // Clear failed attempts
                                $mysqli->query("DELETE FROM login_attempts WHERE username = '$username'");

                                header("location: ../index.php");
                                exit;
                            } else {
                                // Wrong password: log attempt
                                log_failed_attempt($mysqli, $username);
                                $login_err = "Invalid username or password.";
                            }
                        }
                    } else {
                        // Username doesn't exist: log attempt
                        log_failed_attempt($mysqli, $username);
                        $login_err = "Invalid username or password.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $stmt->close();
            }
        }
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMIDOM</title>
    <link rel="stylesheet" href="../css/loginstyle.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/script.js"></script>
</head>
<body class="loginbody">
    <!--navigation bar-->
    <nav class="nav">
        <ul class="feature-logo">
            <li class="feature-home"><a href="../index.php"><img class="logo" src="../images/logo.png" alt=""></a></li>
        </ul>    
        <ul class="feature-item">                
            <li class="features"><a href="search.php">Search<img src="../images/search.svg" alt=""></a></li>
        </ul>
    </nav>
    <div class="loginWrapper">
        <!--login box-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Login</h1>

            <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
            ?>

            <div class="input-box">
                <input type="text" name ="username" placeholder="Username" required <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>

            <!--<div class="remember-forgot">
                <label><input type="checkbox">Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>-->

            <button type="submit" class="btn">Login</button>

            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </div>
        </form>
    </div>
        <footer>
            <p>© 2024 EMIDOM</p>
        </footer>
    </div>
</body>
</html>
