<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DB 테스트 페이지</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>

    </style>

    <?php
        /* DB 관련 함수가 정의된 php 파일 포함 */
        require_once $_SERVER["DOCUMENT_ROOT"]."/db/db_function.php";

        // bool 참/거짓 에 따라 문자열 변환
        function isExist($bool){
            if($bool) return "생성됨.";
            else return "존재하지 않음.";
        }
    ?>

</head>

<body>
    <?php echo"<h1>데이터베이스 테스트 페이지 입니다.</h1>";?>
    <hr>
    <h3>데이터베이스 상태</h3>
    <?php
        if(checkDB()){
            echo "DB가 생성되어 있습니다.<br>";
            echo "유저 테이블 : ".isExist(isTableExist("users"))."<br>";
            echo "프로젝트 테이블 : ".isExist(isTableExist("projects"))."<br>";
            echo "마일스톤 테이블 : ".isExist(isTableExist("milestones"))."<br>";
            echo "이슈 테이블 : ".isExist(isTableExist("issues"))."<br>";
            echo "이미지 테이블 : ".isExist(isTableExist("images"))."<br>";
            echo "보고서 테이블 : ".isExist(isTableExist("reports"))."<br>";
            echo "댓글 테이블 : ".isExist(isTableExist("comments"))."<br>";
            echo "유저 프로젝트 참여 관계 테이블 : ".isExist(isTableExist("user_project_join"))."<br>";
            echo "이미지 이슈 포함 관계 테이블 : ".isExist(isTableExist("image_issue_include"))."<br>";
            echo "이미지 이슈 포함 관계 테이블 : ".isExist(isTableExist("image_report_include"))."<br>";
        }
        else{
            echo "DB가 생성되지 않았습니다!";
        }


    ?>

    <br>
    <form method="POST">
        <input type="submit" name="Test_PreGenerateDB" id="Test_PreGenerateDB" value="데이터베이스, 테이블 생성하기">
        <input type="submit" name="Test_DROPTABLE" id="Test_DROPTABLE" value="데이터베이스 초기화">
    </form>
    <?php
    if(array_key_exists('Test_PreGenerateDB', $_POST)){
        $result = InitDatabase();
        echo "<br>";
        if($result == "0"){
            echo "데이터베이스가 생성되었습니다.";
        }
        if($result == "-1"){
            echo "통신 오류가 발생했습니다. DB 설정과 연결을 확인해주세요.";
        }
    }

    if(array_key_exists('Test_DROPTABLE', $_POST)){
        $result = DropDatabase();
        echo "<br>";
        if($result == "0"){
            echo "데이터베이스의 전체 테이블 레코드를 지웠습니다.<br>";
        }
        if($result == "-1"){
            echo "통신 오류가 발생했습니다. DB 설정과 연결을 확인해주세요.<br>";
        }
    }
    ?>


</body>
</html>

<script type="text/javascript">
    // 새로고침 시 POST 재전송 막기
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

</script>