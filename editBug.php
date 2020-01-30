<?php

include_once './utils/seged.php';
include_once './utils/bugrepository.php';
include_once './utils/userrepository.php';


$bugRepo = new BugRepository();
$bug = ($bugRepo->all())[$_SERVER['QUERY_STRING']];

$userRepo = new UserRepository();
$user = ($userRepo->all())[$bug['reporter']];


session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

if ($_POST) {
    $id = $bug['_id'];
    if ($_POST['category'] != $bug['category']) {
        $category = $_POST['category'];
        $bugRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] === $id;
            },
            function (&$item) use ($category) {
                $item['category'] = $category;
            }
        );
    }
    if (!is_empty($_POST, 'name') && $_POST['name'] != $bug['name']) {
        $name = $_POST['name'];
        $bugRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($name) {
                $item['name'] = $name;
            }
        );
    }
    if (!is_empty($_POST, 'descr') && $_POST['descr'] != $bug['descr']) {
        $descr = $_POST['descr'];
        $bugRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($descr) {
                $item['descr'] = $descr;
            }
        );
    }
    if ($_POST['status'] != $bug['status']) {
        $status = $_POST['status'];
        $bugRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($status) {
                $item['status'] = $status;
            }
        );
    }
    if (!is_empty($_POST, 'correction') && $_POST['correction'] != $bug['correction']) {
        $correction = $_POST['correction'];
        $bugRepo->update(
            function (&$item) use ($id) {
                return $item['_id'] == $id;
            },
            function (&$item) use ($correction) {
                $item['correction'] = $correction;
            }
        );
    }
    header('Location: bug.php?'.$_SERVER['QUERY_STRING']);
    die();
}


?>

<?php include './partials/header.php'?>

<form method="POST">
    <div class="data" id='wideData'>
        <h1>Editing bug: <?=$bug['_id']?></h1>
        <div>
            <label for='category'>Category:</label><br>
            <label>
                <input type='radio' name='category' value='Game' <?= $bug['category'] == 'Game' ? 'checked' : '' ?>>
                <img src='./logos/unreal.png'>
                <span>Game</span>
            </label>
            <label>
                <input type='radio' name='category' value='Modelling' <?= $bug['category'] == 'Modelling' ? 'checked' : '' ?>>
                <img src='./logos/blender.png'>
                <span>Modelling</span>
            </label>
            <label>
                <input type='radio' name='category' value='Design' <?= $bug['category'] == 'Design' ? 'checked' : '' ?>>
                <img src='./logos/design.png'>
                <span>Design</span>
            </label>
            <label>
                <input type='radio' name='category' value='DevTool' <?= $bug['category'] == 'DevTool' ? 'checked' : '' ?>>
                <img src='./logos/todomanager.png'>
                <span>DevTool</span>
            </label>
            <label>
                <input type='radio' name='category' value='Else' <?= $bug['category'] == 'Else' ? 'checked' : '' ?>>
                <img src='./logos/unknown.png'>
                <span>Else</span>
            </label>
        </div>
        <p><br></p>
        <div>
            <label for='name'>Name:</label>
            <input type='text' name='name' value="<?=$bug['name']?>">
        </div>
        <p class='center'>Reporter: <b style='color: <?=$user["color"]?>'><?=$user['name']?></b></p>
        <p class='center'>Time of report: <?=date('Y.m.d H:i:s', $bug['time'])?></p>
        <div>
            <label for='descr'>Description:</label><br>
            <textarea name='descr' rows="10" cols="80" style="white-space: nowrap;"><?=$bug['descr']?></textarea>
        </div>
        <p></p>
        <div>
            <label for='status'>Status:</label><br>
            <select name='status'>
                <option value=0 <?= $bug['status'] == 0 ? 'selected' : '' ?> style='color:#ffcc00'>! Not started !</option>
                <option value=1 <?= $bug['status'] == 1 ? 'selected' : '' ?> style='color:#3366ff'>In progress...</option>
                <option value=2 <?= $bug['status'] == 2 ? 'selected' : '' ?> style='color:#33cc33'>Completed</option>
            </select>
        </div>
        <p></p>
        <div>
            <label for='correction'>Correction:</label><br>
            <textarea name='correction' rows="10" cols="80" style="white-space: nowrap;"><?=$bug['correction']?></textarea>
        </div>
        <button>Save changes</button>
    </div>
</from>
<p class='center'>!!! WARNING !!! Deleting issue cannot be undone!</p>
<div class='delIssue' id='<?=$bug["_id"]?>'>Delete issue</div>

<script src='./js/delIssue.js'></script>

<?php include './partials/footer.php'?>