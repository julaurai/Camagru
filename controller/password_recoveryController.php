<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/models/users.php';
if (isset($_GET['mail']) && isset($_GET['key'])){
    $mail = $_GET['mail'];
    $key = $_GET['key'];
    $pdo = new users;
    $result = $pdo->check_mail($mail);
    if ($result['COUNT(*)'] === "1"){
        $data = $pdo->getuser_key($mail);
        if ($data['key'] === $key){ ?>
<div class="column is-three-fifths is-offset-one-fifth">
    <div class="column is-three-fifths is-offset-one-fifth box">
    <div class="field">
        <form>
            <label class="label" for="new_pwd">Nouveau mot de passe</label>
            <input class="input" type="password" name="new_pwd" id="new_pwd">
            <label class="label" for="new_pwd2">Confirmation nouveau mot de passe</label>
            <input class="input" type="password" name="new_pwd2" id="new_pwd2">
            <div class="field is-grouped is-grouped-centered">
            <p class="control">
            <button class="button is-info" type="submit" id ="password_modify">Valider</button>
            </p>
            </div>
        </form>
    </div>
    </div>
</div>
<script type="text/javascript"> 
    let email = "<?= $_GET['mail']; ?>"
    let key = "<?= $_GET['key']; ?>"
</script>
<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/new_password.js"></script>
<?php
        }
        else{
            header('Location: index.php');
        }
    }else
    {
        header('Location: index.php');
    }
} else if (isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['mail']) && isset($_POST['key'])){
    $uppercase = preg_match('@[A-Z]@', $_POST['pwd']);
    $lowercase = preg_match('@[a-z]@', $_POST['pwd']);
    $number    = preg_match('@[0-9]@', $_POST['pwd']);
    if(!$uppercase || !$lowercase || !$number || strlen($_POST['pwd']) < 8) {
        echo "regex mdp";
        http_response_code(400);
        die();
    }
    $pwd = hash('sha512', $_POST['pwd']);
    $pwd2 = hash('sha512', $_POST['pwd2']);
    $mail = $_POST['mail'];
    $key = $_POST['key'];
    if ($pwd === $pwd2){
        $pdo = new users;
        $result = $pdo->check_mail($mail);
        if ($result['COUNT(*)'] === "1"){
            $data = $pdo->getuser_key($mail);
            if ($data['key'] === $key){
                $pdo->init_password($pwd, $mail);
                http_response_code(200);
            }
        else{
            http_response_code(400);
        }
        }
    } else {
        http_response_code(400);
    }
}
?>
