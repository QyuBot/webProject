<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";



// LoginID 의 문자열 길이를 확인하는 함수
function isTooShort($loginId) {
    if (mb_strlen($loginId) <= 3) {
        return true;
    }
    return false;
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
        $stmt->bindValue(':pw', $id, PDO::PARAM_STR);
        $stmt->bindValue(':nickname', $id, PDO::PARAM_STR);
        $stmt->bindValue(':email', $id, PDO::PARAM_STR);
        $stmt->bindValue(':eDomain', $id, PDO::PARAM_STR);

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