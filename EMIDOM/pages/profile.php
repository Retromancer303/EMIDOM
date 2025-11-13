<?php
// Initialize the session
session_start();

require "config.php";

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch user data from the database
$userId = $_SESSION['id'];
$sql = "SELECT username, bio, profile_picture, wallpaper FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

// Check if the user exists
if ($stmt->num_rows > 0) {
    // Bind result variables
    $stmt->bind_result($username, $bio, $profilePicture, $wallpaper);
    $stmt->fetch();

    // Set default bio if empty
    if (empty($bio)) {
        $bio = "This user has not provided a bio.";
    }

    // Set default profile picture if empty
    if (empty($profilePicture)) {
        $profilePicture = "profile_pictures/defaultProfilePicture.jpg";
    }

    // Set default wallpaper if empty
    if (empty($wallpaper)) {
        $wallpaper = "wallpapers/galaxy.jpg";
    }

} else {
    echo "User not found.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMIDOM</title>
    <link rel="stylesheet" href="../css/profilestyle.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/script.js"></script>
</head>
<body>
    
    <div class="profilewrapper">
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

        <section id="profile" style="background-image: url('<?php echo $wallpaper; ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            
            <div>
              <img src="<?php echo $profilePicture; ?>" alt="User Profile Picture">
            </div>
            <div id="profileText">
                <h1><?php echo $username; ?></h1>
                <p><?php echo $bio; ?></p>
            </div>
        </section>

        
        <button onclick="window.location.href = 'editprofile.php';" class="editprofilebutton">Edit Profile</button>
        
        <section id="recently-watched">
            <h2>_- Recently Watched -_</h2>
            <div class="recently-wrapper">
            </div>
        </section>
        
        <section id="friends-list">
            <h2>_-Friends List-_</h2>
            <div class="friends-container">
              <!-- List of friends -->
              <div class="friend-item">
                <img src="../images/friend1.jpg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>

              <div class="friend-item">
                <img src="../images/friend2.jpeg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>

              <div class="friend-item">
                <img src="../images/friend1.jpg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>
              <div class="friend-item">
                <img src="../images/friend2.jpeg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>
              <div class="friend-item">
                <img src="../images/friend1.jpg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>
              <div class="friend-item">
                <img src="../images/friend2.jpeg" alt="Friend's Profile" class="circle-image">
                <span>Username</span>
              </div>
              <!-- Add more friends here -->
            </div>
        </section>   
        <div>
          <footer>
              <p>© 2024 EMIDOM  ||  <a href="#">Privacy Policy</a>  ||  <a href="#">Terms and Conditions</a></p>
          </footer>
        </div>
    </div>

</body>
</html>
