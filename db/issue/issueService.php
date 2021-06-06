<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

// 프로젝트의 모든 이슈 리스트 가져오기
function getIssueListinProject($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_inclusion_project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 프로젝트의 해결되지 않은 모든 이슈 리스트 가져오기
function getIssueListinProjectAndNotSolved($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_inclusion_project_id = :projectId AND issue_status = 0;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 프로젝트의 해결된 모든 이슈 리스트 가져오기
function getIssueListinProjectAndSolved($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_inclusion_project_id = :projectId AND issue_status = 1;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 이슈 ID로 이슈 객체 구하기
function getIssueByIssueId($milestoneId): array
{
    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':issueId', $milestoneId, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        return $result[0];
    }
    return array();
}

// 프로젝트에 이미 존재하는 이슈 제목인지 확인하는 함수
function isIssueNameExist($projectId, $title) {

    $issues = getIssueListinProject($projectId);

    foreach ($issues as $issue)
        if ($issue['issue_title'] == $title)
            return true;

    return false;
}

// 이슈가 포함된 프로젝트에 이미 존재하는 이슈 제목인지 확인하는 함수
function isIssueNameExistinContainsProject($issueId, $title) {

    $issue = getIssueByIssueId($issueId);
    $projectId = $issueId['issue_inclusion_project_id'];

    return isIssueNameExist($projectId, $title);
}

// 이슈 추가하기
function addIssue($creatorId, $title, $contents, $priority, $status, $projectId, $milestoneId) {

    // 중복 이름 방지
    if (isIssueNameExist($projectId, $title))
        return false;

    $pdo = getPDO();
    $sql = "INSERT INTO issues VALUES ('', :title, :creatorId, :priority, :content, :createTime, :status, :milestoneId, :projectId);";
    $stmt = $pdo->prepare($sql);

    $timestamp = date('Y-m-d H:i:s');

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':creatorId', $creatorId, PDO::PARAM_INT);
        $stmt->bindValue(':priority', $priority, PDO::PARAM_INT);
        $stmt->bindValue(':content', $contents, PDO::PARAM_STR);
        $stmt->bindValue(':createTime', $timestamp, PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        if ($milestoneId == -1)
            $stmt->bindValue(':milestoneId', null);
        else
            $stmt->bindValue(':milestoneId', $milestoneId, PDO::PARAM_INT);
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

// 이슈 수정하기
// 수정자에 대한 검증 로직은 없습니다(해당 함수를 호출 가능하면 누구나 수정 가능)
function editIssue($issueId, $title, $contents, $priority, $status, $milestoneId) {

    // 중복 이름 방지
    if (isIssueNameExistinContainsProject($issueId, $title))
        return false;

    $pdo = getPDO();
    $sql = "UPDATE issues SET issue_title = :title, 
                  issue_priority = :priority, 
                  issue_article = :content,
                  issue_status = :status, 
                  issue_inclusion_milestone_id = :milestoneId WHERE issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':priority', $priority, PDO::PARAM_INT);
        $stmt->bindValue(':content', $contents, PDO::PARAM_STR);
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        $stmt->bindValue(':milestoneId', $milestoneId, PDO::PARAM_INT);
        $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);

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

// 이슈 삭제하기
// 이슈에 달린 댓글들도 전부 삭제됩니다.
function deleteIssue($issueId) {

    if (deleteCommtentsinIssue($issueId))
        return false;

    $pdo = getPDO();

    $sql = "DELETE FROM issues WHERE issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();
        $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);
        $stmt->execute();
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }
    return true;


}

// 이슈에 달린 모든 댓글 구하기
function getCommtentsinIssue($issueId) {
    $pdo = getPDO();
    $sql = "SELECT * FROM comments WHERE comment_inclusion_issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 이슈에 달린 모든 댓글 삭제하기
function deleteCommtentsinIssue($issueId) {
    $pdo = getPDO();
    $sql = "DELETE FROM comments WHERE comment_inclusion_issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();
        $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);
        $stmt->execute();
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }
    return true;
}