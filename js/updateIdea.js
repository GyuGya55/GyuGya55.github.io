async function supportIdea(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('inc', true);
    const resp = await fetch(
        'update-idea.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    location.reload();
}


async function unSupportIdea(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('inc', false);
    const resp = await fetch(
        'update-idea.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    location.reload();
}