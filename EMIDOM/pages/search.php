<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../js/script.js"></script>

</head>
<body class="searchPage">
    <div class="wrapper">
        <!--navigation bar-->
        <nav class="nav">
            <ul class="feature-logo">
                <li class="feature-home"><a href="../index.php"><img class="logo" src="../images/logo.png" alt=""></a></li>
            </ul>             
            <ul class="feature-item">
              <li class="features"><a href="watchlist.php">Watchlist<img src="../images/bookmark.svg" alt=""></a></li>
              <li class="features"><a href="search.php">Search<img src="../images/search.svg" alt=""></a></li>
              <li class="features"><a href="profile.php">Profile<img src="../images/user.svg" alt=""></a></li>
              <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                <li class="features"><a href="logout.php">Logout<img src="../images/login.svg" alt=""></a></li>
              <?php else: ?>
                <li class="features"><a href="login.php">Login<img src="../images/login.svg" alt=""></a></li>
              <?php endif; ?>
            </ul>
        </nav>  
        <div class="search">
            <input type="text" id="searchInput" placeholder="Enter anime name...">
            <button onclick="">Search</button>
        </div>
        <div id="searchResults" class="results-container"></div>
        
        <footer>
            <p>© 2024 EMIDOM</p>
        </footer>
    </div>
</body>
</html>