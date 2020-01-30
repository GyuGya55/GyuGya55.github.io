<?php

include_once 'jsonstorage.php';

class UserRepository extends JsonStorage {
    public function __construct() {
      parent::__construct('.user.json');
    }

    public function getUserByName($name) {
        $results = $this->filter(function ($user) use ($name) {
            return $user["name"] === $name;
        });
        if (count($results) === 1) {
            return array_values($results)[0];
        } else {
            return false;
        }
    }
}
