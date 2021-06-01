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
    // $slashes = addslashes(fread(fopen($file['tmp_name'], "r"), $fsize));
    $slashes = fopen($file['tmp_name'], 'rb');

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
//         $sql = "INSERT INTO images (original_filename, upload_time, upload_user_id, image_blob) VALUES ('{$fname}', '{$timestamp}', 1, {$slashes});";
//         echo $sql."<br>";

        $stmt = $mysqli->stmt_init();
        $sql = "INSERT INTO images (original_filename, upload_time, upload_user_id, image_blob)
            VALUES (?, ?, ?, ?));";

        $stmt->bindParam(1, $fname);
        $stmt->bindParam(2, $timestamp);
        $stmt->bindParam(3, 1);
        $stmt->bindParam(4, $slashes, POD::PARAM_LOB);

        $stmt->execute();

//        $run = mysqli_query($conn, $sql);
//        if ($run) {
//            echo "0";
//            return 0;
//        }
//        else {
//            echo "<br>MYSQL Error Occur<br>";
//            echo mysqli_error($conn);
//            return "-1";
//        }

    }

    echo "-1";
    return -1;
?>
