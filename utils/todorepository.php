<?php

include_once 'jsonstorage.php';

class ToDoRepository extends JsonStorage {
    public function __construct() {
      parent::__construct('.todo.json');
    }
}