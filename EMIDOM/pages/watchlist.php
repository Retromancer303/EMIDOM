<?php
session_start();

// Include config file
require_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch anime IDs from the watchlist table for the current user
$user_id = $_SESSION["id"];
$sql = "SELECT anime_id FROM watchlist WHERE user_id = ?";
$anime_ids = [];

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $stmt->store_result();
        $stmt->bind_result($anime_id);
        while ($stmt->fetch()) {
            $anime_ids[] = $anime_id;
        }
    } else {
        echo "Oops! Something went wrong while fetching anime IDs.";
    }
    $stmt->close();
} else {
    echo "Error preparing SQL statement: " . $mysqli->error;
}

// Function to fetch anime details using v2 API
function fetchAnimeDetails($anime_id)
{
    if ($anime_id <= 0) return false;

    $url = 'https://myanimelist.p.rapidapi.com/v2/anime/' . $anime_id;
    $headers = [
        'x-rapidapi-key: d2aa070660msh67c1d1e950a983dp1ae9e3jsnd3ac570aca0b',
        'x-rapidapi-host: myanimelist.p.rapidapi.com'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $httpCode !== 200) {
        return false;
    }

    $result = json_decode($response, true);
    if (!isset($result['data']['node'])) return false;

    return $result;
}

// Generate HTML for the watchlist
$imageHTML = '';
foreach ($anime_ids as $anime_id) {
    $animeDetails = fetchAnimeDetails($anime_id);

    if ($animeDetails) {
        $node = $animeDetails['data']['node'];
        $animeTitle = $node['title'] ?? 'Unknown Title';
        $imageUrl = $node['main_picture']['medium'] ?? '';

        if ($imageUrl) {
            $imageHTML .= '<figure>
                            <img class="anime-image" src="' . $imageUrl . '" alt="' . htmlspecialchars($animeTitle) . '">
                            <figcaption class="title">' . htmlspecialchars($animeTitle) . '</figcaption>
                          </figure>';
        } else {
            $imageHTML .= '<figure>
                            <div class="anime-error">Anime ID ' . $anime_id . ' has no image.</div>
                          </figure>';
        }
    } else {
        $imageHTML .= '<figure>
                        <div class="anime-error">Anime ID ' . $anime_id . ' could not be fetched.</div>
                      </figure>';
    }
}
?>

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
        <nav class="nav">
            <ul class="feature-logo">
                <li class="feature-home"><a href="../index.php"><img class="logo" src="../images/logo.png" alt=""></a></li>
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

        <div class="watchlist-container">
            <?php echo $imageHTML; ?>
        </div>

        <footer>
            <p>© 2025 EMIDOM</p>
        </footer>
    </div>
</body>
</html>