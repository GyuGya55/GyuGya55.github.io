document.querySelector('.delIssue').addEventListener('click', deleteIssue);

async function deleteIssue(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('type', 'bug');
    const resp = await fetch(
        'delete.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    window.location.replace('bugrep.php');
}