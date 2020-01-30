<?php

include_once './utils/seged.php';
include_once './utils/todorepository.php';
include_once './utils/userrepository.php';


$todoRepo = new ToDoRepository();
$todo = ($todoRepo->all())[$_SERVER['QUERY_STRING']];

$userRepo = new UserRepository();
$users = $userRepo->all();


session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

if ($_POST) {
    $id = $todo['_id'];
    if ($_POST['category'] != $todo['category']) {
        $category = $_POST['category'];
        $todoRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] === $id;
            },
            function (&$item) use ($category) {
                $item['category'] = $category;
            }
        );
    }
    if (!is_empty($_POST, 'name') && $_POST['name'] != $todo['name']) {
        $name = $_POST['name'];
        $todoRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($name) {
                $item['name'] = $name;
            }
        );
    }
    if (!is_empty($_POST, 'descr') && $_POST['descr'] != $todo['descr']) {
        $descr = $_POST['descr'];
        $todoRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($descr) {
                $item['descr'] = $descr;
            }
        );
    }
    if (isset($_POST['target']) && $_POST['target'] != $todo['target']) {
        $target = $_POST['target'];
        $todoRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($target) {
                $item['target'] = $target;
            }
        );
    }
    if (isset($_POST['target']) && count($_POST['target']) > 1 && isset($_POST['everybody'])) {
        $everybody = $_POST['everybody'];
        $todoRepo->upeverybody(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($everybody) {
                $item['everybody'] = $everybody;
            }
        );
    } 
    if ($_POST['date'] != $todo['date']) {
        $date = $_POST['date'];
        $todoRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($date) {
                $item['date'] = $date;
            }
        );
    }
    $todoRepo->update(
        function (&$item) use ($id) {
            return $item['_id'] == $id;
        },
        function (&$item) {
            $item['checked'] = [];
        }
    );
    header('Location: todos.php?'.$_SERVER['QUERY_STRING']);
    die();
}


?>

<?php include './partials/header.php'?>

<form method="POST">
    <div class="data" id='wideData'>
        <h1>Editing ToDo: <?=$todo['_id']?></h1>
        <div>
            <label for='category'>Category:</label><br>
            <label>
                <input type='radio' name='category' value='Game' <?= $todo['category'] == 'Game' ? 'checked' : '' ?>>
                <img src='./logos/unreal.png'>
                <span>Game</span>
            </label>
            <label>
                <input type='radio' name='category' value='Modelling' <?= $todo['category'] == 'Modelling' ? 'checked' : '' ?>>
                <img src='./logos/blender.png'>
                <span>Modelling</span>
            </label>
            <label>
                <input type='radio' name='category' value='Design' <?= $todo['category'] == 'Design' ? 'checked' : '' ?>>
                <img src='./logos/design.png'>
                <span>Design</span>
            </label>
            <label>
                <input type='radio' name='category' value='DevTool' <?= $todo['category'] == 'DevTool' ? 'checked' : '' ?>>
                <img src='./logos/todomanager.png'>
                <span>DevTool</span>
            </label>
            <label>
                <input type='radio' name='category' value='Else' <?= $todo['category'] == 'Else' ? 'checked' : '' ?>>
                <img src='./logos/unknown.png'>
                <span>Else</span>
            </label>
        </div>
        <p><br></p>
        <div>
            <label for='name'>Name:</label>
            <input type='text' name='name' value="<?=$todo['name']?>">
        </div>
        <div>
            <label for='descr'>Description:</label><br>
            <textarea name='descr' rows="10" cols="80" style="white-space: nowrap;"><?=$todo['descr']?></textarea>
        </div>
        <p></p>
        <div>
            <laber for='target'>Target person(people):</label><br>
            <?php foreach ($users as $user): ?>
                <label><input type='checkbox' name='target[]' value='<?=$user["_id"]?>' <?= in_array($user['_id'], $todo['target']) ? 'checked' : '' ?>><img src='<?=$user["profile_picture"]?>'><span style='color:<?=$user["color"]?>'><?=$user['name']?></span></label>
            <?php endforeach ?>
        </div>
        <div>
            <label for='everybody'>Everybody needs to complete:</label><br>
            <label class="switch">
                <input type="checkbox" name='everybody' <?= isset($todo['everybody']) ? 'checked' : '' ?>>
                <span class="slider round"></span>
            </label>
        </div>
        <div>
            <laber for='date'>Deadline:</label><br>
            <input type='date' name="date" value='<?=$todo["date"]?>'>
        </div>
        <p></p>
        <button>Save changes</button>
    </div>
</from>
<p class='center'>!!! WARNING !!! Deleting ToDo cannot be undone!</p>
<div class='delToDo' id='<?=$todo["_id"]?>'>Delete ToDo</div>

<script src='./js/delToDo.js'></script>

<?php include './partials/footer.php'?>