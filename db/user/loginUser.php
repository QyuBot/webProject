<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";


if (!isset($POST_['id']) || !isset($POST_[''])) {
    return "missing_arg(s)";
}

