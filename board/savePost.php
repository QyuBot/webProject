<?php
// 게시글 등록 php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";


if (!isset($_POST['contents'])) {
    echo "no contents";
    return;
}

$timestamp = date('Y-m-d H:i:s');

$conn = getDatabaseConnect();
if ($conn != null) {

    $sql = "INSERT INTO reports VALUES ('', '{$_POST['title']}', '1', '{$_POST['contents']}', '{$timestamp}', '1');";
    $run = mysqli_query($conn, $sql);
    if ($run) {
        echo "<script type='text/javascript'>게시글을 등록하였습니다.</script>";
        echo "<script type='text/javascript'>window.location.href='/exEditor.php';</script>";
        exit;
    }
    else {
        echo "<script type='text/javascript'>게시글 등록을 실패하였습니다.</script>";
        echo "<script type='text/javascript'>window.location.href='/exEditor.php';</script>";
        exit;
    }

}
?>