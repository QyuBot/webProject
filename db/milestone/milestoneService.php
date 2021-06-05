<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

// 프로젝트의 모든 마일스톤 리스트 가져오기
function getMilestoneList($projectId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM milestones WHERE milestone_inclusion_project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 마일스톤 ID로 마일스톤 객체 구하기
function getMilestoneByMilestoneId($milestoneId): array
{
    $pdo = getPDO();
    $sql = "SELECT * FROM milestones WHERE milestone_id = :milestoneId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':milestoneId', $milestoneId, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        return $result[0];
    }
    return array();
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

// 마일스톤의 진행도 구하기
function getPrograssPercentage($milestoneId) {
    $issues = getIssueContainsMilestone($milestoneId);

    $total = count($issues);
    $unsolved = 0;

    // Divid by Zero 방지
    if ($total == 0)
        return -1;

    foreach($issues as $issue) {
        if ($issue['issue_status'] == 0)
            $unsolved ++;
    }

    return (($total - $unsolved) / $total) * 100;
}

// 프로젝트에 이미 존재하는 이름의 마일스톤인지 확인하는 함수
function isMilestoneNameExist($projectId, $name) {

    $milestones = getMilestoneList($projectId);

    foreach ($milestones as $milestone)
        if ($milestone['milestone_name'] == $name)
            return true;

    return false;
}

// 마일스톤 추가하기
function addMilestone($projectId, $milestoneName) {

    // 중복 이름 방지
    if (isMilestoneNameExist($projectId, $milestoneName))
        return false;

    $pdo = getPDO();
    $sql = "INSERT INTO milestones VALUES ('', :milestoneName, :projectId);";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':milestoneName', $milestoneName, PDO::PARAM_STR);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

        // 데이터 삽입
        $stmt->execute();

        // 성공 시 변경점 저장 후 true 리턴
        $pdo->commit();
        return true;

        // 작업 도중 예외 발생 시 복구 지점으로 롤백 후 false 반환
    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }
}

// 마일스톤 삭제
// 배정되었던 이슈는 아무 마일스톤에도 속하지 않게 됩니다.
function deleteMilestone($milestoneId) {

    $containsIsses = getIssueContainsMilestone($milestoneId);
    $pdo = getPDO();

    foreach ($containsIsses as $issue) {

        $sql = "UPDATE issues SET issue_inclusion_milestone_id = null WHERE issue_id = :issueId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':issueId', $issue['issue_id'], PDO::PARAM_INT);
        $stmt->execute();
    }

    $sql = "DELETE FROM milestones WHERE milestone_id = :milestoneId;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':milestoneId', $milestoneId, PDO::PARAM_INT);
    $stmt->execute();

    return true;
}