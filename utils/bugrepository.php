<?php

include_once 'jsonstorage.php';

class BugRepository extends JsonStorage {
    public function __construct() {
      parent::__construct('.bug.json');
    }
}