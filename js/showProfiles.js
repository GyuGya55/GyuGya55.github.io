const section = document.querySelector('#prof');

showProfile(null);

async function showProfile(id) {
    let result = null;
    if (id !== null) {
        const resp = await fetch(
            'show-profile.php?' + id
        );
        result = await resp.text();
    } else {
        const resp = await fetch(
            'show-profile.php?'
        );
        result = await resp.text();
    }
    section.innerHTML = result;
    button = document.querySelector('#editAndSave');
    if (button) {
        button.addEventListener('click', editOrSave);
    }
    descpription = document.querySelector('#descr');
}

document.querySelector('aside').addEventListener('click', showAnother);

function showAnother(e) {
    showProfile(e.target.id);
}

let edit = true;
let descpription = null;
let button = null;

function editOrSave(e) {
    if (edit) {
        editDescr();
    } else {
        saveDescr();
    }
    edit = !edit;
}

function editDescr() {
    let html = descpription.innerHTML;
    html = html.substr(3);
    html = html.substr(0, html.length - 4);
    descpription.innerHTML = `<textarea rows="10" cols="100" style="white-space: nowrap;">${html}</textarea>`;
    button.innerHTML = "<img src='./design/save.png'>";
}

async function saveDescr() {
    let html = document.querySelector('textarea').value;
    descpription.innerHTML = `<p>${html}</p>`;
    button.innerHTML = "<img src='./design/edit.png'>";
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
        }
    };
    xhttp.open('POST', 'save-description.php', true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("descr=" + html);
}