<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>로그인 테스트 페이지</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>
        td {
            padding: 3px;
        }
    </style>
</head>
<body>
<h1>로그인 테스트 페이지 입니다.</h1>
<br>
<h3>현재 로그인 정보</h3>
<hr>
<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
$loginUserNickname = sessionToNickname();
// 로그인 되있을 경우
if ($loginUserNickname == null || $loginUserNickname == -1){
    echo "<p>로그인하지 않았습니다.</p>";
}
// 로그인이 안되있거나, 세션을 찾을 수 없는 경우
else {
    echo "<p>당신은 {$loginUserNickname}님 입니다.</p>";
}
?>
<br>
<br>
<br>
<h3>로그인 하기</h3>
<form method="post" action="/db/user/loginUser.php">
    <table>
        <tr>
            <td>ID</td>
            <td><input type="text" id="input_login_id" name="input_login_id"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="password" id="input_password" name="input_password"></td>
        </tr>
    </table>
    <br>
    <button type="submit">로그인 하기</button>
</form>
<br>
<form method="post" action="/db/user/logoutUser.php">
    <button type="submit">로그아웃 하기</button>
</form>
</body>
</html>

<script type="text/javascript">


</script>