<?php
if (!(isset($_SESSION['login']))){
    header("Location: index.php?p=signin");
} else { ?>


<link rel="stylesheet" type="text/css" href="public/css/webcam.css">
<br>
<br>
<div class="tile is-ancestor">
  <div class="tile is-parent is-vertical">
           <div class="tile is-child box" style="height: fit-content">
            <div class="tile is-parent" id="video">
               <div class="tile is-child is-vertical is-9">
                  <article id="montage" style="position:relative; width:fit-content; height:fit-content">
                    <div id="overlay">
                    </div>
                    <video autoplay="true" id="videoElement" style="position:relative;"></video>
                    <canvas id="canvas" hidden></canvas>
                  </article>
               </div>
               <div class="tile is-vertical box notification" id="customSticker">

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
          
          
                  <div class="tile is-child notification is-light box">
                    <p class="subtitle">Pas de webcam ? upload une photo <a href="index.php?p=upload" class="button is-link">ICI</a></p>
                  </div> 
                
                  
                    
          </div>
    <div class="tile is-child" id="stickersPick">
      <div class="tile is-parent box" style="width: fit-content">
      <p class="notification is-primary">Choisis un sticker</p>
        <div class="tile is-parent box">
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
  </div>
  <div class="tile is-parent is-2 box">
              <div class="tile is-child" id="pictures">
              <?php
              require_once ('models/pictures.php');
              $pdo = new pictures;
              $pictures_users = $pdo->getUser_pictures($_SESSION['user_id']);
              if ($pictures_users){
                foreach ($pictures_users as $pic){
                  ?>
                <div class="tile is-child">
                  <img class="pic box" src="<?=$pic['path']?>" id="<?=htmlspecialchars($pic['id_post'])?>"></img>
                </div>
              <?php }}} ?>
            </div>
  </div>
</div>

<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/webcam.js"></script>
 