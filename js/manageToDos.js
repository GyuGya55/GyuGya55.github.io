document.querySelector('#addToDo').addEventListener('click', form);
const container = document.querySelector('#newToDoContainer');

async function form(e) {
    const resp = await fetch(
        'userlist.php'
    );
    const userlist = await resp.text();
    container.innerHTML = `
    <form method="POST">
        <div class="data" id='wideData'>
            <h2>Creating new ToDo</h2>
            <div>
                <label for='category'>Category:</label><br>
                <label><input type='radio' name='category' value='Game'><img src='./logos/unreal.png'><span>Game</span></label>
                <label><input type='radio' name='category' value='Modelling'><img src='./logos/blender.png'><span>Modelling</span></label>
                <label><input type='radio' name='category' value='Design'><img src='./logos/design.png'><span>Design</span></label>
                <label><input type='radio' name='category' value='DevTool'><img src='./logos/todomanager.png'><span>DevTool</span></label>
                <label><input type='radio' name='category' value='Else'><img src='./logos/unknown.png'><span>Else</span></label>
            </div>
            <div>
                <label for='name'>Name:</label>
                <input type='text' name='name'>
            </div>
            <div>
                <label for='descr'>Description:</label><br>
                <textarea name='descr' placeholder='Enter description here' rows="10" cols="80" style="white-space: nowrap;"></textarea>
            </div>
            <div>
                <laber for='target'>Target person(people):</label><br>
                ${userlist}
            </div>
            <div>
                <label for='everybody'>Everybody needs to complete:</label><br>
                <label class="switch">
                    <input type="checkbox" name='everybody'>
                    <span class="slider round"></span>
                </label>
            </div>
            <div>
                <laber for='date'>Deadline:</label><br>
                <input type='date' name="date">
            </div>
            <button>Create ToDo</button>
        </div>
    </from>`;
}

async function completeToDo(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('inc', true);
    const resp = await fetch(
        'update-todo.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    location.reload();
}


async function unCompleteToDo(e) {
    let url = new URLSearchParams();
    url.append('id', e.target.id);
    url.append('inc', false);
    const resp = await fetch(
        'update-todo.php?' + url.toString()
    );
    result = await resp.text();
    console.log(result);
    location.reload();
}
