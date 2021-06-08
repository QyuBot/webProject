<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

// 프로젝트의 모든 리포트 리스트 가져오기
function getReportListinProject($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM reports WHERE report_inclusion_project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
}

// 리포트 ID로 이슈 객체 구하기
function getReportByReportId($reportId): array
{
    $pdo = getPDO();
    $sql = "SELECT * FROM reports WHERE report_id = :reportId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':reportId', $reportId, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        return $result[0];
    }
    return array();
}

// 프로젝트에 이미 존재하는 리포트 제목인지 확인하는 함수
function isReportTitleExist($projectId, $title) {

    $reports = getReportListinProject($projectId);

    foreach ($reports as $report)
        if ($report['report_title'] == $title)
            return true;

    return false;
}

// 리포트가 포함된 프로젝트에 이미 존재하는 리포트 제목인지 확인하는 함수
function isReportTitleExistinContainsProject($reportId, $title) {

    $report = getReportListinProject($reportId);
    $projectId = $report['issue_inclusion_project_id'];

    return isReportTitleExist($projectId, $title);
}

// 리포트 추가하기
function addReport($creatorId, $title, $contents, $projectId) {

    // 중복 이름 방지
    if (isReportTitleExist($projectId, $title))
        return false;

    $pdo = getPDO();
    $sql = "INSERT INTO reports VALUES ('', :title, :creatorId, :content, :createTime, :projectId);";
    $stmt = $pdo->prepare($sql);

    $timestamp = date('Y-m-d H:i:s');

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':creatorId', $creatorId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $contents, PDO::PARAM_STR);
        $stmt->bindValue(':createTime', $timestamp, PDO::PARAM_STR);
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

// 리포트 수정하기
// 수정자에 대한 검증 로직은 없습니다(해당 함수를 호출 가능하면 누구나 수정 가능)
function editReport($reportId, $title, $contents) {

    $report = getReportByReportId($reportId);
    if (empty($report))
        return false;

    // 중복 이름 방지
    if ($report['report_title'] != $title && isReportTitleExistinContainsProject($reportId, $title))
        return "dupname";

    $pdo = getPDO();
    $sql = "UPDATE reports SET report_title = :title, report_article = :content;";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $contents, PDO::PARAM_STR);

        // 데이터 삽입
        $stmt->execute();

        // 성공 시 변경점 저장 후 true 리턴
        $pdo->commit();
        return "success";

        // 작업 도중 예외 발생 시 복구 지점으로 롤백 후 false 반환
    } catch (PDOException $e) {
        $pdo->rollback();
        return $e;
    }
}

// 이슈 삭제하기
function deleteReport($reportId) {


    $pdo = getPDO();
    $sqlIssue = "DELETE FROM reports WHERE report_id = :reportId;";
    $stmtIssue = $pdo->prepare($sqlIssue);

    $pdo = getPDO();

    try {
        $pdo->beginTransaction();

        // 이슈 삭제
        $stmtIssue->bindValue(':reportId', $reportId, PDO::PARAM_INT);
        $stmtIssue->execute();

        // 커밋
        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }
    return true;
}
