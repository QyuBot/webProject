<?php

    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";

    function getImageSrc($id) {

        $conn = getDatabaseConnect();
        $sql = "SELECT * FROM images WHERE image_id = {$id};";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row['image_save_filename'];
    }

?>