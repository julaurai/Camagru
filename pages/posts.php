<div class="block" id="pictures_users">
  <?php
  require_once ('models/pictures.php');
  $pdo = new pictures;
  $pictures_users = $pdo->getUser_pictures($_SESSION['user_id']);
  if ($pictures_users){
    foreach ($pictures_users as $pic){
    ?>
<div class="column is-three-fifths is-offset-one-fifth">
  <div class="card" id="card<?=htmlspecialchars($pic['id_post'])?>">
      <div class="card-image">
        <figure>
          <img src="<?=$pic['path']?>" id="<?=htmlspecialchars($pic['id_post'])?>"></img>
        </figure>
      </div>
      <footer class="card-footer">

        <a 
        type="submit" 
        onclick="delete_post(<?=htmlspecialchars($pic['id_post'])?>,<?=htmlspecialchars($_SESSION['user_id'])?>)"
        class="card-footer-item notification is-danger"
        >Supprimer le post</a>
        <p class="card-footer-item"><i class="far fa-comment">  <?=htmlspecialchars($pic['commentNbr'])?></i></p>
        <p class="card-footer-item"><i class="far fa-heart" style="color:red">  <?=htmlspecialchars($pic['LikeNbr'])?></i></p>
      </footer>
      </div>
</div>
      <?php } ?>
<?php } else {
  ?>
    <div class="column is-three-fifths is-offset-one-fifth"> 
            <article class="message is-info">
              <div class="message-header">
                <p class="p">Tu n'as pas de post pour le moment :(</p>
              </div>
            </article>
    </div>
<?php
} 
?>
</div>

<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/post.js"></script>
