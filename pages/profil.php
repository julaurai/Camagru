<?php
if (!(isset($_SESSION['login']))){
    header("Location: /");
    die();
}
else{
    $login = $_SESSION['login'];
    require_once 'models/users.php';
    $user = new users;
    $info_user = $user->getData_user($login);
}
?>
<div class="column is-three-fifths is-offset-one-fifth">
    <div class="column is-three-fifths is-offset-one-fifth box">
        <div class="field">
            <form>
                <label class="label" for="login">Login</label>
                <div class="control">
                    <input class="input" type="Text" name="new_login" id="new_login" value="<?=htmlspecialchars($login)?>">
                </div>
                <hr>
                <button class="button is-info" type="submit" id ="login_modify">Changement login</button>
                <p class="notification is-small" id="login_info"><i class="fas fa-info-circle"></i> Votre login doit contenir entre 5 et 31 caractères avec uniquement des caractères alphanumériques</p>
            </form>
        </div>
        <hr class="hr">
        <div class="field">
            <form>
                <label class="label" for="mail">E-Mail</label>
                <div>
                <input class="input" type="Text" name="mail" id="mail" value="<?=htmlspecialchars($info_user['mail'])?>">
                </div>
                <hr>
                <button class="button is-info" type="submit" id ="mail_modify">Changement E-mail</button>
            </form>
        </div>
        <hr class="hr">
        <div class="field">
            <form>
                <label class="label" for="old_pwd">Mot de passe actuel</label>
                <div>
                <input class="input" type="password" name="old_pwd" id="old_pwd">
                </div>
                <label class="label" for="new_pwd">Nouveau mot de passe</label>
                <div>
                <input class="input" type="password" name="new_pwd" id="new_pwd">
                </div>
                <label class="label" for="new_pwd2">Confirmation nouveau mot de passe</label>
                <div>
                <input class="input" type="password" name="new_pwd2" id="new_pwd2">
                </div>
                <hr>
                <button class="button is-info" type="submit" id ="password_modify">Changement mot de passe</button>
                <p id="pwd_info" class="notification is-small"><i class="fas fa-info-circle"></i> Le mot de passe doit contenir au moins une majuscule, un chiffre et 8 caractères</p>
                <hr>
            </form>
        </div>
        <hr class="hr">
        <div class="field">
            <label class="checkbox">
                <input type="checkbox" id="mail_notification" onclick="mail_notification(<?=htmlspecialchars($_SESSION['user_id'])?>)"
                <?php 
                if ($info_user['mail_notification'] === '1'){
                ?>
                checked
                <?php } ?>
                >
                Recevoir une notification lorsque mes photos sont commenté
            </label>
        </div>
    </div>
</div>

<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/profil_modify.js"></script>
