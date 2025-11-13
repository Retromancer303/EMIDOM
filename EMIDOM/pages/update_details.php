<?php
// Start session
session_start();

// Assuming you have already established a database connection
require "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null; // User ID from session
    $username = $_POST['username'] ?? '';
    $bio = $_POST['bio'] ?? '';

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
        echo "Details updated successfully. Wallpaper updated successfully.";
    } else {
        echo "Error updating details: " . $stmt->error;
    }

    $stmt->close();
}
?>

