<?php

    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";

    function getImageSrc($id) {

        $pdo = getPDO();
        $sql = "SELECT * FROM images WHERE image_id = :id;";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_NUM);

        if (count($result) == 1) {
            return $result[0][2];
        }

        return "";
    }

?>