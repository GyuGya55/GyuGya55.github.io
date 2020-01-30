document.querySelector('#addlink').addEventListener('click', form);
const container = document.querySelector('#linkEditContainer');

function form(e) {
    container.innerHTML = `
    <form method="POST">
        <div class="data" id='wideData'>
            <h2>Adding new link</h2>
            <div>
                <label for='name'>Name:</label>
                <input type='text' name='name'>
            </div>
            <div>
                <label for='url'>URL:</label>
                <input type='text' name='url'>
            </div>
            <button>Add link</button>
        </div>
    </from>`;
}

async function deleteLink(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('type', 'link');
    const resp = await fetch(
        'delete.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    location.reload();
}