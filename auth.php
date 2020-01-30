<?php

include_once './utils/userrepository.php';

class Auth {
    private $userrepository;
    public function __construct() {
        $this->userrepository = new UserRepository();
    }

    public function reg($user) {
        $name = $user["name"];
        $userExists = $this->userrepository->getUserByName($name);
        if ($userExists) {
            return false;
        }
        $user["password"] = password_hash($user["password"], PASSWORD_DEFAULT);
        $this->userrepository->insert($user);
        return true;
    }

    public function login($name, $password) {
        $user = $this->userrepository->getUserByName($name);
        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user"] = $user;
        }
        return $this->isLoggedIn();
    }

    public function logout() {
        unset($_SESSION["user"]);
    }

    public function isLoggedIn() {
        return isset($_SESSION["user"]);
    }

    public function reset($name, $old, $new) {
        $user = $this->userrepository->getUserByName($name);
        if ($user && password_verify($old, $user["password"])) {
            $this->userrepository->update(
                function (&$item) use ($name) {
                    return $item['name'] === $name;
                },
                function (&$item) use ($new) {
                    $item['password'] = password_hash($new, PASSWORD_DEFAULT);
                }
            );
            return true;
        }
        return false;
    }

    public function setColor($name, $color) {
        $user = $this->userrepository->getUserByName($name);
        if ($user && $color) {
            $this->userrepository->update(
                function (&$item) use ($name) {
                    return $item['name'] === $name;
                },
                function (&$item) use ($color) {
                    $item['color'] = $color;
                }
            );
        }
    }

    public function setDescr($name, $descr) {
        $user = $this->userrepository->getUserByName($name);
        if ($user && $descr) {
            $this->userrepository->update(
                function (&$item) use ($name) {
                    return $item['name'] === $name;
                },
                function (&$item) use ($descr) {
                    $item['description'] = $descr;
                }
            );
        }
    }
}