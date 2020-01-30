<?php

include_once './utils/seged.php';
include_once 'auth.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

function validate($user, &$errors) {
    if (is_empty($user, 'old')) {
        $errors[] = "You must write the old password!";
    }
    if (is_empty($user, 'new')) {
        $errors[] = "You must write a new password!";
    }
    return count($errors) == 0;
}

if ($_POST) {
    if (!isset($_POST['color'])) {
        $errors = [];
        $valid = validate($_POST, $errors);
        if ($valid) {
            $auth = new Auth();
            $success = $auth->reset($_SESSION['user']['name'], $_POST['old'], $_POST['new']);
            if (!$success) {
                $errors[] = "The old password is not correct!";
                $valid = false;
            }
        }
    } else {
        $valid = true;
        $auth = new Auth();
        $auth->setColor($_SESSION['user']['name'], $_POST['color']);
        $_SESSION['user']['color'] = $_POST['color'];
    }
} else {
    $valid = true;
}


?>

<?php include './partials/header.php'?>

<h1 class='center'>Settings</h1>
<p class='center' style="color: <?=$_SESSION['user']['color']?>">Welcome: <b><?=$_SESSION['user']['name']?></b> (<?=$_SESSION['user']['title']?>)</p>

<h2 class='center'>Changing password</h2>

<?php if(!$valid) : ?>
    <div id='errordiv'>
        <h2>Errors!</h2>
        <ul>
            <?php foreach($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method='POST'>
    <div class="data">
        <div>
            <label for='old'>Old password:</label>
            <input type='password' name='old'>
        </div>
        <div>
            <label for='new'>New password:</label>
            <input type='password' name='new'>
        </div>
        <button>Change password</button>
    </div>
</form>

<h2 class='center'>Changing color</h2>

<form method='POST'>
    <div class="data">
        <div>
            <label for='color'>New color:</label><br>
            <input type='color' name='color' value="<?=$_SESSION['user']['color']?>">
        </div>
        <button>Change Color</button>
    </div>
</form>

<p class='center'>To change anything else please turn to DevTool Developer</p>


<?php include './partials/footer.php'?>