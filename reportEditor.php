<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="lang/summernote-ko-KR.js"></script>

<?php

if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<main>
<h1 id="pageTitle">리포트 생성하기</h1>
<br>

<div class="col-12 col-md-12 item">
    <form action="/db/issue/saveIssue.php" method="post" id="editorForm">
        <input type="hidden" name="imgUrl" id="imgUrl" value="">
        <input type="hidden" name="reportId" id="reportId" value="-1">
        <input type="hidden" name="projectId" id="projectId" value="<?=$_GET['projectId']?>">
        <div class="form-group">
            <input type="text" class="form-control" id="title" name="title" placeholder="제목">
            <br>
        </div>
        <div class="form-group">
            <textarea id="summernote" name="contents"></textarea>
        </div>
        <div class="form-group">
            <div id="attach_site">
                <div id="attachFiles">
                </div>
                <input type="file" multiple class="form-input" name="afile" id="afile" />
            </div>
        </div>
        <button type="button" id="btn-write" class="btn contact-btn" onclick="writeButton();">WRITE</button>
    </form>
</div>
</main>
<script type="text/javascript">

    var isEditPost = false;
    var projectId = getParameterByName('projectId');

    function editMode(report) {

        isEditPost = true;

        const h1Title = document.getElementById('pageTitle');
        const inputReportId = document.getElementById('reportId');
        const inputTitle = document.getElementById('title');
        const inputContent = document.getElementById('summernote');
        const buttonWrite = document.getElementById('btn-write');

        inputReportId.value = report[['report_id']];
        inputTitle.value = report['report_title'];
        inputContent.value = report['report_article'];

        h1Title.innerText = '리포트 수정하기';
        buttonWrite.innerText = 'EDIT';


    }

    $(document).ready(function() {
        var $summernote = $('#summernote').summernote({
            codeviewFilter: false,
            codeviewIframeFilter: true,
            lang: 'ko-KR', // default: 'en-US'
            height: 400,
            callbacks: {
                onImageUpload: function (files) {
                    for(var i = 0; i < files.length; i++) {
                        if(i>20){
                            alert('파일은 20개까지만 등록할 수 있습니다.');
                            return;
                        }
                    }
                    for(var i = 0; i < files.length; i++) {
                        if(i>20){
                            alert('파일은 20개까지만 등록할 수 있습니다.');
                            return;
                        }
                        sendFile($summernote, files[i]);
                    }
                }
            }
        });
    });

    function sendFile($summernote, file) {
        var formData = new FormData();
        formData.append("file", file);

        $.ajax({
            url: '/db/saveImage.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {

                if (data === -1){
                    alert('용량이 너무크거나 이미지 파일이 아닙니다.');
                    return;
                } else {
                    $summernote.summernote('insertImage', data, function ($image) {
                        $image.attr('src', data);
                        $image.attr('class', 'childImg');
                    });
                    var imgUrl = $("#imgUrl").val();
                    if(imgUrl){
                        imgUrl = imgUrl+",";
                    }
                    $("#imgUrl").val(imgUrl+data);
                }

            }
        });

    }

    // 글쓰기 버튼 클릭 시
    function writeButton() {

        if (isEditPost)
            postEditReport();

        else
            postNewReport();
    }

    function postNewReport() {

        const formData = $("#editorForm").serialize();

        $.ajax(
            {
                type: "POST",
                url: "/db/report/saveReport.php",
                data: formData,
                success: (code) => {
                    switch(code) {
                        case "success":
                            alert('리포트가 작성되었습니다.');
                            window.location.href='/?projectId=' + projectId + '&page=report';
                            break;
                        case "access_denied":
                            alert('접근이 거부되었습니다.');
                            window.location.href='/?projectId=' + projectId + '&page=report';
                            break;
                        default:
                            alert('에러가 발생했습니다.');
                    }
                }
            }
        )
    }

    function postEditReport() {

        const formData = $("#editorForm").serialize();
        const reportId = getParameterByName('reportId');

        $.ajax(
            {
                type: "POST",
                url: "/db/report/updateReport.php",
                data: formData,
                success: (code) => {
                    switch(code) {
                        case "success":
                            alert('리포트가 수정되었습니다.');
                            window.location.href='/?projectId=' + projectId + '&page=reportViewer&reportId=' + reportId;
                            break;
                        case "access_denied":
                            alert('접근이 거부되었습니다.');
                            window.location.href='/?projectId=' + projectId + '&page=report';
                            break;
                        default:
                            alert('에러가 발생했습니다.');
                    }
                }
            }
        )
    }


</script>

<?php
// 수정 모드로 들어온 경우
if (isset($_GET['reportId'])) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/report/reportService.php";

    $report = getReportByReportId($_GET['reportId']);
    if (empty($report)) {
        echo "<script>alert('없는 리포트입니다.');</script>";
        echo "<script>window.location.href='/?projectId=' + projectId + '&page=report';</script>";
        exit;
    }
    else
        echo "<script>editMode(".json_encode($report).");</script>";
}
?>