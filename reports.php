
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<!-- Report, Comment -->
<main>
    <section>
        <h3>Report</h3>
        <hr>
        <?php

        require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";
        $reports = getReportListinProject($_GET['projectId']);
        if (count($reports) == 0)
            echo "작성한 리포트가 없어요.";
        else {
            foreach ($reports as $report) {
                echo "<div class='issue'>";
                echo "이슈 제목 : {$report['report_title']}<br>";
                echo "<a href='/?projectId={$_GET['projectId']}&page=reportViewer&reportId={$report['report_id']}'>리포트 조회하기</a><br>";
                echo "</div>";
            }
        }
        ?>
        <div class="btnList">
            <button id="new" onclick="goReportEditor();">글 작성</button>
        </div>
    </section>
</main>

<script>

    function goReportEditor() {
        var projectId = getParameterByName('projectId');
        window.location.href='/?projectId=' + projectId + '&page=reportEditor';
    }


</script>