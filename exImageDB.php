<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>사진 업로드 및 목록</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>
        #preview-image {
            border: 1px solid gray;
            width: auto;
            height: 50%;
            min-width: 50px;
            min-height: 50px;
        }
    </style>
</head>
<body>
<a href="/exImageDB.php">홈으로</a> <a href="/exImageDB.php?&page=upload">업로드</a><br>
<?php
    require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."/settings/settings.php";

    // page GET 키가 없을 경우 -> 홈 화면
    if(!isset($_GET["page"])) {
        echo "게시글을 선택해주세요.";
        drawList();
    }
    else {
        $page = $_GET["page"];
        // page GET 키가 upload 일 경우 -> 업로드 화면
        if ($page == "upload") {
            echo "<div id='image-container'><img id='preview-image'></div>";
            echo "
                <br>
                <form method='POST' action='/db/imageUpload.php' enctype='multipart/form-data'>
                    <input type='file' id='form-image' name='form-image' accept='image/*'>
                    <br>
                    <br>
                    <button type='submit'>업로드</button>
                </form>";
        }
        // page GET 키가 upload 가 아닐 경우 -> 사진 조회 화면
        else {
            require_once $_SERVER["DOCUMENT_ROOT"]."/db/getImageSrc.php";
            global $DIR_IMAGES;
            $fName = getImageSrc($page);
            echo "<h3>사진 조회 : 인덱스[{$page}]</h3>";
            echo "<div id='image-container'><img id='preview-image' src='{$DIR_IMAGES}/{$fName}'></div>";
            echo "<br>";

            drawList();
        }
    }

    // 이미지 목록 표 그리는 함수
    function drawList() {

        echo "<h3>업로드 이미지 목록</h3><hr><div class='list'>";
        $conn = getDatabaseConnect();
        if ($conn != null) {
            $sql = "SELECT * FROM images";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 0)
                echo "업로드된 이미지가 없습니다.";
            else {
                echo "
                    <table border='1'>
                        <thead>
                            <th>ID</th>
                            <th>원본 파일명</th>
                            <th>업로드 시간</th>
                            <th>업로드 유저 ID</th>
                            <th>사진 링크</th>
                        </thead>
                        <tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "
                        <tr>
                            <td>" . $row['image_id'] . "</td>
                            <td>" . $row['image_original_filename'] . "</td>
                            <td>" . $row['upload_time'] . "</td>
                            <td>" . $row['upload_user_id'] . "</td>
                            <td><a href='/exImageDB.php?&page={$row["image_id"]}'>사진보기</a></td></tr>";
                }
            }

            echo "</tbody></thead></table></div>";
        }
    }
?>



</body>
</html>

<script type="text/javascript">

    // 이미지 업로드 시 미리보기
    function readImage(input) {
        // 인풋 태그에 파일이 있는 경우
        if(input.files && input.files[0]) {
            // 이미지 파일인지 검사 (생략)
            // FileReader 인스턴스 생성
            const reader = new FileReader();
            // 이미지가 로드가 된 경우
            reader.onload = e => {
                const previewImage = document.getElementById("preview-image");
                previewImage.src = e.target.result
            }
            // reader가 이미지 읽도록 하기
            reader.readAsDataURL(input.files[0])
        }
    }
    // input file에 change 이벤트 부여
    const inputImage = document.getElementById("form-image");
    if (inputImage != null) {
        inputImage.addEventListener("change", e => {
            readImage(e.target);
        })
    }

</script>