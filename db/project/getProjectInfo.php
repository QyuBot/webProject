<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

$resultEcho = "";

if (isset($_POST['project_id'])) {
    $resultEcho = getProject($_POST['project_id']);
}


header('Content-type: application/json');
echo json_encode($resultEcho);