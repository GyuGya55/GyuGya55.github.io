<?php

include_once './utils/seged.php';
include_once './utils/idearepository.php';
include_once './utils/userrepository.php';

$ideaRepo = new IdeaRepository();
$ideas = array_reverse($ideaRepo->all(), true);

$userRepo = new UserRepository();
$users = $userRepo->all();

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}


if ($_POST) {
    if (is_empty($_POST, 'idea')) {
        $_POST['idea'] = "Oh, it's just an empty idea...";
    }
    $_POST['publisher'] = $_SESSION['user']['_id'];
    $_POST['time'] = time();
    $_POST['support'] = [$_SESSION['user']['_id']];
    $ideaRepo->insert($_POST);
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    die();
}

?>

<?php include './partials/header.php'?>

<h1>Ideas</h1>

<form method='POST'>
    <textarea name='idea' id='newIdea' placeholder='Oh, I have an idea...' rows="10" cols="80" style="white-space: nowrap;"></textarea>
    <button id='publish'>Publish</button>
</form>
<hr>
<?php if (count($ideas) == 0): ?>
    <p class='center'>Ooops.. No ideas found</p>
<?php else: foreach ($ideas as $idea): ?>
    <div class='idea'>
        <img src='<?=$users[$idea["publisher"]]["profile_picture"]?>'>
        <p class='ideaHeader'><span style='color:<?=$users[$idea["publisher"]]["color"]?>'><?=$users[$idea["publisher"]]["name"]?></span> published an idea at <small><i><?=date('Y.m.d H:i:s', $idea['time'])?></i></small></p>
        <p class='ideaNote'><?=$idea['idea']?></p>
        <p class='ideaFooter' <?= in_array($_SESSION['user']['_id'], $idea['support']) ? 'style="color:#33cc33"' : '' ?>>Supported by <b><?=count($idea['support'])?></b> out of <b><?=count($users)?></b> <?= in_array($_SESSION['user']['_id'], $idea['support']) ? '(including you)' : '' ?></p>
        <?php if (in_array($_SESSION['user']['_id'], $idea['support'])): ?>
            <button class='updateIdea' onclick='unSupportIdea(event)'><img src='./design/unsupport.png' id="<?=$idea['_id']?>"></button>
        <?php else: ?>
            <button class='updateIdea' onclick='supportIdea(event)'><img src='./design/support.png' id="<?=$idea['_id']?>"></button>
        <?php endif; ?>
    </div>
<?php endforeach; endif;?>

<script src='./js/updateIdea.js'></script>

<?php include './partials/footer.php'?>