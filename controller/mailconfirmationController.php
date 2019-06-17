<?php
if (isset($_GET['log']) && isset($_GET['key']))
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/models/users.php';
    $login = $_GET['log'];
    $key = $_GET['key'];
    $pdo = new users;
    if ($pdo->tokencheck($login, $key)){ ?>
        <div class="column is-three-fifths is-offset-one-fifth"> 
            <article class="message is-success">
              <div class="message-header">
                <p>Compte activé avec succés connecte toi maintenant !</p>
              </div>
            </article>
        </div>
    <?php    
    }
    else
        header('Location: index.php');
}
else 
    if ($_SERVER['REQUEST_METHOD']) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}


