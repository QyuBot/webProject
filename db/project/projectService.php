<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

// 프로젝트 관련 함수를 작성한 PHP 입니다.


// 프로젝트 생성하기
function addProject($adminId, $projectName): bool
{

    if (isProjectNameExist($projectName))
        return false;

    $pdo = getPDO();
    $sql = "INSERT INTO projects VALUES ('', :userId, :projectName);";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':userId', $adminId, PDO::PARAM_INT);
        $stmt->bindValue(':projectName', $projectName, PDO::PARAM_STR);

        // 데이터 삽입
        $stmt->execute();
        $pdo->commit();


        // 프로젝트 생성자를 참가자로 추가
        $projectId = isProjectNameExist($projectName);
        addUsertoProject($projectId, $adminId);
        return true;

        // 작업 도중 예외 발생 시 복구 지점으로 롤백 후 false 반환
    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }
}

// 존재하는 프로젝트 ID인지 확인하는 함수
function isProjectIdExist($projectId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM projects WHERE project_id = :id;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $projectId, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);

    if (count($result) == 0)
        return false;
    else
        return true;
}

// 존재하는 프로젝트 이름인지 확인하는 함수
// 존재하면 해당 프로젝트 ID 반환(비어있지 않은 값은 true로 취급)
// 즉 프로젝트 이름으로 ID를 구하는 용도로도 사용 가능
function isProjectNameExist($projectName) {

    $pdo = getPDO();
    $sql = "SELECT * FROM projects WHERE project_name = :name;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $projectName, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);

    if (count($result) == 0)
        return false;
    else
        return $result[0][0];
}

// 프로젝트 ID로 이름 구하기
function getProjectNameByProjectId($projectId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM projects WHERE project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) == 1)
        return $result[0]['project_name'];
    else
        return false;
}

// 프로젝트 정보 구하기
function getProject($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM projects WHERE project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll();
    if (count($result) == 1) {
        $row = $result[0];
        $data = ['name' => $row['project_name'], 'admin_id' => $row['project_admin_id']];
        return $data;
    }
    else
        return array();

}

// 프로젝트 정보 구하기
function getProjectByProjectId($projectId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM projects WHERE project_id = :projectId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll();
    if (count($result) == 1)
        return $result[0];
    else
        return array();

}

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

// 프로젝트 참가 인원 구하기
function getNumberofProjectCollaborators($projectId) {

    $pdo = getPDO();
    $sqlProjectList = "SELECT project_id FROM user_project_join WHERE project_id = :projectId;";
    $stmt = $pdo->prepare($sqlProjectList);

    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll();
}

// 전체 프로젝트 리스트 구하기
function getAllProjects(): array
{

    $pdo = getPDO();
    $sqlProjectList = "SELECT * FROM projects;";
    $stmt = $pdo->prepare($sqlProjectList);

    $stmt->execute();
    return $stmt->fetchAll();
}

// 참가하고 안한 프로젝트 리스트 구하기
function getJoinorNotProjectList($userId) {

    $pdo = getPDO();
    $sqlProjectList = "SELECT project_id FROM user_project_join WHERE user_id = :userId;";
    $stmt = $pdo->prepare($sqlProjectList);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    $stmt->execute();

    // 프로젝트 목록과 참가한 프로젝트 목록을 구한다
    $resultProjectJoinList = $stmt->fetchAll();
    $resultProjectList = getAllProjects();

    $join = array();
    $notjoin = array();

    // 참가한 프로젝트 ID를 배열로 정리한다
    foreach ($resultProjectJoinList as $row) {
        array_push($join, $row['project_id']);
    }

    // 모든 프로젝트 리스트를 순회 검사한다
    foreach ($resultProjectList as $row) {
        $projectId = $row['project_id'];
        $flag = true;

        // 참가한 프로젝트 목록에 이미 있는 ID라면 -> 참가안한 프로젝트가 아니기 때문에 건너뛴다
        foreach ($join as $id) {
            if ($id == $projectId) {
                $flag = false;
                break;
            }
        }
        // 비교가 완료되었는데도 참가한 프로젝트 목록에 없는 프로젝트 ID 라면 -> 참가하지 않은 프로젝트 배열에 추가
        if ($flag)
            array_push($notjoin, $projectId);
    }

    // 참가한 프로젝트 목록 배열, 참가안한 프로젝트 목록 배열을 넣고 반환
    $data = ['join' => $join, 'notJoin' => $notjoin];
    return $data;

}

// 유저가 프로젝트에 참가중인지 확인하기
function isUserJoinedProject($projectId, $userId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM user_project_join WHERE project_id = :projectId AND user_id = :userId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':projectId', $projectId, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) == 1)
        return true;
    else
        return false;


}

// 유저를 프로젝트에 참가시키기
function addUsertoProject($projectId, $userId) {

    // 존재하지 않는 프로젝트 ID 이면 false
    if(!isProjectIdExist($projectId))
        return false;

    // 이미 참가한 유저라면 false
    if(isUserJoinedProject($projectId, $userId))
        return false;

    $pdo = getPDO();
    $sql = "INSERT INTO user_project_join VALUES (:userId, :projectId);";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
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

function quitUserFromProject($projectId, $userId) {

    // 존재하지 않는 프로젝트 ID 이면 false
    if (!isProjectIdExist($projectId))
        return false;

    // 프로젝트에 참가하지 않은 유저라면 false
    if (!isUserJoinedProject($projectId, $userId))
        return false;

    // 프로젝트 관리자라면
    $project = getProjectByProjectId($projectId);
    if ($project['project_admin_id'] == $userId)
        return false;

    $pdo = getPDO();
    $sql = "DELETE FROM user_project_join WHERE project_id = :projectId AND user_id = :userId;";
    $stmt = $pdo->prepare($sql);

    try {
        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
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

// 프로젝트 삭제
function deleteProject($projectId) {

    $pdo = getPDO();
    $phase = 0;

    try {

        $pdo->beginTransaction();

        // 댓글 전부 삭제
        $phase = -1;
        $issues = getIssueListinProject($projectId);

        foreach ($issues as $issue) {
            $sql = "DELETE FROM comments WHERE comment_inclusion_issue_id = :issueId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':issueId', $issue['issue_id'], PDO::PARAM_INT);
            $stmt->execute();
        }


        // 이슈 전부 삭제
        $phase = -2;

        $sql = "DELETE FROM issues WHERE issue_inclusion_project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        // 마일스톤 전부 삭제
        $phase = -3;

        $sql = "DELETE FROM milestones WHERE milestone_inclusion_project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        // 보고서 전부 삭제
        $phase = -4;

        $sql = "DELETE FROM reports WHERE report_inclusion_project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        // 프로젝트에 참가한 유저 전부 해제
        $phase = -5;

        $sql = "DELETE FROM reports WHERE report_inclusion_project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        // 프로젝트에 참가한 유저 전부 해제
        $phase = -6;

        $sql = "DELETE FROM user_project_join WHERE project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        // 프로젝트 삭제
        $phase = -7;

        $sql = "DELETE FROM projects WHERE project_id = :projectId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);
        $stmt->execute();

        $phase = 1;
        $pdo->commit();

        return $phase;
        // 작업 도중 예외 발생 시 복구 지점으로 롤백 후 false 반환
    } catch (PDOException $e) {
        $pdo->rollback();
        return $phase;
    }



}