window.onload = function() {
    initHandlers();
}

function initHandlers() {
    if (document.getElementById('cam'))
    {
        var video = document.querySelector("#video");
        var uploadedFile = document.querySelector(".upload-file");
        var icons = document.querySelectorAll(".icon");
        var captureButton = document.querySelector(".capture-button");
    
        initCameraHandler(video);
        initIconsHandler(icons);
    
        captureButton.addEventListener("click", capture(icons));
        initUploadHanlder();
    }
}

function initCameraHandler(video) {
    if (navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(error) {
                console.log("Something went wrong!");
            });
    }
}

function initIconsHandler(icons) {
    for (let index = 0; index < icons.length; index++) {
        icons[index].addEventListener("click", selectIcon(icons, index));

    }
}

function selectIcon(icons, index) {
    return function() {
        for (let i = 0; i < icons.length; i++) {
            icons[i].style.border = "none";
        }
        var toDelete = document.querySelector("#icon");
        if (toDelete) {
            document.querySelector(".cam").removeChild(toDelete);
        }
        icons[index].style.border = "1em solid #2e2e2e";
        var img = icons[index].childNodes[0];
        img.dataset.selected = "yes";

        var icon = img.cloneNode(true);
        icon.setAttribute("width", "80");
        icon.setAttribute("height", "60");
        icon.setAttribute("alt", "New icon");
        icon.setAttribute("id", "icon");
        icon.style.postion = "absolute";
        icon.style.cursor = "move";
        positionIcon(icon);
        document.querySelector("#icon").onwheel = zoom;
        dragElement("#icon");
    }
}

function positionIcon(icon) {
    var rectSource = document.querySelector("#video") || document.querySelector(".upload-file");
    var rect = rectSource.getBoundingClientRect();
    var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    position = { top: rect.top + scrollTop + 150, left: rect.left + scrollLeft + 190 };
    icon.style.top = position.top + "px";
    icon.style.left = position.left + "px";
    icon.style.position = "absolute";
    document.querySelector(".cam").appendChild(icon);
}

function capture(icons) {
    return function() {
        var selected = 0;
        var isUploaded = 1;
        for (let i = 0; i < icons.length; i++) {
            if (icons[i].childNodes[0].dataset.selected === "yes") {
                selected = 1;
            }
        }
        if (!selected) {
            alert("You have to select an icon !");
            return;
        }

        var canvas = document.querySelector("#canvas");
        var canvas2 = document.querySelector("#canvas2");
        var context = canvas.getContext("2d");
        var context2 = canvas2.getContext("2d");

        if (document.querySelector("#img-captured")) {
            context.clearRect(0, 0, canvas.width, canvas.height)
            document.querySelector("#img-captured").parentNode.removeChild(document.querySelector("#img-captured"));
        }
        if (document.querySelector("#img-captured2")) {
            context2.clearRect(0, 0, canvas2.width, canvas2.height)
            document.querySelector("#img-captured2").parentNode.removeChild(document.querySelector("#img-captured2"));
        }

        if (document.querySelector("#image"))
        {
            var drawSource = document.querySelector("#image");
            isUploaded = 2;
        }
        else
            var drawSource = document.querySelector("#video");

        context.drawImage(drawSource, 0, 0, 400, 300);
        var ico = document.querySelector("#icon");
        ico.positionLeft = ico.offsetLeft - drawSource.offsetLeft;
        ico.positionTop = ico.offsetTop - drawSource.offsetTop;
        context2.drawImage(ico, ico.positionLeft, ico.positionTop, ico.width, ico.height);
        
        var img = document.createElement("img")
        img.setAttribute("width", "400");
        img.setAttribute("height", "300");
        img.setAttribute("alt", "captured");
        img.setAttribute("id", "img-captured");
        img.setAttribute("src", canvas.toDataURL("image/png"));
        document.querySelector(".canvas-wrapper").insertBefore(img, canvas);

        var img2 = document.createElement("img")
        img2.setAttribute("width", "400");
        img2.setAttribute("height", "300");
        img2.setAttribute("alt", "captured");
        img2.setAttribute("id", "img-captured2");
        img2.setAttribute("src", canvas2.toDataURL("image/png"));
        document.querySelector(".canvas-wrapper").insertBefore(img2, canvas);
        

        for (let i = 0; i < icons.length; i++) {
            icons[i].style.border = "none";
            icons[i].childNodes[0].dataset.selected = "no";
        }
        var toDelete = document.querySelector("#icon");
        var icon_source = toDelete.src;
        if (toDelete) {
            document.querySelector(".cam").removeChild(toDelete);
        }

        var save = document.querySelector("#img-form");
        save.style.display = "initial";
        var img_path = document.querySelector("#img_path");
        img_path.setAttribute("value", img.src);
        var img_path2 = document.querySelector("#img_path2");
        img_path2.setAttribute("value", document.querySelectorAll("img")[isUploaded].src);
    }
}

function dragElement(elmnt) {
    var pos1 = 0;
    var pos2 = 0;
    var pos3 = 0;
    var pos4 = 0;
    var ico = document.querySelector(elmnt);
    ico.onmousedown = dragMouseDown;
}

function dragMouseDown(e) {
    e = e || window.event;
    e.preventDefault();
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.onmouseup = closeDragElement;
    document.onmousemove = elementDrag;
}

function elementDrag(e) {
    e = e || window.event;
    e.preventDefault();
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    var elem = document.querySelector("#icon");
    elem.style.top = (elem.offsetTop - pos2) + "px";
    elem.style.left = (elem.offsetLeft - pos1) + "px";
}

function closeDragElement() {
    document.onmouseup = null;
    document.onmousemove = null;
    checkPosition();
}

function checkPosition() {
    var ico = document.querySelector("#icon");
    var icon = ico.getBoundingClientRect()
    var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    var positionIco = { top: icon.top + scrollTop, left: icon.left + scrollLeft };

    var rectSource = document.querySelector("#video") || document.querySelector(".upload-file");
    var rect = rectSource.getBoundingClientRect();
    var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft
    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    var positionRect = { top: rect.top + scrollTop, left: rect.left + scrollLeft };

    if (positionIco.top < positionRect.top ||
        positionIco.left < positionRect.left ||
        positionIco.left + ico.width > positionRect.left + 400 ||
        positionIco.top + ico.height > positionRect.top + 300) {
        positionIcon(ico);
    }
}

function zoom(e) {
    e.preventDefault();
    //icon = document.querySelector("#icon");
    if (event.deltaY > 0 && icon.width < 200 && icon.height < 150) {
        icon.width += 10;
        icon.height += 10;
        checkPosition();
    }
    if (event.deltaY < 0 && icon.width > 80 && icon.height > 80) {
        icon.width -= 10;
        icon.height -= 10;
        checkPosition();
    }
}

function upload(elemt) {
    var array = elemt.split('\\');
    var name = array[array.length - 1];
    var img_path = "public/assets/upload/" + name;
    if (document.querySelector("#video"))
        document.querySelector(".cam").removeChild(document.querySelector("#video"));
    if (document.querySelector("#image"))
        document.querySelector(".cam").removeChild(document.querySelector("#image"));
    var img = document.createElement("img")
    img.setAttribute("width", "400");
    img.setAttribute("height", "300");
    img.setAttribute("alt", "New image");
    img.setAttribute("src", img_path);
    img.setAttribute("class", "upload-file");
    img.setAttribute("id", "image");
    document.querySelector(".cam").insertBefore(img, document.querySelector(".capture-button"));

}

function initUploadHanlder() {
    var form = document.getElementById('file-form');
    var fileSelect = document.getElementById('myfile');
    var uploadButton = document.getElementById('submit');
    var statusDiv = document.getElementById('status');
    form.onsubmit = function(event) {
        event.preventDefault();
        var files = fileSelect.files;

        var formData = new FormData(form);

        var file = files[0];
        if (!file)
            return ;
        if (file && !file.type.match('image.*')) {
            alert('You cannot upload this file because it’s not an image.');
            return;
        }

        if (file && file.size >= 2000000) {
            alert('You cannot upload this file because its size exceeds the maximum limit of 2 MB.');
            return;
        }
        var xhr = new XMLHttpRequest();

        xhr.open('POST', '/camagru/image/upload', true);

        xhr.send(formData);

        xhr.onload = function() {
            if (xhr.status === 200) {
                upload(fileSelect.value);
                alert("upload with success");
            } else {
                alert('An error occurred during the upload. Try again.');
            }
        };

    }
}