<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

// 프로젝트의 모든 마일스톤 리스트 가져오기
function getMilestoneList($projectId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM milestones;";
    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll();

}

// 마일스톤에 배정된 이슈 리스트 가져오기
function getIssueContainsMilestone($milestoneId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_inclusion_milestone_id = :milestoneId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':milestoneId', $milestoneId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}