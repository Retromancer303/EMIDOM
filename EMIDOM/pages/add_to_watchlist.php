<?php
session_start();

// Include config file
require_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}

// Your API URL and headers
$url = 'https://myanimelist.p.rapidapi.com/anime/top/%7Bcategory%7D';
$headers = array(
    'X-RapidAPI-Key: c574e600e0mshf9b43d5d6846017p1d76ffjsnbf4083bfc589',
    'X-RapidAPI-Host: myanimelist.p.rapidapi.com'
);

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    die('Error: ' . curl_error($curl));
}

// Close cURL session
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);

// Check if decoding was successful
if ($data === null) {
    die('Error: Unable to decode JSON response');
}

// Extract anime IDs from the response
$animeIds = array_column($data, 'myanimelist_id');

// Loop through each anime ID and insert into the watchlist table
foreach ($animeIds as $animeId) {
    // Prepare a SQL statement to insert anime ID into watchlist table
    $sql = "INSERT INTO watchlist (user_id, anime_id) VALUES (?, ?)";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ii", $param_user_id, $param_anime_id);

        // Set parameters
        $param_user_id = $_SESSION["id"]; // Get the user ID from session
        $param_anime_id = $animeId;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Anime added successfully
            echo "Anime ID: $animeId added to watchlist successfully.\n";
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$mysqli->close();
?>
