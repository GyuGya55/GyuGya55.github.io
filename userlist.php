<?php

include_once './utils/userrepository.php';

session_start();

$userRepo = new UserRepository();
$users = $userRepo->all();

?>

<?php foreach ($users as $user): ?>
    <label><input type='checkbox' name='target[]' value='<?=$user["_id"]?>'><img src='<?=$user["profile_picture"]?>'><span style='color:<?=$user["color"]?>'><?=$user['name']?></span></label>
<?php endforeach ?>