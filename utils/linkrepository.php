<?php

include_once 'jsonstorage.php';

class LinkRepository extends JsonStorage {
    public function __construct() {
      parent::__construct('.link.json');
    }

    public function getLinkByURL($url) {
      $results = $this->filter(function ($link) use ($url) {
          return $link["url"] === $url;
      });
      if (count($results) === 1) {
          return array_values($results)[0];
      } else {
          return false;
      }
  }
}
