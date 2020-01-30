<?php

include_once './utils/todorepository.php';

session_start();

$todoRepo = new ToDoRepository();

$id = $_GET['id'];
$inc = ($_GET['inc'] == 'true');
$userID = $_SESSION['user']['_id'];


if ($inc) {
    $todoRepo->update(
        function (&$item) use ($id) {
            return $item['_id'] === $id;
        },
        function (&$item) use ($userID){
            if (!in_array($userID, $item['checked'])) {
                array_push($item['checked'], $userID);
            }
        }
    );
} else {
    $todoRepo->update(
        function (&$item) use ($id) {
            return $item['_id'] === $id;
        },
        function (&$item) use ($userID) {
            if (in_array($userID, $item['checked'])) {
                $key = array_search($userID, $item['checked']);
                unset($item['checked'][$key]);
            }
        }
    );
}
