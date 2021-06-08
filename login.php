<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 로그인</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <!-- jQuery 임포트-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- 아래 4줄의 코드는 Bootstrap 임포트 코드 입니다. -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
        #body-image{
            height: 100vh;
            background-image:url("assets/image/login.jpg");
            background-repeat:no-repeat;
            background-position:center;
            background-size:cover;

        }

        #main-title {
            color: #917b56;
            font-size: 50pt;
            margin-top: 15%;
            text-shadow: 4px 4px 1px white;
        }
        #sub-title{
            color:#C9BA9B;
            font-size:20pt;
            text-align:center;
        }

        .se {font-family: serif;}
        .sa {font-family: sans-serif;}
        .cu {font-family: cursive;}
        .fa {font-family: fantasy;}
        .mo {font-family: monospace;}


    </style>
</head>
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";

// 세션 값 확인
$loginUserNickname = sessionToNickname();

// 로그인 되있을 경우 -> 메인으로 이동
if (!($loginUserNickname == null || $loginUserNickname == -1))
    echo "<script type='text/javascript'>window.location.href='/index.php';</script>";
?>
<body>

    <BODY id="body-image">
    <!-- 로그인 시 입력된 정보는 /db/user/loginUser.php 로 이동하여 서버에서 처리됩니다. -->
    <form method="post" action="/db/user/loginUser.php">
        <table align="center" height="90">

            <tr>
                <td><div id="main-title">Bucket<div></td>
            </tr>
            <br><br>
            <tr>
                <td><div id="sub-title">LOGIN</div></td>
            </tr>
        </table>

        <table align="center" height="40" width="430" border="0" style="border:solid 4px #C9BA9B; margin-top:4%">
            <tr>
                <td><input type="text" id="input_login_login_id" name="input_login_login_id" placeholder="아이디" style="height:40px; width:430px;"></td>
            </tr>
        </table>
        <table align="center" height="40" width="430" border="0" style="border:solid 4px #C9BA9B; margin-top:1%">
            <tr>
                <td><input type="password" id="input_login_password" name="input_login_password" placeholder="비밀번호" style="height:40px; width:430px"></td>
            </tr>
        </table>

        <table align="center" height="40" style="margin-top:1%">
            <tr>
                <td><button type="submit" value="로그인" style="height:50px; width:430px;  background-color:#C9BA9B;color:white; font-size:16px; border:solid 1px #7B2614; " onClick="enter()">로그인</button></td>
            </tr>
        </table>

        <table align="center" height="0" width="440" border="1" style="border:solid 1px #C9BA9B; margin-top:2%">
        </table>

        <table align="center" height="50" style="margin-top:1%" class="mo">
            <tr>
                <td><a style="text-decoration:none;" href="sign_up.php">회원가입</a></td>
                <td><span> | </span></td>
                <td><a style="text-decoration:none;" href="find_ID.php">아이디 찾기</a></td>
                <td><span> | </span></td>
                <td><a style="text-decoration:none;" href="find_PW.php">비밀번호 찾기</a></td>
            </tr>

        </table>
    </form>


</body>
</html>

<script type="text/javascript">



</script>