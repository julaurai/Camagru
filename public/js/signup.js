document.addEventListener ('click', function(e){
    if (event.target.id === 'signup_submit'){
        let user_login = document.getElementById('user_login').value.trim(),
            mail = document.getElementById('mail').value.trim(),
            pwd = document.getElementById('pwd').value.trim(),
            pwd2 = document.getElementById('pwd2').value.trim(),
            info_login = document.querySelector("#info_login"),
            info_mdp = document.querySelector("#info_mdp");
        e.preventDefault();
        let error = [];
        user_login.length === 0 ? error.user_login = "Veuillez remplir ce champs" : "";
        ((user_login.length < 5 && user_login.length > 0) || user_login.length > 31) ? info_login.setAttribute('class', 'notification is-danger is-small') : info_login.setAttribute('class', 'notification is-light is-small');
        mail.length === 0 ? error.mail = "Veuillez remplir ce champs" : "";
        pwd.length === 0 ? error.pwd = "Veuillez remplir ce champs" : "";
        pwd2.length === 0 ? error.pwd2 = "Veuillez remplir ce champs" : "";
        pwd2 !== pwd ? error.pwd2 = "Les mots de passe ne sont pas identiques" : "";
        pwd2 < 8 && pwd2 > 0 ? info_mdp.setAttribute('class', 'notification is-danger is-small') : info_mdp.setAttribute('class', 'notification is-light is-small')
        let input = ['user_login', 'mail', 'pwd', 'pwd2'];
        for (var i = 0; i < input.length; i++){
            if(document.querySelector('#'+input[i]).parentNode.childElementCount === 2){
                document.querySelector('#'+input[i]).parentNode.removeChild(document.querySelector('#'+input[i]).parentNode.lastChild);
            }
        }
        if (error['user_login'] || error['mail'] || error['pwd'] || error['pwd2']){
            for (var key in error){
                createNotification(error[key], document.querySelector('#'+key));
            }
            return ;
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function (){
            if (xhr.readyState == 4){
                if(xhr.status == '200' || xhr.status == '0'){
                    alert("Inscription validé, active ton compte grâce au mail que nous t'avons envoyé !");
                    window.location.replace("index.php");
                }
                else if(xhr.status == '400'){
                    document.querySelector('#info_mdp').setAttribute('class', 'notification is-small is-danger');
                    document.querySelector('#info_login').setAttribute('class', 'notification is-small is-danger');
            }  
                else if(xhr.status == '401'){
                    createNotification("Login/mail déjà utilisé", document.querySelector("#user_login"));
                    createNotification("Login/mail déjà utilisé", document.querySelector("#mail"));
                }
        }
    }
        xhr.open('POST', '/controller/signupController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =  'action=signup'
                    +'&login='+user_login
                    +'&mail=' + mail
                    +'&pwd='+ pwd
                    +'&pwd2='+ pwd2;
        xhr.send(data);
}
})

function createNotification(message, doc){
    let error = document.createElement("div");
    doc.setAttribute("class", "input is-danger");
    error.setAttribute('class', 'notification is-danger');
    error.innerHTML = message;
    doc.parentNode.appendChild(error);
}
