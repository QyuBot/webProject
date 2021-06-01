<?php

    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";

    if (!isset($_FILES['form-image'])) {
        echo "no file uploaded";
        return;
    }


    $file = $_FILES['form-image'];
    $fname = $file['name'];
    $fsize = $file['size'];
    $timestamp = date('Y-m-d H:i:s');

    if (get_magic_quotes_gpc()) {
        $slashes = fread(fopen($file['tmp_name'], "rb"), $fsize);
    }
    else {
        $slashes = addslashes(fread(fopen($file['tmp_name'], "rb"), $fsize));
    }

    // $slashes = fopen($file['tmp_name'], 'rb');
    $tempuser = 1;

    // 파일 크기(20MB 제한)
    if ($file['size']>20480000) {
        echo "-1";
        exit;
    }

    $ext = substr(strrchr($file['name'],"."),1);
    $ext = strtolower($ext);

    if ($ext != "jpg" and $ext != "png" and $ext != "jpeg" and $ext != "gif")
    {
        echo "-1";
        exit;
    }

    $conn = getDatabaseConnect();
    if ($conn != null) {

        $sql = "INSERT INTO images VALUES ('', '{$fname}', '{$timestamp}', '$tempuser', '{$slashes}');";
        echo $sql."<br>";
        $run = mysqli_query($conn, $sql);
        if ($run) {
            echo "0";
        }
        else {
            echo "<br>MYSQL Error<br>";
            echo mysqli_error($conn);
            echo "-1";
        }

    }
?>
