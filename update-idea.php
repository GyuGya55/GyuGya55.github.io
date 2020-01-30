<?php

include_once './utils/idearepository.php';

session_start();

$ideaRepo = new IdeaRepository();

$id = $_GET['id'];
$inc = ($_GET['inc'] == 'true');
$userID = $_SESSION['user']['_id'];


if ($inc) {
    $ideaRepo->update(
        function (&$item) use ($id) {
            return $item['_id'] === $id;
        },
        function (&$item) use ($userID){
            if (!in_array($userID, $item['support'])) {
                array_push($item['support'], $userID);
            }
        }
    );
} else {
    $ideaRepo->update(
        function (&$item) use ($id) {
            return $item['_id'] === $id;
        },
        function (&$item) use ($userID) {
            if (in_array($userID, $item['support'])) {
                $key = array_search($userID, $item['support']);
                unset($item['support'][$key]);
            }
        }
    );
    $ideaRepo->delete( function (&$item) {
        return count($item['support']) == 0;
    });
}
