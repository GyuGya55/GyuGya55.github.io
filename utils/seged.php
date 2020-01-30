<?php
function is_empty($input, $key) {
    return !(isset($input[$key]) && trim($input[$key]) !== '');
}
