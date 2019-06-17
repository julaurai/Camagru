<?php
require_once ('models/pictures.php');

$post_id =  intval($_GET['postID']);
if (!is_numeric($post_id) || $post_id === 0){
  header('Location: index.php');
  die();
}
$pdo = new pictures;
$data_picture = $pdo->pic_comment($post_id);
if(!isset($data_picture[0]['path'])){
  header('Location: index.php');
  die();
}
$pic = $data_picture[0]['path'];
$title = $data_picture[0]['author'];
$likesCount = $data_picture[0]['likescount'];
$date = " - ".$data_picture[0]['post_date']." - ".$data_picture[0]['post_time'];
?>
<div class="column is-three-fifths is-offset-one-fifth">
  <div class="card">
        <!-- TITLE AUTHOR -->
        <header class="card-header">
          <div class="card-header-title is-centered">
            <p>
              <strong class="title is-4"><?=htmlspecialchars($title)?></strong> 
              <small class="subtitle is-6"><?=htmlspecialchars($date)?></small>
            </p>
          </div>
        </header>
        <!-- IMAGE -->
      <div class="card-image">
        <figure>
          <img src="<?=$pic?>"></img>
        </figure>
      </div>
        <!-- LIKE BUTTON -->
      <div class="card-content">
        <div class="media">
          <div class='media-right'>
            <?php if (isset($_SESSION['login'])){ 
                if ($pdo->already_liked($post_id, $_SESSION['user_id'])){
            ?>
              <a class="content" 
              onclick="like_button(<?=htmlspecialchars($post_id)?>,<?=htmlspecialchars($_SESSION['user_id'])?>)"
              ><i class="fas fa-heart" style="color:red" id="heart<?=htmlspecialchars($post_id)?>"><?=" ".htmlspecialchars($likesCount)?></i></a>
              <?php } else { ?>
              <a class="content" 
              onclick="like_button(<?=htmlspecialchars($post_id)?>,<?=htmlspecialchars($_SESSION['user_id'])?>)"
              ><i class="far fa-heart" style="color:red" id="heart<?=htmlspecialchars($post_id)?>"><?=" ".htmlspecialchars($likesCount)?></i></a>
              <?php }} else { ?>
              <a class="content" href="index.php?p=signin"
              ><i class="far fa-heart" style="color:red" id="heart<?=htmlspecialchars($post_id)?>"><?=" ".htmlspecialchars($likesCount)?></i></a>
              <?php } ?>
          </div>
        </div>
          <!-- COMMENT -->
        <div class="content">
        <?php foreach ($data_picture as $comment){ ?>
          <article class="box">
            <div class="content">
              <p class="title is-5"><?=htmlspecialchars($comment['commenter'])?><i class="title is-7"><?=" - ".htmlspecialchars($comment['comment_date'])." - ".htmlspecialchars($comment['comment_time'])?></i></p>
            </div>
            <hr>
            <div class="content">
              <p class='subtitle is-6'><?=htmlspecialchars($comment['comment'])?></p>
            </div>
          </article>
  <?php } ?>
      </div>
      </div>
  </div>
</div>
<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/gallery.js"></script>


