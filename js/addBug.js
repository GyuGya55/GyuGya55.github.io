document.querySelector('#addBug').addEventListener('click', form);
const container = document.querySelector('#bugReportContainer');

function form(e) {
    container.innerHTML = `
    <form method="POST">
        <div class="data" id='wideData'>
            <h2>Report a new bug</h2>
            <div>
                <label for='category'>Category:</label><br>
                <label><input type='radio' name='category' value='Game'><img src='./logos/unreal.png'><span>Game</span></label>
                <label><input type='radio' name='category' value='Modelling'><img src='./logos/blender.png'><span>Modelling</span></label>
                <label><input type='radio' name='category' value='Design'><img src='./logos/design.png'><span>Design</span></label>
                <label><input type='radio' name='category' value='DevTool'><img src='./logos/todomanager.png'><span>DevTool</span></label>
                <label><input type='radio' name='category' value='Else'><img src='./logos/unknown.png'><span>Else</span></label>
            </div>
            <p><br></p>
            <div>
                <label for='name'>Name:</label>
                <input type='text' name='name'>
            </div>
            <div>
                <label for='descr'>Description:</label><br>
                <textarea name='descr' placeholder='Enter description here' rows="10" cols="80" style="white-space: nowrap;"></textarea>
            </div>
            <button>Report bug</button>
        </div>
    </from>`;
}