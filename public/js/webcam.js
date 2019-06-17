(function() {
  var streaming    = false,
      video        = document.querySelector('#videoElement'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      startbutton  = document.querySelector('#startbutton'),
      width = 640,
      height = 480;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);
  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
    if (navigator.mediaDevices.getUserMedia){
            document.querySelector('#customSticker').style.visibility = "hidden";
            document.querySelector('#stickersPick').style.display = "";
            document.querySelector('#video').style.display = "";
            navigator.mediaDevices.getUserMedia({video: true}).
            then((stream) => {video.srcObject = stream});
      } else {
        document.querySelector('#customSticker').style.display = "none";
        document.querySelector('#stickersPick').style.display = "none";
        document.querySelector('#video').style.display = "none";
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
   
      document.querySelector('#customSticker').style.display = "none";
      document.querySelector('#stickersPick').style.display = "none";
      document.querySelector('#video').style.display = "none";
      console.log("An error occured! " + err);
    }
  );

  videoElement.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

  function takepicture() {
  
    let stickerInfo = JSON.stringify(getStickerInfo());
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(video, 0, 0, width, height);
    var data = canvas.toDataURL('image/png');
    
    let xhr = getXMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4){
          if(xhr.status == '200' || xhr.status == '0'){
              let newdiv = document.querySelector('#pictures')
              if (newdiv.childElementCount === 6){
                newdiv.lastElementChild.remove(newdiv.lastElementChild);
              }
              photo = document.createElement('IMG'),
              photo.setAttribute('src', xhr.responseText);
              photo.setAttribute('class', 'pic box notification is-success');
              newdiv.prepend(photo);
              const sticker = document.getElementById("overlay");
              if (sticker.firstChild){
                sticker.removeChild(sticker.firstChild);
              }
              document.querySelector('#customSticker').style.visibility = "hidden";
          }
          else if (xhr.status == '400')
              alert('Probleme serveur');
      }
  }
  xhr.open('POST', '/controller/picturesController.php', true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send( 'action=addPost'
            +'&sticker='+ stickerInfo
            +'&data='+data
          );
}

  if (document.querySelector('#startbutton')){
  startbutton.addEventListener('click', function(ev){
      takepicture();
    ev.preventDefault();
  }, false);
  }
})();



document.addEventListener("click", function(e)	{
  let offsetHeight = document.querySelector('#videoElement').offsetHeight;
  const overlay = document.querySelector('#overlay').style;
  let readyDrag = document.querySelector('#readyDrag'),
      offsetTop = document.querySelector("#overlay").offsetTop,
      offsetLeft = document.querySelector("#overlay").offsetLeft;
      
      
      if (event.target.id === "items")	{
        const sticker = document.getElementById("overlay");
        if (sticker.firstChild){
          sticker.removeChild(sticker.firstChild);
        }
        const stickerSrc = event.target.src;
        const stickerImg = document.createElement("img");
        stickerImg.setAttribute('id', 'readyDrag')
        overlay.right = "0";
        overlay.top = "0";
        stickerImg.src = stickerSrc;
        stickerImg.style.height = "50px";
        stickerImg.style.width = "50px";
        stickerImg.style.right = "50";
        stickerImg.style.top = "50";
        sticker.appendChild(stickerImg);
        document.querySelector("#startbutton").disabled = false;
        document.querySelector('#customSticker').style.visibility = "visible";
        return ;
      }
  if (!document.querySelector('#readyDrag')){
    return;
  } 
  let heightt = parseInt(readyDrag.style.height),
        width = parseInt(readyDrag.style.width),
        offsetWidth = document.querySelector('#videoElement').offsetWidth;
  
  if (event.target.id === "moins"){
    height = parseInt(readyDrag.style.height);
    if ((height - 30) < 50){
      return;
    } else {
      readyDrag.style.height = (height - 30)+"px";
      readyDrag.style.width = (height - 30)+"px";
    }
    return ;
  }
  if (event.target.id === "plus"){
    height = parseInt(readyDrag.style.height);
    if ((height) > 270 || offsetLeft <= 30
                            || offsetTop + heightt + 30 > offsetHeight
                            || offsetLeft + width + 30 > offsetWidth
                            || offsetTop + heightt < heightt){
        return;
    } else {
      readyDrag.style.height = (height + 30)+"px";
      readyDrag.style.width = (height + 30)+"px";
    }
    return;
  }
  if (event.target.id === "gauche"){
    let right = parseInt(overlay.right);
    if (offsetLeft <= 30){
      overlay.right = (right += offsetLeft)+"px";  
    } else {
      overlay.right = (right += 30)+"px";
    }
    return ;
  }
  if (event.target.id === "droite"){
    let right = parseInt(overlay.right);
    if (offsetLeft + width + 30 > offsetWidth){
      overlay.right = (offsetLeft + width - offsetWidth);
    } else {
      overlay.right = (right -= 30)+"px";
    }
    return ;
  }
  if (event.target.id === "bas"){
    let right = parseInt(overlay.top);
    if ((offsetTop + heightt) + 30 > offsetHeight){
      overlay.top = (offsetHeight - heightt)+"px";
    } else {
      overlay.top = (right += 30)+"px";
    }
    return ;
  }
  if (event.target.id === "haut"){
    if (((offsetTop + heightt) - 30) < heightt){
      return;
    }
    let right = parseInt(overlay.top);
    overlay.top = (right -= 30)+"px";
    return ;
  }
})


function getStickerInfo(){
  let h = document.querySelector("#videoElement").offsetHeight,
      w = document.querySelector("#videoElement").offsetWidth;
  
  let div = document.querySelector("#readyDrag");
  let pos = document.querySelector("#overlay");
  let offsetPicLeft = parseInt(pos.offsetLeft) * 640 / parseInt(w) ,
  offsetPicTop = parseInt(pos.offsetTop) * 480 / parseInt(h) ;
  
  let pic = div.src,
      width = parseInt(div.style.width) * 640 / w;
      height = parseInt(div.style.height) * 480 / h;
  let obj = {
              src: pic,
              width: width,
              height: height,
              x: offsetPicLeft,
              y: offsetPicTop,
            }
    return(obj);
  }
