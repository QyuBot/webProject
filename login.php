<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 로그인</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <style>

    </style>
</head>
<body>
    <?php
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";

    // 세션 값 확인
    $loginUserNickname = sessionToNickname();

    // 로그인 되있을 경우 -> 메인으로 이동
    if (!($loginUserNickname == null || $loginUserNickname == -1))
        echo "<script type='text/javascript'>window.location.href='/index.php';</script>";
    ?>

    <h1>White Bucket</h1>
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
    <br>
    <h3>회원가입</h3>
    <hr>
    <form method="POST" action="/db/user/registUser.php">
        <table>
            <tr>
                <td><span>ID</span></td>
                <td><input type="text" id="input_login_id" name="input_login_id"></td>
                <td><span id="login_id_duplicate_checker_result"></span></td>
            </tr>
            <tr>
                <td><span>비밀번호</span></td>
                <td><input type="password" id="input_password" name="input_password"></td>
                <td></td>
            </tr>
            <tr>
                <td><span>닉네임</span></td>
                <td><input type="text" id="input_nickname" name="input_nickname"></td>
                <td><span id="nickname_duplicate_checker_result"></span></td>
            </tr>
            <tr>
                <td><span>이메일</span></td>
                <td><input type="text" id="input_email" namne="input_email"> @</td>
                <td>
                    <input type="text" id="input_email_domain" name="input_email_domain" list="domain_list">
                    <datalist id="domain_list">
                        <option value="naver.com">
                        <option value="google.com">
                        <option value="hannam.ac.kr">
                    </datalist>
                </td>
                <td><span id="email_duplicate_checker_result"></td>
            </tr>
        </table>
        <br>
        <button type="button" id="button_doRegist">회원가입하기</button>
    </form>
</body>
</html>

<script type="text/javascript">

    // ID 중복 확인
    $("#input_login_id").change(() => {
        const input = document.getElementById("input_login_id");
        const result_span = document.getElementById("login_id_duplicate_checker_result");
        $.ajax(
            {
                type: "POST",
                url:"/db/user/isLoginIdExist.php",
                data: {
                    loginId: input.value,
                },
                // 성공 시 아래 코드가 작동합니다.
                success: (code) => {
                    switch(code) {
                        case "no_args":
                            result_span.innerText = "ID를 입력해주세요"
                            break;
                        case "id_too_short":
                            result_span.innerText = "ID는 4글자 이상이여야 합니다"
                            break;
                        case "not_exist":
                            result_span.innerText = "존재하지 않는 ID 입니다"
                            break;
                        case "exist":
                            result_span.innerText = "존재하는 ID 입니다"
                            break;
                        default:
                            result_span.innerText = "예외 발생"
                    }
                }
            }
        )
    })

    // 닉네임 중복 확인 (ID 중복 확인과 로직 동일함)
    $("#input_nickname").change(() => {
        const input = document.getElementById("input_nickname");
        const result_span = document.getElementById("nickname_duplicate_checker_result");
        $.ajax(
            {
                type: "POST",
                url:"/db/user/isNicknameExist.php",
                data: {
                    nickname: input.value,
                },
                success: (code) => {
                    switch(code) {
                        case "no_args":
                            result_span.innerText = "닉네임을 입력해주세요"
                            break;
                        case "not_exist":
                            result_span.innerText = "존재하지 않는 닉네임 입니다"
                            break;
                        case "exist":
                            result_span.innerText = "존재하는 닉네임 입니다"
                            break;
                        default:
                            result_span.innerText = "예외 발생"
                    }
                }
            }
        )
    })

    // 이메일 중복 확인 (ID 중복 확인과 로직 동일함)
    // email과 domain 을 둘 다 입력했는지 확인
    var flag_email = false;
    var flag_email_domain = false;

    $("#input_email").change(() => {
        flag_email = true;
        emailDuplicationCheck();
    })
    $("#input_email_domain").change(() => {
        flag_email_domain = true;
        emailDuplicationCheck();
    })

    function emailDuplicationCheck() {

        if(!(flag_email && flag_email_domain))
            return;

        const input_email = document.getElementById("input_email");
        const input_email_domain = document.getElementById("input_email_domain");
        const result_span = document.getElementById("email_duplicate_checker_result");
        $.ajax(
            {
                type: "POST",
                url:"/db/user/isEmailExist.php",
                data: {
                    email: input_email.value,
                    email_domain: input_email_domain.value,
                },
                success: (code) => {
                    switch(code) {
                        case "no_email":
                            result_span.innerText = "이메일을 입력해주세요"
                            break;
                        case "no_domain":
                            result_span.innerText = "이메일 도메인을 입력해주세요"
                            break;
                        case "empty_field":
                            result_span.innerText = "두 입력 칸 모두 입력해주세요"
                            break;
                        case "not_exist":
                            result_span.innerText = "존재하지 않는 이메일 입니다"
                            break;
                        case "exist":
                            result_span.innerText = "존재하는 이메일 입니다"
                            break;
                        default:
                            result_span.innerText = "예외 발생"
                    }
                }
            }
        )
    }

    // 회원가입
    $("#button_doRegist").click(() => {
        const input_id = document.getElementById("input_login_id");
        const input_password = document.getElementById("input_password");
        const input_nickname = document.getElementById("input_nickname");
        const input_email = document.getElementById("input_email");
        const input_email_domain = document.getElementById("input_email_domain");

        $.ajax(
            {
                type: "POST",
                url:"/db/user/registUser.php",
                data: {
                    login_id: input_id.value,
                    password: input_password.value,
                    nickname: input_nickname.value,
                    email: input_email.value,
                    email_domain: input_email_domain.value,
                },
                success: (code) => {
                    console.log(code);
                    switch(code) {
                        case "success":
                            alert("회원가입 성공");
                            break;
                        case "fail":
                            alert("회원가입 실패");
                            break;
                        case "missing_arg(s)":
                            alert("회원가입 실패(매개변수 오류)");
                            break;
                        case "exception":
                            alert("회원가입 실패(내부 오류)");
                            break;
                        default:
                            alert("예외 발생");
                    }
                }
            }
        )
    })

</script>