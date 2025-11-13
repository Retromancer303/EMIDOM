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

// Check if the anime ID is provided in the URL
if (!isset($_GET["id"])) {
    // Handle case where anime ID is missing in the URL
    echo "Error: Anime ID is missing in the URL.";
    exit;
}

// Extract anime ID from the URL
$animeId = $_GET["id"];

// Check if the anime is already in the watchlist for the current user
$sql = "SELECT * FROM watchlist WHERE user_id = ? AND anime_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("ii", $param_user_id, $param_anime_id);

    // Set parameters
    $param_user_id = $_SESSION["id"]; // Get the user ID from session
    $param_anime_id = $animeId; // Use the anime ID extracted from the URL

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store the result
        $stmt->store_result();

        // Set the button text based on whether the anime is in the watchlist
        if ($stmt->num_rows > 0) {
            $buttonText = "Remove from Watchlist";
        } else {
            $buttonText = "Add to Watchlist";
        }

        // If the anime is already in the watchlist, delete it
        if ($stmt->num_rows > 0) {
            $deleteSql = "DELETE FROM watchlist WHERE user_id = ? AND anime_id = ?";
            if ($deleteStmt = $mysqli->prepare($deleteSql)) {
                // Bind variables to the prepared statement as parameters
                $deleteStmt->bind_param("ii", $param_user_id, $param_anime_id);

                // Attempt to execute the prepared statement
                if ($deleteStmt->execute()) {
                    // Anime removed successfully
                    // Optionally, you can echo a message here if you want
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $deleteStmt->close();
            }
        } else {
            // Anime is not in the watchlist, insert it
            // Prepare a SQL statement to insert anime ID into watchlist table
            $insertSql = "INSERT INTO watchlist (user_id, anime_id) VALUES (?, ?)";
            if ($insertStmt = $mysqli->prepare($insertSql)) {
                // Bind variables to the prepared statement as parameters
                $insertStmt->bind_param("ii", $param_user_id, $param_anime_id);

                // Attempt to execute the prepared statement
                if ($insertStmt->execute()) {
                    // Anime added successfully
                    // Optionally, you can echo a message here if you want
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                $insertStmt->close();
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
} else {
    // Handle case where SQL statement preparation fails
    echo "Error preparing SQL statement: " . $mysqli->error;
}

// Close connection
$mysqli->close();
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/script.js"></script>

</head>

<body class="anime-body">
    <div class="wrapper">
        <!--navigation bar-->
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

        <div class="display-container">
        <div class="anime-container">
            <h1 id="title_ov"></h1>
            <img id="picture_url" src="" alt=" ">

            <div class="anime-feature">
                <button class="watchlist-button" data-anime-id="<?php echo $animeId; ?>"><?php echo $buttonText; ?></button>
            </div>

            <h3>Synopsis</h3>
            <p id="synopsis"></p>
            
        </div>

        <div class="anime-sidebar">
            <ul>
                <li id="type">Type:</li>
                <li id="episodes">Episodes: </li>
                <li id="status">Status: </li>
                <li id="aired">Aired: </li>
                <li id="premiered">Premiered: </li>
                <li id="broadcast">Broadcast: </li>
                <li id="producers">Producers: </li>
                <li id="studios">Studios: </li>
                <li id="source">Source: </li>
                <li id="genres">Genres: </li>
                <li id="demographic">Demographic: </li>
                <li id="duration">Duration: </li>
                <li id="rating">Rating: </li>
            </ul>
        </div>
    </div>

        <footer>
            <p>© 2024 EMIDOM</p>
        </footer>
    </div>

    <script>
        // Get the current URL
        const url = window.location.href;

        // Extract digits from the URL using a regular expression
        const animeIdFromUrl = url.match(/\d+/);

        // Check if animeIdFromUrl is not null and get the first match
        const animeId = animeIdFromUrl ? animeIdFromUrl[0] : null;

        // Log the extracted anime ID
        console.log("Anime ID from URL:", animeId);

</script>


</body>

</html>