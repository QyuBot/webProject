<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";


// 유저와 관련된 함수들을 모와 둔 PHP 입니다.

// LoginID 의 문자열 길이를 확인하는 함수
function isTooShort($loginId) {
    if (mb_strlen($loginId) <= 3) {
        return true;
    }
    return false;
}

// 유저 ID 로 유저 객체 구하기
function getUserByUserId($userId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_id = :userId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':userId', $userId, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll();
    if (count($result) == 1)
        return $result[0];
    else
        return array();
}

// LoginId가 이미 존재하는지 확인하는 함수
function isLoginIdExist($loginId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_login_id = :loginId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':loginId', $loginId, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    if (count($result) == 0)
        return false;
    else
        return true;
}

// 닉네임이 이미 존재하는지 확인하는 함수
function isNicknameExist($nickname) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_nickname = :nickname;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nickname', $nickname, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);
    if (count($result) == 0)
        return false;
    else
        return true;
}

// 이메일이 이미 존재하는지 확인하는 함수
function isEmailExist($email, $eDomain) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_email = :email AND user_email_domain = :domain;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':domain', $eDomain, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);

    if (count($result) == 0)
        return false;
    else
        return true;

}

// 유저 정보를 추가하는 함수
function addUser($id, $pw, $nickname, $email, $eDomain) {

    // 제약조건 필터링
    if (isTooShort($id) || isLoginIdExist($id) || isNicknameExist($nickname) || isEmailExist($email, $eDomain))
        return false;

    $pdo = getPDO();
    if($pdo == null)
        return false;

    $sql = "INSERT INTO users VALUES ('', :id, :pw, :nickname, :email, :eDomain, 0);";
    $stmt = $pdo->prepare($sql);
    try {

        // 복구 지점 설정
        $pdo->beginTransaction();

        // 파라메터 바인딩
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $stmt->bindValue(':pw', $pw, PDO::PARAM_STR);
        $stmt->bindValue(':nickname', $nickname, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':eDomain', $eDomain, PDO::PARAM_STR);

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

// userId(로그인 ID 아님) 존재 여부 확인 함수
function isUserIDExist($id) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_id=:id;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll();

    if (count($result) == 1)
        return true;
    return false;
}

// 현재 세션을 닉네임으로 변환하여 반환하는 함수
function sessionToNickname(){

    // 세션이 userId를 가지고 있는가?
    if (session_status() == PHP_SESSION_NONE)
        session_start();

    if (isset($_SESSION['sess'])){
        $index = $_SESSION['sess'];

        // 존재하는 index 인가? 아니면 세션 파기
        if (!isUserIDExist($index)){
            session_unset();
            return -1;
        }

        $pdo = getPDO();
        $sql = "SELECT * FROM users WHERE user_id=:user_index;";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_index', $index, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) == 1)
            return $result[0]['user_nickname'];
        else {
            session_unset();
            return -1;
        }
    }
    else
        return -1;
}

// ID, PW가 일치하는 유저 레코드의 ID를 반환하는 함수
function doLogin($loginId, $password) {

    $pdo = getPDO();
    $sql = "SELECT * FROM users WHERE user_login_id=:login_id AND user_password=:password";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':login_id', $loginId, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) == 1)
        return $result[0]['user_id'];
    else
        return false;
}

// 사용자가 프로젝트에 작성한 이슈 전부 구하기
function getAllWritedIssuesInProject($projectId, $userId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM issues WHERE issue_creator_id = :userId AND issue_inclusion_project_id = :projectId";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll();
}

// 사용자가 프로젝트에 작성한 댓글 전부 구하기
function getAllWritedCommentsInProject($projectId, $userId): array
{

    $pdo = getPDO();
    $sql = "SELECT * FROM comments 
            INNER JOIN issues
                ON issues.issue_inclusion_project_id = :projectId
            WHERE comment_creator_id = :userId AND comment_inclusion_issue_id = issues.issue_id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':projectId', $projectId, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll();
}