<?php
//Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
#if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    #header("location: login.php");
    #exit;
#}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EMIDOM</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="./js/script.js"></script>

</head>

<body class="indexPage">
  <div class="indexWrapper">
    <!--navigation bar-->
    
    <nav class="nav">
        <ul class="feature-logo">
            <li class="feature-home"><a href="index.php"><img class="logo" src="./images/logo.png" alt=""></a></li>
        </ul>             
        <ul class="feature-item">
          <li class="features"><a href="./pages/watchlist.php">Watchlist<img src="./images/bookmark.svg" alt=""></a></li>
          <li class="features"><a href="./pages/profile.php">Profile<img src="./images/user.svg" alt=""></a></li>
          <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <li class="features"><a href="./pages/logout.php">Logout<img src="./images/login.svg" alt=""></a></li>
          <?php else: ?>
            <li class="features"><a href="./pages/login.php">Login<img src="./images/login.svg" alt=""></a></li>
          <?php endif; ?>
        </ul>
    </nav> 


    <div class="container">
      <h1>TOP ANIME</h1>
      <div class="slider-wrapper">
        <button id="prev-slide" class="slide-button material-symbols-rounded">
          
        </button>
        <ul class="image-list">
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
          <img class="image-item" src="" alt="" />
        </ul>
        <button id="next-slide" class="slide-button material-symbols-rounded">
          
        </button>
      </div>
      <div class="slider-scrollbar">
        <div class="scrollbar-track">
          <div class="scrollbar-thumb"></div>
        </div>
      </div>
      <h1>TOP MANGA</h1>
      <div class="manga-slider-wrapper">
        <button id="manga-prev-slide" class="manga-slide-button material-symbols-rounded">
          
        </button>
        <ul class="manga-image-list">
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
          <img class="manga-image-item" src="" alt="" />
        </ul>
        <button id="manga-next-slide" class="manga-slide-button material-symbols-rounded">
          
        </button>
      </div>
      <div class="manga-slider-scrollbar">
        <div class="manga-scrollbar-track">
          <div class="manga-scrollbar-thumb"></div>
        </div>
      </div>

      <h1>TV NEW</h1>
      <div class="tvNew-slider-wrapper">
        <button id="tvNew-prev-slide" class="tvNew-slide-button material-symbols-rounded">
          
        </button>
        <ul class="tvNew-image-list">
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
          <img class="tvNew-image-item" src="" alt="" />
        </ul>
        <button id="tvNew-next-slide" class="tvNew-slide-button material-symbols-rounded">
          
        </button>
      </div>
      <div class="tvNew-slider-scrollbar">
        <div class="tvNew-scrollbar-track">
          <div class="tvNew-scrollbar-thumb"></div>
        </div>
      </div>

    </div>

    <footer>
      <p>© 2024 EMIDOM</p>
    </footer>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  </body>

</html>