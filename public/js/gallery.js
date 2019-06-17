function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
   
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
  }


function add_comment(post_id, user_login){
    var input = document.querySelector("input[id="+ CSS.escape(post_id) +"]");
    if (input.value.trim().length == 0){
        createNotification('Vous ne pouvez pas envoyer un commentaire vide', input);
    } else {
        input.setAttribute("class", "input is-rounded");
        input.placeholder = 'Ajouter un commentaire...';
        let new_comment = input.value,
            xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function (){
            if (xhr.readyState == 4){
                if(xhr.status == '200'){
                    let box = document.querySelector('#box'+ post_id);
                if (box){
                    var view_more = document.querySelector('#view_more'+post_id);
                    // CHECK IF THERE IS ALREADY COMMENT / VIEW MORE
                    var comment = box.querySelector('#title_comment' + post_id);
                    if (view_more){
                        refresh_comments_nbr(post_id); 
                    } else if (!view_more && comment){
                        create_view_comments(post_id);
                    }
                    if (comment){
                        document.querySelector('#title_comment'+post_id).innerHTML = user_login; 
                        document.querySelector('#subtitle_comment'+post_id).innerHTML = (xhr.responseText);
                    } else {
                        let title = document.querySelector('#noTitle'+post_id);
                        let subtitle = document.querySelector('#noSubtitle'+post_id);
                        title.innerHTML = user_login;
                        subtitle.innerHTML = escapeHtml(xhr.responseText);
                        title.id = 'title_comment'+post_id;
                        subtitle.id = 'subtitle_comment'+post_id;
                    }
                // INPUT TO NULL
                input.value = "";
                }
                    }
                    else if(xhr.status == '400'){
                        alert('probleme avec le serveur');
                    }
                }
            }
        xhr.open('POST', '/controller/picturesController.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        let data =  'action=addComment'
                    +'&user_login=' + user_login
                    +'&comment='+ new_comment
                    +'&post_id='+ post_id;
        xhr.send(data);
        }
}
function like_button(post_id, user_id){
    let like_btn = document.getElementById('heart'+ post_id);
    var value = parseInt(like_btn.innerHTML);
    if (like_btn.className == 'fas fa-heart'){
        like_btn.className = 'far fa-heart';
        var data =  'action=takeOffLike'
                    +'&user_id=' + user_id
                    +'&post_id='+ post_id;
        value = value - 1;
        like_btn.innerHTML = " " + value;
    } else{
        like_btn.className = 'fas fa-heart'
        var data =  'action=addLike'
                    +'&user_id=' + user_id
                    +'&post_id='+ post_id;
        value = value + 1;
        like_btn.innerHTML = " " + value;
    }
    let xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function (){
            if (xhr.readyState == 4){
                if(xhr.status == '200'){
                    
                }
                else if(xhr.status == '400'){
                    
                }
            }
        }
    xhr.open('POST', '/controller/picturesController.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(data);
}

window.onscroll = function(event) {
    if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
        const matches = document.querySelectorAll("[offset]");
        const last = matches[matches.length -1];
        let offset = parseInt(last.getAttribute('offset'), 10);
        const action = 'action=GetNextPost&offset='+offset;  //+'&token='+token;
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let json = JSON.parse(xhr.responseText),
                    connected = json['connected'];
                if (connected){
                    var session_login = json['session'][0];
                    var session_id = json['session'][1];
                }
                delete json.connected;
                delete json.session;
                const keys = Object.keys(json)
                for (const key of keys) {
                    offset += 1;
                    let login = json[key].login,
                        path = json[key].path,
                        id_user = json[key].id_user
                        date = json[key].date,
                        time = json[key].time,
                        comment = json[key].comment,
                        commenter = json[key].commenter,
                        commentNbr = json[key].commentNbr,
                        id_post = json[key].id_post,
                        user_liked = json[key].user_liked == null ? 0 : json[key].user_liked,
                        likes_count = json[key].likesCount == null ? 0 : json[key].likesCount;
                    createOnePost(connected, commentNbr, offset, login, path, id_post, user_liked, likes_count, comment, commenter, id_user, session_login, session_id);
                  }
            }
            if (xhr.readyState == 4 && xhr.status == 401) {
                
            }
        };
        xhr.open("POST", "./controller/picturesController.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(action);
    }
};






function refresh_comments_nbr(post_id){
    let more_comment = document.querySelector('#view_more'+ post_id);
    if (more_comment){
        let test = more_comment.innerHTML,
            value = test.match(/[0-9]+/);
        value = parseInt(value[0]) + 1;
        more_comment.innerHTML = "View "+ value + " more comments";
        return true;
    }  
}

function create_view_comments(post_id){
    let view_more = document.createElement('a');
    view_more.setAttribute('id', 'view_more'+ post_id);
    view_more.setAttribute('href','index.php?p=posts&postID='+post_id);
    view_more.innerHTML = 'View 1 more comment';
    let button = document.querySelector("#view_more_button"+ post_id);
    button.appendChild(view_more);
}


function createNotification(message, doc){
    doc.setAttribute("class", "input is-danger is-rounded");
    doc.placeholder = message;
}

function createOnePost(connected, commentNbr, offset, login, path, id_post, user_liked, likes_count, comment, commenter, id_user, session_login, session_id){
    if (commentNbr >= 2){
        commentNbr = parseInt(commentNbr);
        commentNbr = commentNbr - 1;
    }
    let orthographe = commentNbr === 1 ? "comment" : "comments";
    let new_post = document.createElement('div'),
        add_comment = connected ? `<div class="content">
        <input 
            placeholder="Ajouter un commentaire..." 
            class="input is-rounded" 
            type="comment" 
            name="comment" 
            id="`+id_post+`">
        </input>
        </div>
        <div class="content">
        <button 
            class="button is-rounded" 
            type="submit_comment" 
            id="`+id_post+`"
            onclick="add_comment(`+id_post+`,'`+session_login+`')"
            ><i class="far fa-comment">  Commenter</i></button>
        </div>` : "" ; 
    new_post.setAttribute('class', 'column is-three-fifths is-offset-one-fifth');
    new_post.setAttribute('id', 'gallery');
    if (connected === 'false'){
        var heart = `<a 
                        href="index.php?p=signin"
                        ><i class="far fa-heart" style="color:red" id="heart`+id_post+`">`+" "+likes_count+`</i>
                    </a>`;
    } else if (user_liked){
        var heart = `<a 
                        onclick="like_button(`+id_post+`, `+session_id+`)"
                        ><i class="fas fa-heart" style="color:red" id="heart`+id_post+`">`+" "+likes_count+`</i>
                    </a>`;
    } else {
        var heart = `<a 
                        onclick="like_button(`+id_post+`, `+session_id+`)"
                        ><i class="far fa-heart" style="color:red" id="heart`+id_post+`">`+" "+likes_count+`</i>
                    </a>`;
    }

    if (parseInt(commentNbr) === 0){
        var comment = `<div class="content">
                               <p id="noTitle`+id_post+`" class='title is-5'>Pas de commentaire</p>
                        </div>
                        <div class="content">
                               <p id="noSubtitle`+id_post+`" class='subtitle is-6'></p>
                        </div>
                    </div> 
                        `;
    } else if (parseInt(commentNbr) === 2){
        var comment =   `<div class="content">
                            <p class="title is-5" id="title_comment`+id_post+`">`+commenter+`</p>    
                        </div>
                        <div class="content">
                            <p class="subtitle is-6" id="subtitle_comment`+id_post+`">
                                `+comment+`
                            </p>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="content" id="view_more_button`+id_post+`">
                        <a href="index.php?p=posts&postID=`+id_post+`" id="view_more`+id_post+`">View `+commentNbr +` more `+orthographe+`</a>
                    </div>`;
    } else {
        var comment = `<div class="content">
                            <p class="title is-5" id="title_comment`+id_post+`">`+commenter+`</p>    
                        </div>
                        <div class="content">
                            <p class="subtitle is-6" id="subtitle_comment`+id_post+`">
                                `+comment+`
                            </p>
                        </div>
                    </div>
            </div>
        </div>
                    <div class="content" id="view_more_button`+id_post+`">
                        <a href="index.php?p=posts&postID=`+id_post+`" id="view_more`+id_post+`">View `+commentNbr +` more `+orthographe+`</a>
                    </div>`;
    }
  


    new_post.innerHTML =
`
    <div class="card" offset="`+offset+`" id="card`+id_post+`" style="border-radius: 4px">
        <header class="card-header">
            <div class="card-header-title is-centered">
                <p>
                    
                    <strong class="title is-4">`+login+`</strong>
                    <small class="subtitle is-6">`+" - "+date+" - "+time+`</small>
                </p>
            </div>
        </header>
            <div class="card-image">
                    <figure>
                        <img class="content" src="`+path+`"></img>
                    </figure>
        </div>
        <!-- LIKE BUTTON  -->
        <div class="card-content">
            <hr class="hr">
            <div class="media">
                <div class="media-right">`+heart+`

                    </div>
                </div>
                <hr class="hr">
                        <!-- COMMENT AUTHOR -->
                <div class="content">
                    <div class="content" id="container`+id_post+`">
                        <div class="content" id="`+id_post+`">
                            <article class="box" id="box`+id_post+`">
                                <div class="content"  id="lastComment`+id_post+`">
                                `+comment+`
                                </div>
                            </article>
                                <div class="content" id="view_more_button`+id_post+`">
                        </div>
                        `+add_comment+`
                    </div>
                </div>
            </div>`;
     
     document.body.parentNode.appendChild(new_post);
    document.querySelector('#gallery').parentNode.appendChild(new_post);
}
