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
        include_once "db/db_function.php";

        // bool 참/거짓 에 따라 문자열 변환
        function isExist($bool){
            if($bool) return "생성됨.";
            else return "존재하지 않음.";
        }
    ?>
</head>

<body scroll=no onload="init();">
    <?php echo("<h1>데이터베이스 테스트 페이지 입니다.</h1>");?>
    <hr>
    <h3>데이터베이스 상태</h3>
    <?php

        if(checkDB()){
            echo "유저 테이블 : ".isExist(checkTable("users"))."<br>";
        }
        else{
            echo "DB가 생성되지 않았습니다!";
        }


    ?>



</body>
</html>

<script type="text/javascript">


</script>