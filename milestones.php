<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 이정표</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>

    </style>
</head>
<body>
<header>
    <?php
    if(!defined('DirectAccessCheck')){
        header('HTTP/1.0 404 Not Found', true, 404);
        exit;
    }
    ?>
</header>
    <h1>마일스톤 목록</h1>
    <br>
    <?php

    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";


    ?>
</body>
</html>

<script type="text/javascript">


</script>