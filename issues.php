<style>
    .milestone {
        border: 1px solid gray;
        margin: 3px;
        padding: 3px;
        width: 600px;
        height: 140px;
    }
</style>
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

?>
<h1>이슈 목록</h1>
<br>
<a href="/?projectId=<?=$_GET['projectId']?>&page=issueEditor">이슈 새로 작성하러가기</a>
<br>
<br>