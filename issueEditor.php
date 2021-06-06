<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="lang/summernote-ko-KR.js"></script>

<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";

if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}

// 수정 모드로 들어온 경우
if (isset($_POST['editPostId'])) {


}

?>

<h1>이슈 생성하기</h1>
<br>

<div class="col-12 col-md-12 item">
    <form action="/db/issue/saveIssue.php" method="post" id="editorForm">
        <input type="hidden" name="imgUrl" id="imgUrl" value="">
        <input type="hidden" name="projectId" id="projectId" value="<?=$_GET['projectId']?>">
        <input type="hidden" name="priority" id="priority" value="1">
        <input type="hidden" name="status" id="status" value="0">
        <div class="form-group">
            <input type="text" class="form-control" id="title" name="title" placeholder="제목">
            <br>
            우선순위&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" onclick="btn_priority_toggle(this);">1 순위</button>
            <br>
            <br>
            상태&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-light" value="" onclick="btn_status_toggle(this);">해결안됨</button>
            <br>
            <br>
            마일스톤&nbsp;&nbsp;&nbsp;
            <select class="form-control" id="milestoneId" name="milestoneId">
                <option value="-1">마일스톤 없음</option>
                <?php
                $milestones = getMilestoneListinProject($_GET['projectId']);
                foreach ($milestones as $milestone)
                    echo "<option value='{$milestone['milestone_id']}'>{$milestone['milestone_name']}</option>>";
                ?>
            </select>
            <br>
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
        <button type="button" class="btn contact-btn" onclick="writeIssue();">WRITE</button>
    </form>
</div>

<script type="text/javascript">

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
            url: '/board/saveImage.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                console.log("asdf");
                console.log("data : " + data);
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

    function btn_priority_toggle(btn) {
        const input = document.getElementById("priority");
        switch (input.value) {
            default:
            case "1":
                btn.className = "btn btn-warning";
                btn.innerText = "2 순위"
                input.value = 2;
                break;
            case "2":
                btn.className = "btn btn-light";
                btn.innerText = "3 순위"
                input.value = 3;
                break;
            case "3":
                btn.className = "btn btn-secondary";
                btn.innerText = "4 순위"
                input.value = 4;
                break;
            case "4":
                btn.className = "btn btn-danger";
                btn.innerText = "1 순위"
                input.value = 1;
                break;
        }
    }

    function btn_status_toggle(btn) {
        const input = document.getElementById("status");
        switch (input.value) {
            case "0":
                btn.className = "btn btn-success";
                btn.innerText = "해결됨"
                input.value = 1;
                break;
            default:
            case "1":
                btn.className = "btn btn-light";
                btn.innerText = "해결안됨"
                input.value = 0;
        }
    }

    // 글쓰기 버튼 클릭 시
    function writeIssue() {
        const formData = $("#editorForm").serialize();
        $.ajax(
            {
                type: "POST",
                url:"/db/issue/saveIssue.php",
                data:formData,
                success: (code) => {
                    switch(code) {
                        case "success":
                            alert('이슈가 작성되었습니다.');
                            const projectId = getParameterByName('projectId');
                            window.location.href='/?projectId=' + projectId + '&page=issue';
                            break;
                        default:
                            console.log(code);
                            alert('에러.');
                    }
                }
            }
        )
    }


    // echo "<script type='text/javascript'>window.location.href='/phpExample/exEditor.php';

</script>