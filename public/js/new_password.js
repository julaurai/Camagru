document.addEventListener('click', function(e){
    if (event.target.id === 'password_modify'){
        let pwd = document.getElementById('new_pwd').value.trim();
        let pwd2 = document.getElementById('new_pwd2').value.trim();
        e.preventDefault();
        let container = document.querySelector('.field');
        for (var i = 0; i < container.childElementCount; i++){
            if(container.childElementCount >= 2){
                container.removeChild(container.lastChild);
            }
        }
        if (pwd.length == 0 || pwd2.length == 0){
            createNotification("Veuillez remplir les champs",container, 'notification is-small is-danger');
        }
        if (pwd != pwd2){
            createNotification("Les mots de passes ne sont pas identiques",container, 'notification is-small is-danger');
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4){
                if(xhr.status == '200' || xhr.status == '0'){
                    alert("Password modifié avec succés");
                    window.location.replace("index.php");
                }
                else if (xhr.status == '400')
                    createNotification("Le mot de passe doit contenir au moins une majuscule, un chiffre et 8 caractères",container, 'notification is-small is-danger');
            }
        }
        xhr.open('POST', '/controller/password_recoveryController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =  'mail='+ email
                    +'&key='+ key
                    +'&pwd='+ pwd
                    +'&pwd2='+ pwd2;
        xhr.send(data);
    }
})


function createNotification(message, doc, clas){
    let error = document.createElement("div");
    error.setAttribute('class', clas);
    error.innerHTML = message;
    doc.appendChild(error);
}

