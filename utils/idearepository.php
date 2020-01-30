<?php

include_once 'jsonstorage.php';

class IdeaRepository extends JsonStorage {
    public function __construct() {
      parent::__construct('.idea.json');
    }
}