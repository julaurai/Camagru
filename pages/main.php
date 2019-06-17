<?php 
require_once 'models/pictures.php';
$pdo = new pictures;
if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $photo = $pdo->get_dataPictures($user_id);
} else {
    $photo = $pdo->get_dataPictures();
}
if ($photo){
foreach ($photo as $pic){
$commentNbr = intval($pic['commentNbr']);
$date = " - ".$pic['date']." - ".$pic['time'];
if (!isset($offset)){
    $offset = 0;
}

?>
<div class="column is-three-fifths is-offset-one-fifth" id="gallery">
    <div class="card" offset="<?=$offset += 1?>" id="card<?=$pic['id_post']?>" style="border-radius: 4px">
        <header class="card-header">
            <div class="card-header-title is-centered">
                <p>
                    <!-- AUTHOR PICTURE -->
                    <strong class="title is-4"><?=htmlspecialchars($pic['login'])?></strong>
                    <small class="subtitle is-6"><?=htmlspecialchars($date)?></small>
                </p>
            </div>
        </header>

        <!-- PICTURE -->
        <div class="card-image">
        <figure>
            <img class="content" src="<?=htmlspecialchars($pic['path'])?>"></img>
        </figure>
        </div>
        <!-- LIKE BUTTON  -->
        <div class="card-content">
            <hr class="hr">
            <div class="media">
                <div class="media-right">
            <?php if (isset($_SESSION['login'])){ 
            if ($pdo->already_liked($pic['id_post'], $_SESSION['user_id'])){ ?>
        <a 
        onclick="like_button(<?=htmlspecialchars($pic['id_post'])?>,<?=htmlspecialchars($_SESSION['user_id'])?>)"
        ><i class="fas fa-heart" style="color:red" id="heart<?=$pic['id_post']?>"><?=" ".htmlspecialchars($pic['likesCount'])?></i>
        </a>
        <?php } else { ?>
        <a
        onclick="like_button(<?=htmlspecialchars($pic['id_post'])?>,<?=htmlspecialchars($_SESSION['user_id'])?>)"
        ><i class="far fa-heart" style="color:red" id="heart<?=htmlspecialchars($pic['id_post'])?>"><?=" ".htmlspecialchars($pic['likesCount'])?></i>
        </a>
        <?php }} else { ?>
        <a href="index.php?p=signin">
        <i class="far fa-heart" style="color:red" id="heart<?=htmlspecialchars($pic['id_post'])?>"><?=" ".htmlspecialchars($pic['likesCount'])?></i>
        </a>
                <?php } ?>
                    </div>
                </div>
                <hr class="hr">
                        <!-- COMMENT AUTHOR -->
                <div class="content">
                    <div class="content" id="container<?=htmlspecialchars($pic['id_post'])?>">
                        <div class="content" id="comments<?=htmlspecialchars($pic['id_post'])?>">
                            <article class="box" id="box<?=htmlspecialchars($pic['id_post'])?>">
                                <div class="content"  id="lastComment<?=htmlspecialchars($pic['id_post'])?>">
                                <?php if ($commentNbr === 0){ ?>
                                    <div class="content">
                                        <p id="noTitle<?=htmlspecialchars($pic['id_post'])?>" class='title is-5'>Pas de commentaire</p>
                                    </div>
                                    <div class="content">
                                        <p id="noSubtitle<?=htmlspecialchars($pic['id_post'])?>" class='subtitle is-6'></p>
                                    </div>
                                <?php } else { 
                                    $date = " - ".htmlspecialchars($pic['date'])." - ".htmlspecialchars($pic['time']);
                                    ?>
                                <div class="content">
                                    <p class="title is-5" id="title_comment<?=htmlspecialchars($pic['id_post'])?>"><?=htmlspecialchars($pic['commenter'])?></p>
                                
                                </div>
                                <div class="content">
                                    <p class="subtitle is-6" id="subtitle_comment<?=htmlspecialchars($pic['id_post'])?>">
                                        <?=htmlspecialchars($pic['comment'])?>
                                    </p>
                                </div>
                                <?php } ?>
                                </div>
                            </article>
                        </div>
                    </div>
                        <!-- DISPLAY SHOW MORE BUTTON IF > 1 COMMENT -->
                    <div class="content" id="view_more_button<?=htmlspecialchars($pic['id_post'])?>">
                    <?php if ($commentNbr > 1){?>
                    
                    <a href="index.php?p=posts&postID=<?=urlencode($pic['id_post'])?>" id="view_more<?=htmlspecialchars($pic['id_post'])?>">View <?=htmlspecialchars($commentNbr - 1)?> more comment<?php if($commentNbr != 2){?>s<?php } ?></a>
                    
                    <?php } ?>
                    </div>
                            <?php if (isset($_SESSION['login'])){ ?>
                        <div class="content">
                            <input 
                                placeholder="Ajouter un commentaire..." 
                                class="input is-rounded" 
                                type="comment" 
                                name="comment" 
                                id="<?=urlencode($pic['id_post'])?>">
                            </input>
                            </div>
                            <div class="content">
                            <button 
                                class="button is-rounded" 
                                type="submit_comment" 
                                id="<?=urlencode($pic['id_post'])?>"
                                onclick="add_comment(<?=htmlspecialchars($pic['id_post'])?>,'<?=htmlspecialchars($_SESSION['login'])?>')"
                                ><i class="far fa-comment">  Commenter</i></button>
                            </div>
                            <?php } ?>
                </div>
            </div>
    </div>
</div>
        <?php
    }}
    ?>
<?php if(isset($_SESSION['login'])){ ?>
<script type="text/javascript"> 
    let login = "<?= htmlspecialchars($_SESSION['login']); ?>"
    let user_id = "<?= htmlspecialchars($_SESSION['user_id']); ?>"
</script>
<?php } ?>

<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/gallery.js"></script>
