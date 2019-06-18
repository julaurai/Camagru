<?php
if (isset($_GET['uploaded'])){
    if ($_GET['uploaded'] === 'wrong'){
?>
<div class="column is-three-fifths is-offset-one-fifth">
    <div class="column is-three-fifths is-offset-one-fifth box">
      <div class="notification is-danger">
            <p class="nofication is-danger"><i class="fas fa-exclamation-triangle"></i>Mauvais fichier, uniquement png, jpeg, jpg, 10 Mo Maximum</p>
      </div>
            <p class="notification">Upload une nouvelle photo  <a class="button is-link"href="index.php?p=upload&upload"><i class="fas fa-upload"></i></a></p>
    </div>
</div>

<?php
    } else if ($_GET['uploaded'] === 'success') {
    $src = $_SESSION['pic'];
?> 
    <?php
if (!(isset($_SESSION['login']))){
    header("Location: index.php?p=signin");
} else { ?>


<link rel="stylesheet" type="text/css" href="public/css/webcam.css">

<div class="tile is-ancestor">
  <div class="tile is-parent is-vertical">
           <div class="tile is-child box">
            <div class="tile is-parent">
               <div class="tile is-child is-vertical is-9">
                  <article id="montage" style="position:relative; width:fit-content; height:fit-content">
                    <div id="overlay">
                    </div>
                    <img id="picture" style="position:relative; height:480px; width: 640px;" src="<?=htmlspecialchars($src)?>"></img>
                  </article>
               </div>
               <div class="tile is-child is vertical" id="customSticker">

                      <p class="notification is-info">Positionne ton sticker</p>
                        <hr>
                        <button id="startbutton" class="button is-primary is-large" disabled><i class="fas fa-camera"></i></button>
                        <hr>
                      <div class="tile">
                        <div class="tile is-vertical is-child">
                          <div id="moins" class="button is-small">➖</div>
                          <hr>
                          <div id="plus" class="button is-small">➕</div>
                        </div>
                        <div class="tile is-child">
                          <div id="gauche" class="button is-small">⬅️</div>
                        </div>
                        <div class="tile is-child">
                          <div id="haut" class="button is-small">⬆️</div>
                          <hr>
                          <div id="bas" class="button is-small">⬇️</div>
                        </div>
                        <div class="tile is-vertical is-child">
                          <div id="droite" class="button is-small">➡️</div>
                        </div>
                      </div>
                      
               </div>
              </div>
                 
          </div>
    <div class="tile is-child">
      <div class="tile is-parent box" style="width: fit-content">
      <p class="notification is-info">Choisis un sticker pour ajouter un post</p>
          <?php 
          $files = glob('images/stickers/*');
          foreach($files as $file){
            ?>
            <div class="tile is-child">
              <img id="items" src="<?=$file?>"></img>
            </div>
          <?php } ?>
      </div>
    </div>
  </div>
    <script src="../public/js/xhr_init.js"></script> 
    <script src="../public/js/upload.js"></script> 
              <?php } ?>

</div>

 
    <?php 
    }
}
    else {
        ?>
<div class="column is-three-fifths is-offset-one-fifth">
    <div class="column is-three-fifths is-offset-one-fifth box">
      <div class="notification is-info">
      <p class="title">Upload ta photo</p>
          <p> Uniquement jpeg , jpg, png, size : 10 Mo </p>
      </div>
      <div class="box" style="width:fit-content;">
          <form action="../controller/uploadController.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file"></input>
            <button class="button is-link" type="submit" value="Upload" name="submit"><i class="fas fa-upload"></i></button>
          </form>
      </div>
    </div>
</div>

<?php          
    }
?> 



