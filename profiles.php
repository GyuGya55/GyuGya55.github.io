<?php

include_once './utils/userrepository.php';

session_start();

$userRepo = new UserRepository();
$users = $userRepo->all();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

?>

<?php include './partials/header.php'?>

<h1>Profiles</h1>

<aside>
    <ul>
        <?php foreach ($users as $user): ?>
            <li style='color: <?=$user["color"]?>' id = '<?=$user["_id"]?>'>
                <hr>
                <img class='minipic' src='<?=$user["profile_picture"]?>'>
                <h2 class='pushed'><?=$user['name']?></h2>
                <?php $titles = explode('|', $user['title']); ?>
                <?php foreach ($titles as $title): ?>
                    <br><?=$title?>
                <?php endforeach; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>

<section id='prof'>

</section>

<script src='./js/showProfiles.js'></script>

<?php include './partials/footer.php'?>