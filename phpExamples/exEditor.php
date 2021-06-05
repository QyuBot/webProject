<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>글 작성 테스트</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="lang/summernote-ko-KR.js"></script>
    <style>
        body {
            padding: 2%;
        }
        .contents {
            border: 1px solid black;
            padding: 10px;
            margin: 10px;
        }
        th, td {
            padding: 3px;
        }
    </style>
</head>
<body>
    <a href="/exEditor.php">홈으로</a> <a href="/exEditor.php?&page=editor">글 작성하기</a><br>
    <h2>게시글 등록 및 조회</h2>
    <hr>
    <?php
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/db_function.php";

    // page GET 키가 없을 경우 -> 홈 화면
    if(!isset($_GET["page"])) {
        echo "게시글을 선택해주세요.";
        drawList();
    }
    else {
    $page = $_GET["page"];
    // page GET 키가 upload 일 경우 -> 글 작성 화면
    if ($page == "editor") {
        echo <<<EOT
        <div class="col-12 col-md-12 item">
        <form action="/board/savePost.php" method="post">
            <input type="hidden" name="imgUrl" id="imgUrl" value="">
            <!--<input type="hidden" name="attachFile" id="attachFile" value="">-->
            <div class="form-group">
                <input type="text" class="form-control" id="title" name="title" placeholder="제목">
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
            <button type="submit" class="btn contact-btn">WRITE</button>
        </form>
    </div>
EOT;
    }
    // page GET 키가 upload 가 아닐 경우 -> 게시글 조회 화면
    else {

        $pdo = getPDO();
        $sql = "SELECT * FROM reports WHERE report_id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET["page"], PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        echo "<h3>글 조회 : 인덱스[{$page}]</h3><h3>글 제목 : {$result[0]['report_title']}</h3>";
        echo "<div class='contents'>{$result[0]['report_article']}</div>";
        echo "<br><h3>작성일자</h3>{$result[0]['report_create_time']}<hr>";
        drawList();
    }


    }

    function drawList() {
        echo "<h3>업로드 이미지 목록</h3><hr><div class='list'>";
        $pdo = getPDO();
        $sql = "SELECT * FROM reports";
        $stmt = $pdo->prepare($sql);

        $stmt->execute();
        $result = $stmt->fetchAll();

        if (count($result) == 0)
            echo "업로드된 이미지가 없습니다.";
        else {
            echo "
                <table border='1'>
                    <thead>
                        <th>ID</th>
                        <th>프로젝트 ID</th>
                        <th>제목</th>
                        <th>작성자 ID</th>
                        <th>시간</th>
                        <th>게시글 링크</th>
                    </thead>
                    <tbody>";
            for($i = 0; $i < count($result); $i++) {
                $row = $result[$i];
                echo "
                    <tr>
                        <td>" . $row['report_id'] . "</td>
                        <td>" . $row['report_inclusion_project_id'] . "</td>
                        <td>" . $row['report_title'] . "</td>
                        <td>" . $row['report_creator_id'] . "</td>
                        <td>" . $row['report_create_time'] . "</td>
                        <td><a href='/exEditor.php?&page={$row[0]}'>글 보기</a></td></tr>";
            }
        }
        echo "</tbody></thead></table></div>";
    }

    ?>

</body>
</html>

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

</script>