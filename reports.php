
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<style>
button {
    font-size: 16px;
    width: 80px;
    text-align: center;
    height: 46px;
    border-radius:10px;
    border-color: #917b56;
    color: #fff;
    margin-left: 25px;
}
</style>

<!-- Report, Comment -->
<main>
    <section>
        <h3>Report<span class="btnList">
            <button style="float:right" id="new" onclick="goReportEditor();">글 작성</button>
        </span></h3>
        <hr>
        
        <?php

        require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";
        $reports = getReportListinProject($_GET['projectId']);
        if (count($reports) == 0)
            echo "작성한 리포트가 없어요.";
        else {
            foreach ($reports as $report) {
                echo "<div class='issue'>";
                echo "이슈 제목 : {$report['report_title']}";
                echo "<button onclick=location.href='/?projectId={$_GET['projectId']}&page=reportViewer&reportId={$report['report_id']}'>조회</button><br>";
                echo "</div><br>";
            }
        }
        ?>
        
    </section>
</main>

<script>

    function goReportEditor() {
        var projectId = getParameterByName('projectId');
        window.location.href='/?projectId=' + projectId + '&page=reportEditor';
    }


</script>