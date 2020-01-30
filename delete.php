<?php

include_once './utils/linkrepository.php';
include_once './utils/bugrepository.php';
include_once './utils/todorepository.php';

if (isset($_GET['type'])){
    switch ($_GET['type']){
        case 'link':
            $linkRepo = new LinkRepository();
            $id = $_GET['id'];
            $linkRepo->delete(function (&$item) use ($id) {
                return $item['_id'] === $id;
            });
            break;
        case 'bug':
            $bugRepo = new BugRepository();
            $id = $_GET['id'];
            $bugRepo->delete(function (&$item) use ($id) {
                return $item['_id'] === $id;
            });
        case 'todo':
            $todoRepo = new ToDoRepository();
            $id = $_GET['id'];
            $todoRepo->delete(function (&$item) use ($id) {
                return $item['_id'] === $id;
            });
    }
} 