<div class="column is-three-fifths is-offset-one-fifth"> 
    <div class="column is-three-fifths is-offset-one-fifth box">
        <div class="field">
            <p class="title notification is-light">Connexion</p>
            <form>
                <label class="label" for="login">Login</label>
                <div>
                <input class="input" type="Text" name="login" id="user_login" required>
                </div>
        </div>
        <div class="field">
                <label class="label" for="pwd">Mot de passe</label>
                <div>
                <input class="input" type="password" name="pwd" id="pwd" required>
                </div>
            </form>
        <hr>
        <div class="field is-grouped is-grouped-centered">
        <p class="control">
                <button class="button is-success" type="submit" id="signin_submit">Connexion</button>
                <a class="button is-light" href="index.php?p=forgottenPassword">Mot de passe oublié</a>
        </p>
        </div>
        </div>
    </div>
</div>
<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/signin.js"></script>

