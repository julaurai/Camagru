<?php
// header("Location: ../../index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   
    <link rel="stylesheet" type="text/css" href="public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

    <title>Camagru</title>
</head>
<body class="site">
<nav id="navbar" class="navbar" role="navigation">
    <div class="navbar-brand">
        <a href="index.php" class="navbar-item">
            <img src="../images/logo/logo.png"></img>Camagru
        </a>
            <div class="navbar-item">
                <?php if (isset($_SESSION['login'])) { ?>
                <a class="button is-danger" href="pages/logout.php">Deconnexion</a>
                <?php } ?>
            </div>
            <a id="burger" role="button" class="navbar-burger" aria-label="menu" aria-expanded="false">
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
            </a>
    </div>
    <div id="menu" class="navbar-menu">
            <div class="navbar-end">
                <div class="navbar-item">
                    <div class="buttons">
                        <?php if ($title !== 'Webcam'){ ?>
                            <div class="navbar-item">
                                <a class="button is-primary" href="index.php?p=webcam">New post</a>
                            </div>
                        <?php } ?>
                        <?php if ($title !== 'Gallery') { ?>
                            <a class="button" href="index.php">Gallery</a>
                        <?php } ?>
                        <?php if (isset($_SESSION['login'])) { ?>
                            <?php if ($title !== 'Posts') { ?>
                            <a class="button" href="index.php?p=posts">My posts</a>
                            <?php } ?>
                            <?php if ($title !== 'Profil') { ?>
                            <a class="button" href="index.php?p=profil">Account</a>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($title !== 'Signin') { ?>
                            <a class="button" href="index.php?p=signin">Login</a>
                            <?php } ?>
                            <?php if ($title !== 'Signup') { ?>
                            <a class="button" href="index.php?p=signup">Signup</a>
                            <?php } ?>
                        <?php } ?>  
                    </div>
                </div>
            </div>
        </div>
    </nav>
</nav>
    <div class="container">
        <div class='content'>
         <?=$content?>
        </div>
    </div>
    <div class="block">
        <div class='footer' style="background-color: rgb(255,255,255)">
            <hr>
            <p>created by julaurai</p>
            <hr>
        </div>
    </div>
</body>
</html>

<script>
document.addEventListener('click', function (event) {
	if (event.target.matches('#burger')) {
        const menu = document.getElementById('menu');
        const burger = document.getElementById('burger');
        menu.classList.toggle('is-active');
        burger.classList.toggle('is-active');
	}
}, false);
</script>
