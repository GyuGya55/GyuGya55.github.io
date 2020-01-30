<?php

include_once 'auth.php';
include_once './utils/userrepository.php';

$array['name'] = 'GyuGya_55';
$array['password'] = 'GyuGya_55';
$array['color'] = '#00580F';
$array['title'] = 'Co-Founder | Lead Graphic Designer | Software Engineer';
$array['profile_picture'] = '';
$array['description'] = '';


$auth = new Auth();
$auth->reg($array);
