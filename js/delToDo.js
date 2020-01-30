document.querySelector('.delToDo').addEventListener('click', deleteToDo);

async function deleteToDo(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('type', 'todo');
    const resp = await fetch(
        'delete.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    window.location.replace('todos.php');
}