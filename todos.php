<?php

include_once './utils/seged.php';
include_once './utils/todorepository.php';
include_once './utils/userrepository.php';


$userRepo = new UserRepository();
$users = $userRepo->all();


session_start();

$id = (isset($_POST['id']) ? $_POST['id'] : $_SESSION['user']['_id']);
$currUserID = $_SESSION['user']['_id'];


$todoRepo = new ToDoRepository();
$todos = $todoRepo->filter(function (&$item) use ($id) {
    return in_array($id, $item['target']);
});

$unCompleteTodos = array_filter($todos, function (&$item) {
    return count($item['checked']) == 0;
});
$completedTodos = array_filter($todos, function (&$item) {
    return count($item['checked']) > 0;
});


$lateTodos = array_filter($unCompleteTodos, function (&$item) {
    return ($item['date'] != '' && strtotime($item['date']) < time());
});
$dailyTodos = array_filter($unCompleteTodos, function (&$item) {
    return (strtotime($item['date']) >= time() && strtotime($item['date']) < time() + (60 * 60 * 24));
});
$weeklyTodos = array_filter($unCompleteTodos, function (&$item) {
    return (strtotime($item['date']) >= time() + (60 * 60 * 24) && strtotime($item['date']) < time() + (60 * 60 * 24 * 7));
});
$monthlyTodos = array_filter($unCompleteTodos, function (&$item) {
    return (strtotime($item['date']) >= time() + (60 * 60 * 24 * 7)  && strtotime($item['date']) < time() + (60 * 60 * 24 * 31));
});
$laterTodos = array_filter($unCompleteTodos, function (&$item) {
    return strtotime($item['date']) >= time() + (60 * 60 * 24 * 31);
});
$noDeadlineTodos = array_filter($unCompleteTodos, function (&$item) {
    return $item['date'] == '';
});

function cmp($a, $b)
{
    return strcmp($a["date"], $b["date"]);
}

usort($lateTodos, "cmp");
usort($dailyTodos, "cmp");
usort($weeklyTodos, "cmp");
usort($monthlyTodos, "cmp");
usort($laterTodos, "cmp");


if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

if ($_POST) {
    if (isset($_POST['target'])) {
        if (!isset($_POST['category'])) {
            $_POST['category'] = 'Else';
        }
        if (is_empty($_POST, 'name')) {
            $_POST['name'] = 'Unnamed';
        }
        if (is_empty($_POST, 'descr')) {
            $_POST['descr'] = 'No description...';
        }
        if (count($_POST['target']) == 1 && isset($_POST['everybody'])) {
            unset($_POST['everybody']);
        }
        $_POST['checked'] = [];
        $todoRepo->insert($_POST);
        unset($_POST);
        header("Location: ".$_SERVER['PHP_SELF']);
        die();
    }
}


function getIcon($category) {
    switch ($category) {
        case 'Game':
            return './logos/programming.png" title="Category: Game';
        case 'Modelling':
            return './logos/modelling.png" title="Category: Modelling';
        case 'Design':
            return './logos/designMini.png" title="Category: Desgin';
        case 'DevTool':
            return './logos/devtool.png" title="Category: DevTool';
        case 'Else':
            return './logos/else.png" title="Category: Else';
    }
}


?>

<?php include './partials/header.php'?>

<h1>ToDo-s</h1>

<div id='newToDoContainer'>
    <button id='addToDo'>+ Create new ToDo</button>
</div>

<form method='POST'>
    <div class="data">
        <div>
            <label for='id'>Selec user:</label><br>
            <select name='id'>
                <?php foreach ($users as $user): ?>
                    <option value='<?=$user["_id"]?>' <?= $user["_id"] == $id ? 'selected' : '' ?> style='color:<?=$user["color"]?>'><?=$user["name"]?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button>See ToDo-s</button>
    </div>
</form>


<!-- LATE -->
<?php if (count($lateTodos) > 0): ?>
<p style='color:red' class='center'>!!! LATE !!!</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($lateTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr' style='color:red'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline' style='color:red'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($lateTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr' style='color:red'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline' style='color:red'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- DAILY -->
<?php if (count($dailyTodos) > 0): ?>
<p class='center'>DAILY</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($dailyTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($dailyTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- WEEKLY -->
<?php if (count($weeklyTodos) > 0): ?>
<p class='center'>WEEKLY</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($weeklyTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($weeklyTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- MONTHLY -->
<?php if (count($monthlyTodos) > 0): ?>
<p class='center'>MONTHLY</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($monthlyTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($monthlyTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- LATER -->
<?php if (count($laterTodos) > 0): ?>
<p class='center'>LATER THAN A MONTH</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($laterTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($laterTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- NO DEADLINE -->
<?php if (count($noDeadlineTodos) > 0): ?>
<p class='center'>NO DEADLINE</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($noDeadlineTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>No deadline</i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($noDeadlineTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>No deadline</i></p>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>



<!-- COMPLETED -->
<?php if (count($completedTodos) > 0): ?>
<p style='color:#33cc33' class='center'>COMPLETED</p>
<hr>

<table>
    <tr>
        <th>Solo ToDo</th>
        <th>Team ToDo</th>
    </tr>
    <tr>
<td class='soloToDo'>
<?php foreach ($completedTodos as $todo): if (count($todo['target']) == 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
    </div>
<?php endif; endforeach; ?>
</td>

<td class='multiToDo'>
<?php foreach ($completedTodos as $todo): if (count($todo['target']) > 1): ?>
    <div class='todo' style='border: 2px solid <?=$users[$id]["color"]?>'>
        <span style='background-color:<?=$users[$id]["color"]?>'>
            <img src="<?=getIcon($todo['category'])?>">
            <?=$todo['name']?>
        </span>
        <a href='edit-todo.php?<?=$todo["_id"]?>' class='edit'><img src='./design/edit.png'></a>
        <?php foreach ($todo['target'] as $userID): if ($userID != $id): ?>
            <span class='targetMarker' style='background-color:<?=$users[$userID]["color"]?>'></span>
        <?php endif; endforeach; ?>
        <p class='tododescr'>
            <?php if (in_array($currUserID, $todo['target'])): ?>
                <button class='completeToDo' onclick='<?= in_array($currUserID, $todo["checked"]) ? "unCompleteToDo(event)" : "completeToDo(event)" ?>'>
                    <img src='./design/<?= in_array($currUserID, $todo["checked"]) ? "" : "un" ?>checked.png' id="<?=$todo['_id']?>">
                </button>
            <?php endif; ?>
            <?=$todo['descr']?>
        </p>
        <?php if ($todo['date'] == ''): ?>
            <p class='deadline'><i>No deadline</i></p>
        <?php else: ?>
            <p class='deadline'><i>Deadline: <?=$todo['date']?></i></p>
        <?php endif; ?>
    </div>
<?php endif; endforeach; endif; ?>
</td>
    </tr>
</table>

<script src='./js/manageToDos.js'></script>

<?php include './partials/footer.php'?>