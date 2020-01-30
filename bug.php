<?php

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

function getState($status){
    switch ($status) {
        case 0:
            return '<i style="color:#ffcc00">! Not started !</i>';
        case 1:
            return '<i style="color:#3366ff">In progress...</i>';
        case 2:
            return '<i style="color:#33cc33">Completed</i>';
    }
}

?>

<?php include './partials/header.php'?>

<h1>
    Reported bug: <?=$bug['_id']?>
    <a href='editBug.php?<?=$_SERVER['QUERY_STRING']?>'><img src='./design/edit.png'></a>
</h1>

<img src='<?=getIcon($bug["category"])?>' id='categoryIcon' title='<?=$bug["category"]?>'>
<h2 class='center'><?=$bug['name']?></h2>
<p class='center'>Reporter: <b style='color: <?=$user["color"]?>'><?=$user['name']?></b></p>
<p class='center'>Time of report: <?=date('Y.m.d H:i:s', $bug['time'])?></p>
<p class='center'>Status: <?=getState($bug['status'])?></p>
<p><br></p>
<p class='center'>Description:</p>
<hr>
<p><?=$bug['descr']?></p>
<p><br></p>
<p class='center'>Correction:</p>
<hr>
<p><?=$bug['correction']?></p>
<p><br></p>

<?php include './partials/footer.php'?>