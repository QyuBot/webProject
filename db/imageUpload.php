<?php
    // 사진 업로드 php
    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."/settings/settings.php";

    if (!isset($_FILES['form-image'])) {
        echo "no file uploaded";
        return;
    }


    $file = $_FILES['form-image'];
    $fname = $file['name'];
    $fsize = $file['size'];
    $ftype = $file['type'];
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

    // 파일을 저장한다.
    $saveFileName = saveImageFile($file);

    // 파일 저장 결과가 실패라면 종료한다.
    if ($saveFileName == false) {
        return false;
    }

    $conn = getDatabaseConnect();
    if ($conn != null) {

        $sql = "INSERT INTO images VALUES ('', '{$fname}', '{$saveFileName}', '{$ftype}', '{$fsize}', '{$timestamp}', '1');";
        $run = mysqli_query($conn, $sql);
        if ($run) {
            echo "<script type='text/javascript'>window.location.href='/exImageDB.php';</script>";
            return $saveFileName;
        }
        else {
            echo "<br>MYSQL Error<br>";
            echo mysqli_error($conn);
            return false;
        }

    }

    // 매개변수의 이미지 파일 객체를 랜덤 파일명으로 저장하는 함수
    function saveImageFile($imageFile) {

        global $DIR_STORAGE, $DIR_IMAGES;

        if(gettype($imageFile['tmp_name']) == "resource"){
            $tmpfile = tmpfile();
            fwrite($tmpfile, $imageFile['tmp_name']);
        }
        else {
            $tmpfile = $imageFile['tmp_name'];
        }

        if (is_file($tmpfile)){

            $imginfo = getimagesize($tmpfile);
            $extension = image_type_to_extension($imginfo[2]);

            // 파일명이 겹치지 않을때까지 랜덤 파일명 생성
            while (true) {
                $saveFileName = GenerateString(16).$extension;
                if (!is_file($saveFileName))
                    break;
            }

            $storageDir = $_SERVER["DOCUMENT_ROOT"].$DIR_STORAGE;
            $saveDir = $_SERVER["DOCUMENT_ROOT"].$DIR_IMAGES;

            // 저장 폴더가 없을 경우 생성
            echo $saveDir;
            if(!is_dir($storageDir))
                mkdir($storageDir);
            if (!is_dir($saveDir))
                mkdir($saveDir);

            $f = fopen($saveDir."/".$saveFileName, "w");
            fwrite($f, "");

            // Copy Exception
            if (!copy($tmpfile, $saveDir."/".$saveFileName)){
                unlink($tmpfile);
                return false;
            }
        }

        unlink($tmpfile);
        return $saveFileName;
    }

    function GenerateString($length)
    {
        $characters  = "0123456789";
        $characters .= "abcdefghijklmnopqrstuvwxyz";
        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $string_generated = "";

        $nmr_loops = $length;
        while ($nmr_loops--)
        {
            $string_generated .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string_generated;
    }
?>