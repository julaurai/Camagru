
// PASSWORD
document.addEventListener('click', function(e){
    if (event.target.id === 'password_modify'){
        let old_pwd = document.getElementById('old_pwd').value.trim();
        let new_pwd = document.getElementById('new_pwd').value.trim();
        let new_pwd2 = document.getElementById('new_pwd2').value.trim();
        let error = [];
        e.preventDefault();
        old_pwd.length === 0 ? error.old_pwd = "Veuillez remplir ce champs" : "";
        new_pwd.length === 0 ? error.new_pwd = "Veuillez remplir ce champs" : "";
        new_pwd2.length === 0 ? error.new_pwd2 = "Veuillez remplir ce champs" : "";
        new_pwd !== new_pwd2 ? error.new_pwd2 = "Les mots de passe ne sont pas identiques" : "";
        // new_pwd !== new_pwd2 ? error.new_pwd = "Les mots de passe ne sont pas identiques" : "";
        let input = ['old_pwd', 'new_pwd', 'new_pwd2'];
        for (var i = 0; i < input.length; i++){
            if(document.querySelector('#'+input[i]).parentNode.childElementCount === 2){
                document.querySelector('#'+input[i]).parentNode.removeChild(document.querySelector('#'+input[i]).parentNode.lastChild);
            }
        }
        if (error['old_pwd'] || error['new_pwd'] || error['new_pwd2']){
            for (var key in error){
                createNotification(error[key], document.querySelector('#'+key));
            }
            return ;
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function (){
            if (xhr.readyState == 4){
                if(xhr.status == '200'){
                    for (var i = 0; i < input.length; i++){
                        if(document.querySelector('#'+input[i]).parentNode.childElementCount === 2){
                            document.querySelector('#'+input[i]).parentNode.removeChild(document.querySelector('#'+input[i]).parentNode.lastChild);
                        }
                        document.querySelector('#'+input[i]).setAttribute('class', 'input');
                        document.querySelector('#'+input[i]).value = "";
                    }
                    document.querySelector('#pwd_info').setAttribute('class', 'notification is-small is-light');
                    let success = document.createElement("div");
                    success.setAttribute('class', 'notification is-primary');
                    success.innerHTML = 'Mot de passe changé';
                    document.querySelector("#new_pwd2").parentNode.appendChild(success);
                }
                else if (xhr.status == '400'){
                    document.querySelector('#pwd_info').setAttribute('class', 'notification is-small is-danger');
                }
                else if (xhr.status == '401'){
                   createNotification('Mauvais mot de passe', document.querySelector('#old_pwd'));
                }
            }
        }
        xhr.open('POST', '/controller/modify_profilController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =   'action=pwd_modify'
                    +'&old_pwd='+ old_pwd
                    +'&pwd='+ new_pwd
                    +'&pwd2='+ new_pwd2;
        xhr.send(data);
    }

    // LOGIN
    else if (event.target.id === 'login_modify'){
        let newlogin = document.getElementById('new_login').value;
        e.preventDefault();
        if(document.querySelector('#new_login').parentNode.childElementCount === 2){
            document.querySelector('#new_login').parentNode.removeChild(document.querySelector('#new_login').parentNode.lastChild);
        }
        if (newlogin.length == 0){
            createNotification("Veuillez remplir ce champs", document.getElementById('new_login'));
            return ;
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4){
                if(xhr.status == '200'){
                    if(document.querySelector('#new_login').parentNode.childElementCount === 2){
                        document.querySelector('#new_login').parentNode.removeChild(document.querySelector('#new_login').parentNode.lastChild);
                    }
                    document.querySelector('#new_login').setAttribute('class', 'input');
                    document.querySelector('#login_info').setAttribute('class', 'notification is-small is-light');
                    document.querySelector('#new_login').setAttribute('class', 'input is-primary');
                    // window.location.replace("index.php?p=profil");
                }
                else if (xhr.status == '400'){
                    if(document.querySelector('#new_login').parentNode.childElementCount === 2){
                        document.querySelector('#new_login').parentNode.removeChild(document.querySelector('#new_login').parentNode.lastChild);
                    }
                    document.querySelector('#login_info').setAttribute('class', 'notification is-small is-danger');
                    // createNotification(xhr.responseText, document.getElementById('new_login'));
                }
                else if(xhr.status == '401'){
                    createNotification("Login déjà utilisé", document.querySelector("#new_login"));
                }
            }
        }
        xhr.open('POST', '/controller/modify_profilController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =   'action=login_modify'
                    +'&new_login='+ newlogin;
        xhr.send(data);
    }
    // MAIL
    else if (event.target.id === 'mail_modify'){
        let mail = document.getElementById('mail').value;
        e.preventDefault();
        if(document.querySelector('#mail').parentNode.childElementCount === 2){
            document.querySelector('#mail').parentNode.removeChild(document.querySelector('#mail').parentNode.lastChild);
        }
        if (mail.length === 0){
            createNotification("Veuillez remplir ce champs", document.getElementById('mail'));
            return ;
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4){
                if(xhr.status == '200' || xhr.status == '0'){
                    document.getElementById('mail').setAttribute('class', 'input is-primary');
                    if(document.querySelector('#mail').parentNode.childElementCount === 2){
                        document.querySelector('#mail').parentNode.removeChild(document.querySelector('#mail').parentNode.lastChild);
                    }
                    
                }
                else if (xhr.status == '400'){
                    createNotification(xhr.responseText, document.getElementById('mail'));
                }
                else if(xhr.status == '401'){
                    createNotification("E-mail déjà utilisé", document.querySelector("#mail"));
                }
                    
            }
        }
        xhr.open('POST', '/controller/modify_profilController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =   'action=mail_modify'
                    +'&mail='+ mail;
        xhr.send(data);
    } 
})
    // MAIL NOTIFICATION
function mail_notification(user_id){
    let checkBox = document.querySelector('#mail_notification').checked;
    let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4){
                if(xhr.status == '200'){
                }
                else if (xhr.status == '400'){
                }
            }
        }
        xhr.open('POST', '/controller/modify_profilController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =   'action=mail_notification'
                    +'&Notif='+ checkBox;
        xhr.send(data);

}

function createNotification(message, doc){
    let error = document.createElement("div");
    doc.setAttribute("class", "input is-danger");
    error.setAttribute('class', 'notification is-danger');
    error.innerHTML = message;
    doc.parentNode.appendChild(error);
}
