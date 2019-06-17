function delete_post(post_id, user_id){
  if (confirm("Veux tu vraiment supprimer ce post ?")){
    let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function (){
          if (xhr.readyState == 4){
            if (xhr.status == '200'){
                  let card = document.querySelector("#card"+post_id);
                  if (card){
                    card.parentNode.removeChild(card);
                    let first = card.firstElementChild;
                    while (first){
                      first.remove();
                      first = card.firstElementChild;
                    }
                  }
            }
            else if (xhr.status == '400'){
              alert (xhr.responseText);
            }
          }
        }
        xhr.open('POST', '/controller/picturesController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =  'action=delPost'
                    +'&user_id=' + user_id
                    +'&post_id='+ post_id; 
        xhr.send(data);
  }
}

function createNotification(message, doc){
  doc.setAttribute("class", "input is-danger");
}
