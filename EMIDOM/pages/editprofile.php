<?php
// Start session
session_start();

// Assuming you have already established a database connection
require "config.php";

// Initialize success message variable
$success_message = "";

// Function to validate username
function isValidUsername($username) {
    // Check for invalid characters (only allow alphanumeric characters and underscores)
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        return "Username contains invalid characters. Only letters, numbers, and underscores are allowed.";
    }
    // Check for length limit
    if (strlen($username) > 250) {
        return "Username cannot exceed 250 characters.";
    }
    return true;
}

// Function to validate bio
function isValidBio($bio) {
    if (strlen($bio) > 500) {
        return "Bio cannot exceed 500 characters.";
    }
    return true;
}

// Function to check file size with a limit of 20 MB
function isValidFileSize($file) {
    $maxSize = 2 * 1024 * 1024; // 2 MB in bytes
    if ($file['size'] > $maxSize) {
        return false;
    }
    return true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null; // User ID from session
    $username = $_POST['username'] ?? '';
    $bio = $_POST['bio'] ?? '';

        // Handle profile picture upload
        if (!empty($_FILES['profilePicture']['name'])) {
            if (!isValidFileSize($_FILES['profilePicture'])) {
                echo "Profile picture must not exceed 2 MB.";
                exit;
            }
            $profilePicture = $_FILES['profilePicture']['name'];
            $profilePicture_tmp = $_FILES['profilePicture']['tmp_name'];
            $profilePicture_path = "profile_pictures/" . $profilePicture;
    
            if (!move_uploaded_file($profilePicture_tmp, $profilePicture_path)) {
                echo "Error uploading profile picture.";
                exit;
            }
        } else {
            $profilePicture_path = ''; // No profile picture uploaded
        }
    
        // Handle wallpaper upload
        if (!empty($_FILES['wallpaper']['name'])) {
            if (!isValidFileSize($_FILES['wallpaper'])) {
                echo "Wallpaper must not exceed 2 MB.";
                exit;
            }
            $wallpaper = $_FILES['wallpaper']['name'];
            $wallpaper_tmp = $_FILES['wallpaper']['tmp_name'];
            $wallpaper_path = "wallpapers/" . $wallpaper;
    
            if (!move_uploaded_file($wallpaper_tmp, $wallpaper_path)) {
                echo "Error uploading wallpaper.";
                exit;
            }
        } else {
            $wallpaper_path = ''; // No wallpaper uploaded
        }

    // Validate username
    $usernameValidation = isValidUsername($username);
    if ($usernameValidation !== true) {
        echo $usernameValidation;
        exit;
    }

    // Validate bio
    $bioValidation = isValidBio($bio);
    if ($bioValidation !== true) {
        echo $bioValidation;
        exit;
    }
    // Check if required fields are empty
    if (empty($userId) || empty($username) || empty($bio)) {
        echo "User ID, username, and bio are required.";
        exit;
    }

    // Handle profile picture upload
    if (!empty($_FILES['profilePicture']['name'])) {
        $profilePicture = $_FILES['profilePicture']['name'];
        $profilePicture_tmp = $_FILES['profilePicture']['tmp_name'];
        $profilePicture_path = "profile_pictures/" . $profilePicture; // Path where the uploaded picture will be stored on the server

        if (!move_uploaded_file($profilePicture_tmp, $profilePicture_path)) {
            echo "Error uploading profile picture.";
            exit;
        }
    } else {
        $profilePicture_path = ''; // No profile picture uploaded
    }

    // Handle wallpaper upload
    if (!empty($_FILES['wallpaper']['name'])) {
        $wallpaper = $_FILES['wallpaper']['name'];
        $wallpaper_tmp = $_FILES['wallpaper']['tmp_name'];
        $wallpaper_path = "wallpapers/" . $wallpaper; // Path where the uploaded wallpaper will be stored on the server

        if (!move_uploaded_file($wallpaper_tmp, $wallpaper_path)) {
            echo "Error uploading wallpaper.";
            exit;
        }
    } else {
        $wallpaper_path = ''; // No wallpaper uploaded
    }

    // Update user details in the database
    $sql = "UPDATE users SET username=?, bio=?, profile_picture=?, wallpaper=? WHERE id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssssi", $username, $bio, $profilePicture_path, $wallpaper_path, $userId);

    if ($stmt->execute()) {
        $success_message = "Details updated successfully. Wallpaper updated successfully.";
    } else {
        echo "Error updating details: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="../css/editstyle.css">
</head>
<body class="editbody">

    <nav class="nav">
        <ul class="feature-logo">
            <li class="feature-home"><a href="../index.php"><img class="logo" src="../images/logo.png" alt=""></a></li>
        </ul>             
        <ul class="feature-item">
        <li class="features"><a href="watchlist.php">Watchlist<img src="../images/bookmark.svg" alt=""></a></li>
        <li class="features"><a href="profile.php">Profile<img src="../images/user.svg" alt=""></a></li>
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <li class="features"><a href="logout.php">Logout<img src="../images/login.svg" alt=""></a></li>
        <?php else: ?>
            <li class="features"><a href="login.php">Login<img src="../images/login.svg" alt=""></a></li>
        <?php endif; ?>
        </ul>
    </nav>  

    <div class="editWrapper">
        <h1>Edit Profile</h1>
        
        <?php if (!empty($error_message)) : ?>
            <div class="error-container">
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)) : ?>
            <div class="success-container">
                <div class="alert-success"><?php echo $success_message; ?></div>
            </div>
        <?php endif; ?>

        <form method="post" action="editprofile.php" enctype="multipart/form-data">

            <div class="input-box">
                <input type="text" name ="username" placeholder="Username" required>
            </div>

            <div class="input-box2">
                <textarea id="bio" name="bio" rows="4" cols="50" placeholder="Bio"></textarea>
            </div>

            <div class="container">
                <label for="profilePicture" class="file-upload-btn">Choose Profile Picture</label>
                <input type="file" id="profilePicture" name="profilePicture" style="display: none;">
            </div>

            <div class="container">
                <label for="wallpaper" class="file-upload-btn">Choose Wallpaper</label>
                <input type="file" id="wallpaper" name="wallpaper" style="display: none;">
            </div>

            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>

    <footer>
        <p>© 2024 EMIDOM  ||  <a href="#">Privacy Policy</a>  ||  <a href="#">Terms and Conditions</a></p>
    </footer>
</body>
</html>
