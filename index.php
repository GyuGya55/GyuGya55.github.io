<?php

include_once './utils/seged.php';
include_once 'auth.php';

session_start();

unset($_SESSION['user']);

function validate($user, &$errors) {
    if (is_empty($user, 'name')) {
        $errors[] = "You must write a username!";
    }
    if (is_empty($user, 'password')) {
        $errors[] = "You must write a password!";
    }
    return count($errors) == 0;
}

if ($_POST) {
    $errors = [];
    $valid = validate($_POST, $errors);
    if ($valid) {
        $auth = new Auth();
        $loggedIn = $auth->login($_POST['name'], $_POST['password']);
        if ($loggedIn) {
            header('Location: profiles.php');
            exit();
        } else {
            $errors[] = "The username or the password is not correct!";
            $valid = false;
        }
    }
} else {
    $valid = true;
}

?>

<?php include './partials/header.php'?>

<h1 class='center'>Login</h1>

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
            <label for='name'>Name:</label>
            <input type='text' name='name' value="<?=isset($_POST['name']) ? $_POST['name'] : ''?>">
        </div>
        <div>
            <label for='password'>Password:</label>
            <input type='password' name='password'>
        </div>
        <button>Login</button>
    </div>
</form>


<?php include './partials/footer.php'?>