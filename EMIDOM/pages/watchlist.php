<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watchlist</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="watchlistBody">
    <div class="watchlistWrapper">
        <!-- Navigation bar -->
        <nav class="nav">
            <ul class="feature-logo">
                <li class="feature-home"><a href="../index.php"><img class="logo" src="../images/logo.png" alt=""></a>
                </li>
            </ul>
            <ul class="feature-item">
                <li class="features"><a href="watchlist.php">Watchlist<img src="../images/bookmark.svg" alt=""></a></li>
                <li class="features"><a href="profile.php">Profile<img src="../images/user.svg" alt=""></a></li>
                <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li class="features"><a href="logout.php">Logout<img src="../images/login.svg" alt=""></a></li>
                <?php else: ?>
                    <li class="features"><a href="login.php">Login<img src="../images/login.svg" alt=""></a></li>
                <?php endif; ?>
            </ul>
        </nav>

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

        // Fetch anime IDs from the watchlist table for the current user
        $user_id = $_SESSION["id"];
        $sql = "SELECT anime_id FROM watchlist WHERE user_id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind user ID parameter
            $stmt->bind_param("i", $user_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Bind result variables
                $stmt->bind_result($anime_id);

                // Fetch anime IDs into an array
                $anime_ids = array();
                while ($stmt->fetch()) {
                    $anime_ids[] = $anime_id;
                }

                // Close statement
                $stmt->close();
            } else {
                echo "Oops! Something went wrong while fetching anime IDs.";
            }
        } else {
            echo "Error preparing SQL statement: " . $mysqli->error;
        }

        // Function to fetch anime details including the image URL
        // Function to fetch anime details including the image URL// Function to fetch anime details including the image URL
        function fetchAnimeDetails($anime_id)
        {
            // API endpoint URL
            $url = 'https://myanimelist.p.rapidapi.com/anime/' . $anime_id;

            // API request headers
            $headers = array(
                'X-RapidAPI-Key: c574e600e0mshf9b43d5d6846017p1d76ffjsnbf4083bfc589',
                'X-RapidAPI-Host: myanimelist.p.rapidapi.com'
            );

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers
            ]);

            // Execute the cURL request
            $response = curl_exec($ch);

            // Close cURL session
            curl_close($ch);

            // Decode the JSON response
            return json_decode($response, true);
        }

        // Loop through each anime ID and generate the HTML for its image
        $imageHTML = '';
        foreach ($anime_ids as $anime_id) {
            // Fetch anime details
            $animeDetails = fetchAnimeDetails($anime_id);

            // Check if the request was successful
            if ($animeDetails && isset($animeDetails['picture_url']) && isset($animeDetails['title_ov'])) {
                $imageUrl = $animeDetails['picture_url'];
                $animeTitle = $animeDetails['title_ov'];

                // Generate HTML for the image with a CSS class
                $imageHTML .= '<figure>
                                <img class="anime-image" src="' . $imageUrl . '" alt="Anime Image">
                                <figcaption class="title">' . $animeTitle . '</figcaption>
                                </figure>';
            } else {
                $imageHTML .= 'Error: Unable to fetch anime information for ID ' . $anime_id;
                $animeTitle .= 'Error: Unable to fetch anime information for ID ' . $anime_id;
            }
        }
        ?>
        <div class="watchlist-container">
            <?php echo $imageHTML; ?>
        </div>

        <footer>
            <p>© 2024 EMIDOM</p>
        </footer>
    </div>
</body>

</html>