// idxscripts.js de CN8VX

// Script d'actualisation de contenu
setInterval(function () {
    actualiserContenu();
}, 1000);

function actualiserContenu() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("updatedlog").innerHTML = xhr.responseText;
        }
    };
    xhr.open("GET", "create_content.php", true);
    xhr.send();
}

// Script d'actualisation des informations matérielles
setInterval(function () {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("hardwareInfoContainer").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "hardinfo.php", true);
    xhttp.send();
}, 5000);
