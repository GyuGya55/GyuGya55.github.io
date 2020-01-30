<?php

$array = explode('/', $_SERVER['DOCUMENT_URI']);
$currFile = end($array);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ToDo Manager</title>
    <link rel="stylesheet" href="./design/styles.css">
</head>
<body>
<header>
    <img src='./design/logo.png' id='logo'>
    <h1>ToDo Manager</h1>
    <p>Manage your tasks and ideas easly</p>
    <div>
        <?php if (isset($_SESSION['user'])): ?>
            <h2 style="color: <?=$_SESSION['user']['color']?>">
                <img src="<?=$_SESSION['user']['profile_picture']?>" id="profpic"/>
                Welcome: <?=$_SESSION['user']['name']?>
            </h2>
            <a href='settings.php'><img src='./design/settings.png' id='settings' /></a>
            <a href='index.php'><img src='./design/logout.png' id='logout' /></a>
        <?php endif; ?>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
        <nav>
            <ul>
                <li><a href='profiles.php' <?= $currFile == 'profiles.php' ? 'class=active' : '' ?>>Profiles</a></li>
                <li><a href='todos.php' <?= $currFile == 'todos.php' ? 'class=active' : '' ?>>ToDo-s</a></li>
                <li><a href='ideas.php' <?= $currFile == 'ideas.php' ? 'class=active' : '' ?>>Ideas</a></li>
                <li><a href='links.php' <?= $currFile == 'links.php' ? 'class=active' : '' ?>>Link Warehouse</a></li>
                <li><a href='bugrep.php' <?= $currFile == 'bugrep.php' ? 'class=active' : '' ?>>Bug Report</a></li>
            </ul>
        </nav>
    <?php endif; ?>
</header>
<main>