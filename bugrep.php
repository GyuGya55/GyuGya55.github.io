<?php

include_once './utils/seged.php';
include_once './utils/bugrepository.php';
include_once './utils/userrepository.php';


$userRepo = new UserRepository();
$users = $userRepo->all();

$bugRepo = new BugRepository();
$bugs0 = array_reverse($bugRepo->filter(function (&$item) {
    return $item['status'] == 0;
}), true);
$bugs1 = array_reverse($bugRepo->filter(function (&$item) {
    return $item['status'] == 1;
}), true);
$bugs2 = array_reverse($bugRepo->filter(function (&$item) {
    return $item['status'] == 2;
}), true);


session_start();


if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

if ($_POST) {
    if (!isset($_POST['category'])) {
        $_POST['category'] = 'else';
    }
    if (is_empty($_POST, 'name')) {
        $_POST['name'] = 'Unnamed';
    }
    if (is_empty($_POST, 'descr')) {
        $_POST['descr'] = 'No description...';
    }
    $_POST['status'] = 0;
    $_POST['correction'] = 'Not started!';
    $_POST['reporter'] = $_SESSION['user']['_id'];
    $_POST['time'] = time();
    $bugRepo->insert($_POST);
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    die();
}


function getIcon($category) {
    switch ($category) {
        case 'Game':
            return './logos/unreal.png';
        case 'Modelling':
            return './logos/blender.png';
        case 'Design':
            return './logos/design.png';
        case 'DevTool':
            return './logos/todomanager.png';
        case 'Else':
            return './logos/unknown.png';
    }
}

?>

<?php include './partials/header.php'?>

<h1>Bug Report</h1>

<div id='bugReportContainer'>
    <button id='addBug'>+ Report a new bug</button>
</div>

<h2>Current issues:</h2>

<p style='color:#ffcc00' class='center'>! Not started !</p>
<hr>
<?php if (count($bugs0) == 0): ?>
    <p>No issues found in this category</p>
<?php else: foreach ($bugs0 as $bug): ?>
    <a href='bug.php?<?=$bug["_id"]?>' class='bug' style='border: 2px solid <?=$users[$bug["reporter"]]["color"]?>'>
        <img src='<?=getIcon($bug["category"])?>'>
        <h2><?=$bug['name']?></h2>
        <p><?=$bug['descr']?></p>
    </a>
<?php endforeach; endif; ?>
<p><br></p>

<p style='color:#3366ff' class='center'>In progress...</p>
<hr>
<?php if (count($bugs1) == 0): ?>
    <p>No issues found in this category</p>
<?php else: foreach ($bugs1 as $bug): ?>
    <a href='bug.php?<?=$bug["_id"]?>' class='bug' style='border: 2px solid <?=$users[$bug["reporter"]]["color"]?>'>
        <img src='<?=getIcon($bug["category"])?>'>
        <h2><?=$bug['name']?></h2>
        <p><?=$bug['descr']?></p>
    </a>
<?php endforeach; endif; ?>
<p><br></p>

<p style='color:#33cc33' class='center'>Completed</p>
<hr>
<?php if (count($bugs2) == 0): ?>
    <p>No issues found in this category</p>
<?php else: foreach ($bugs2 as $bug): ?>
    <a href='bug.php?<?=$bug["_id"]?>' class='bug' style='border: 2px solid <?=$users[$bug["reporter"]]["color"]?>'>
        <img src='<?=getIcon($bug["category"])?>'>
        <h2><?=$bug['name']?></h2>
        <p><?=$bug['descr']?></p>
    </a>
<?php endforeach; endif; ?>

<script src='./js/addBug.js'></script>

<?php include './partials/footer.php'?>