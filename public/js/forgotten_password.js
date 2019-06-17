document.addEventListener('click', function(e){
    if(event.target.id == 'submit'){
        let mail = document.getElementById('mail').value.trim();
        e.preventDefault();
        if (mail.length == 0){
            alert('Empty field');
            exit();
        }
        let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4){
                if (xhr.status == "200"){
                    alert("Un mail vient de vous être envoyé afin de réinitialiser votre mot de passe");
                    window.location.replace("index.php");
                }
                else if (xhr.status == '400'){
                    alert("Un mail vient de vous être envoyé afin de réinitialiser votre mot de passe");
                }
            }
        }
        xhr.open('POST', '/controller/forgottenPasswordController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data = '&mail=' + mail;
        xhr.send(data);
    }
})
