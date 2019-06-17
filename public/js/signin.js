
document.addEventListener('click', function(e){
    if (event.target.id === 'signin_submit'){
        let user = document.getElementById('user_login').value.trim();
        let pwd = document.getElementById('pwd').value.trim();
        e.preventDefault();
        if(document.querySelector('#user_login').parentNode.childElementCount === 2){
            document.querySelector('#user_login').parentNode.removeChild(document.querySelector('#user_login').parentNode.lastChild);
        }
        if(document.querySelector('#pwd').parentNode.childElementCount === 2){
            document.querySelector('#pwd').parentNode.removeChild(document.querySelector('#pwd').parentNode.lastChild);
        }
        if (user.length === 0){
            createNotification("Veuillez remplir ce champs",document.querySelector('#user_login'));
            return ;
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4){
                if(xhr.status == '200' || xhr.status == '0'){
                    window.location.replace("index.php");
                }
                else if (xhr.status == '400'){
                    createNotification("Mauvais login/password", document.querySelector('#pwd'));
                    error();
                }
                else if (xhr.status == '428'){
                    createNotification("Compte pas activé", document.querySelector('#pwd'));
                    error2();
                }
            }
        }
        xhr.open('POST', '/controller/signinController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =   'action=signin'
                    +'&login='+user
                    +'&pwd='+ pwd;
        xhr.send(data);
    }
})

var error = function (){
    var input = document.querySelectorAll('.input');
    for(i = 0; i < input.length; i++){
        input[i].className = "input is-danger";
    }
}
var error2 = function (){
    var input = document.querySelectorAll('.input');
    for(i = 0; i < input.length; i++){
        input[i].className = "input is-warning";
    }
}

function createNotification(message, doc){
    let error = document.createElement("div");
    doc.setAttribute("class", "input is-danger");
    error.setAttribute('class', 'notification is-danger');
    error.innerHTML = message;
    doc.parentNode.appendChild(error);
}
