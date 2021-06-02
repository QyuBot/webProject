<?php

    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";

    $id = $page = $_GET["id"];
    $conn = getDatabaseConnect();
    $sql = "SELECT * FROM images WHERE image_id = {$id};";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    header('Content-type: image/jpg');
    echo $row['image_blob'];
    // echo 'data:image/jpg;base64,'. base64_encode($row['image_blob']);
    // echo '<img src="data:image/jpg;base64,' . base64_encode( $row['image_blob'] ) . '" />';

    exit;
?>