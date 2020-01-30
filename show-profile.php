<?php

include_once './utils/userrepository.php';

session_start();

$query = $_SERVER['QUERY_STRING'];

if ($query) {
    $userRepo = new UserRepository();
    $user = ($userRepo->all())[$query];
} else {
    $user = $_SESSION['user'];
}

?>

<img src='<?=$user["profile_picture"]?>'>
<h1 class='center' style='color: <?=$user["color"]?>'><?=$user['name']?></h1>
<h2 class='center' style='color: <?=$user["color"]?>'>(<?=$user['title']?>)</h2>
<p>
    Description:
    <?= $_SESSION['user']['_id'] == $user['_id'] ? "<button id='editAndSave'><img src='./design/edit.png'></button>" : "" ?>
</p>
<div id='descr'><p><?=$user['description']?></p></div>