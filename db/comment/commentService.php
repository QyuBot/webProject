<?php

// 댓글 추가하기
function addComment($issueId, $creatorId, $content) {

    $pdo = getPDO();
    $sql = "INSERT INTO comments VALUES ('', :creatorId, :contents, :createTime, :issueId)";
    $stmt = $pdo->prepare($sql);

    try {
        $pdo->beginTransaction();

        $stmt->bindValue(':creatorId', $creatorId, PDO::PARAM_INT);
        $stmt->bindValue(':contents', $content, PDO::PARAM_STR);
        $stmt->bindValue(':createTime', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);

        $stmt->execute();

        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        $pdo->rollback();
        return false;
    }

}

// 이슈의 모든 댓글 가져오기
function getCommentcontainsIssue($issueId) {

    $pdo = getPDO();
    $sql = "SELECT * FROM comments WHERE comment_inclusion_issue_id = :issueId;";
    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':issueId', $issueId, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll();
}