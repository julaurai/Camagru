<div class="column is-three-fifths is-offset-one-fifth">
    <div class="column is-three-fifths is-offset-one-fifth box">
        <div class='field'>
                <p class="title notification is-light">Inscription</p>
                <form>
                <div class="field">
                    <label class="label" for="login">Login</label>
                    <div>
                    <input class="input is-half" type="Text" name="login" id="user_login" required>
                    </div>
                    <p id="info_login" class="notification is-small"><i class="fas fa-info-circle"></i> Votre login doit contenir entre 5 et 31 caractères avec uniquement des caractères alphanumériques</p>
                </div>
                <hr>
                <div class="field">
                    <label class="label" for="mail">E-Mail</label>
                    <div>
                    <input class="input" type="Text" name="mail" id="mail" required>
                    </div>
                </div>
                <hr>
                <div class="field">
                    <label class="label" for="pwd">Mot de passe</label>
                    <div>
                    <input class="input" type="password" name="pwd" id="pwd" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="pwd2">Confirmation mot de passe</label>
                    <div>
                    <input class="input" type="password" name="pwd2" id="pwd2" required>
                    </div>
                    <div>
                    <p id="info_mdp" class="notification is-small"><i class="fas fa-info-circle"></i>  Le mot de passe doit contenir au moins une majuscule, un chiffre et 8 caractères au total</p>
                    </div>
                </div>
                <hr>
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                    <button class="button is-primary" type="submit" id ="signup_submit">S'inscrire</button>
                    </p>
                </div>
                </form>
        </div>
    </div>
</div>
<script src="../public/js/xhr_init.js"></script>
<script src="../public/js/signup.js"></script>
