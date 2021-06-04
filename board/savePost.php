<?php
// 게시글 등록 php
require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";


if (!isset($_POST['title'])) {
    echo "no_title";
    return;
}

if (!isset($_POST['contents'])) {
    echo "no_contents";
    return;
}

$timestamp = date('Y-m-d H:i:s');

$pdo = getPDO();
$sql = "INSERT INTO reports VALUES ('', :title, 1, :contents, :post_timestamp, 1);";
$stmt = $pdo->prepare($sql);

try {
    $pdo->beginTransaction();

    $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
    $stmt->bindParam(':contents', $_POST['contents'], PDO::PARAM_STR);
    $stmt->bindParam(':post_timestamp', $timestamp, PDO::PARAM_STR);

    $stmt->execute();

    $pdo->commit();
    echo "<script type='text/javascript'>alert('게시글을 등록하였습니다.');</script>";

} catch (PDOException $e) {
    $pdo->rollback();
    echo "<script type='text/javascript'>alert('게시글 등록을 실패하였습니다.');</script>";
}

echo "<script type='text/javascript'>window.location.href='/exEditor.php';</script>";