<?php

include_once './utils/linkrepository.php';
include_once './utils/userrepository.php';

session_start();

$linkRepo = new LinkRepository();
$links = $linkRepo->all();

$userRepo = new UserRepository();
$users = $userRepo->all();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}


function getSrcTo($url) {
    if (strpos($url, 'youtube') !== false) {
        return './logos/youtube.png';
    }
    if (strpos($url, 'twitch') !== false) {
        return './logos/twitch.png';
    }
    if (strpos($url, 'drive.google') !== false) {
        return './logos/drive.png';
    }
    if (strpos($url, 'onedrive') !== false) {
        return './logos/onedrive.png';
    }
    if (strpos($url, 'github') !== false) {
        return './logos/github.png';
    }
    if (strpos($url, 'calendar') !== false) {
        return './logos/calendar.png';
    }
    if (strpos($url, 'docs') !== false || strpos($url, 'sheets') !== false || strpos($url, 'slides') !== false) {
        return './logos/docs.png';
    }
    return './logos/otherSite.png';
}


if ($_POST) {
    $exists = $linkRepo->getLinkByURL($_POST['url']);
    if (!$exists) {
        $_POST['creator'] = $_SESSION['user']['_id'];
        $linkRepo->insert($_POST);
    }
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    die();
}

?>

<?php include './partials/header.php'?>

<h1>Link Warehouse</h1>

<div id='linkContainer'>
    <?php foreach ($links as $link): ?>
        <div class='link' style='border: 2px solid <?=$users[$link["creator"]]["color"]?>'>
            <img src="<?=getSrcTo($link['url'])?>">
            <a href='<?=$link["url"]?>' target='_blank'><?=$link['name']?></a>
            <button class='deleteLink' onclick='deleteLink(event)'><img src='./design/delete.png' id="<?=$link['_id']?>"></button>
        </div>
    <?php endforeach; ?>
</div>

<div id='linkEditContainer'>
    <button id='addlink'>+ Adding new link</button>
</div>

<script src='./js/addLink.js'></script>

<?php include './partials/footer.php'?>