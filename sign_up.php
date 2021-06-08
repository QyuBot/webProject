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
    <style>
        /* 레이아웃 틀 */
        html {
            height: 100%;
        }

        body {
            margin: 0;
            height: 100%;
            background: #f5f6f7;
            color: #917b56;
            font-family: Dotum,'돋움',Helvetica,sans-serif;
        }
        #logo {
            width: 240px;
            height: 44px;
            cursor: pointer;
        }

        #header {
            padding-top: 40px;
            padding-bottom: 20px;
            text-align: center;
        }
        #wrapper {
            position: relative;
            height: 100%;
        }

        #content {
            position: absolute;
            left: 50%;
            transform: translate(-50%);
            width: 460px;
        }




        /* 입력폼 */


        h3 {
            margin: 19px 0 8px;
            font-size: 14px;
            font-weight: 700;
        }


        .box {
            display: block;
            width: 100%;
            height: 51px;
            border: solid 1px #dadada;
            padding: 10px 14px 5px 14px;
            box-sizing: border-box;
            background: #fff;
            position: relative;
        }

        .int {
            display: block;
            position: relative;
            width: 100%;
            height: 15px;
            border: none;
            background: #fff;
            font-size: 15px;
        }

        input {
            font-family: Dotum,'돋움',Helvetica,sans-serif;
        }

        .box.int_id {
            padding-right: 110px;
        }

        .box.int_pass {
            padding-right: 40px;
        }

        .box.int_pass_check {
            padding-right: 40px;
        }

        .step_url {
            /*@naver.com*/
            position: absolute;
            top: 16px;
            right: 13px;
            font-size: 15px;
            color: #8e8e8e;
        }

        .pswdImg {
            width: 18px;
            height: 20px;
            display: inline-block;
            position: absolute;
            top: 50%;
            right: 16px;
            margin-top: -10px;
            cursor: pointer;
        }

        #bir_wrap {
            display: table;
            width: 100%;
        }

        #bir_yy {
            display: table-cell;
            width: 147px;

        }

        #bir_mm {
            display: table-cell;
            width: 147px;
            vertical-align: middle;
        }

        #bir_dd {
            display: table-cell;
            width: 147px;
        }

        #bir_mm, #bir_dd {
            padding-left: 10px;
        }

        select {
            width: 100%;
            height: 29px;
            font-size: 15px;
            background: #fff url(/resources/img/sel_arr_2x.gif) 100% 50% no-repeat;
            background-size: 20px 8px;
            -webkit-appearance: none;
            display: inline-block;
            text-align: start;
            border: none;
            cursor: default;
            font-family: Dotum,'돋움',Helvetica,sans-serif;
        }

        /* 에러메세지 */

        .error_next_box {
            margin-top: 5px;
            font-size: 12px;
            color: red;
            display: none;
        }

        #alertTxt {
            position: absolute;
            top: 19px;
            right: 38px;
            font-size: 12px;
            color: red;
            display: none;
        }

        /* 버튼 */

        .btn_area {
            margin: 10px 0 91px;
        }

        #btnJoin {
            width: 100%;
            padding: 21px 0 17px;
            border: 0;
            cursor: pointer;
            color: #917b56;
            background-color: #C9BA9B;
            font-size: 20px;
            font-weight: 400;
            font-family: Dotum,'돋움',Helvetica,sans-serif;
        }

        /* 이메일 입력 칸 길이 조절 */
        .int_email {
            display: inline-block;
            width: 45%;
        }
    </style>
</head>
<body>

<!-- header -->
<div id="header">

    <h1>회원가입 하기</h1>
</div>


<!-- wrapper -->
<div id="wrapper">
    <!-- content-->
    <div id="content">

        <!-- ID -->
        <div>
            <h3 class="join_title">
                <label for="id">아이디</label>
            </h3>
            <span class="box int_id">
                <input type="text" id="input_login_id" name="input_login_id" class="int" maxlength="20">
                <span class="step_url"></span>
            </span>
            <span id="login_id_duplicate_checker_result" class="error_next_box"></span>
        </div>

        <!-- PW1 -->
        <div>
            <h3 class="join_title"><label for="pswd1">비밀번호</label></h3>
            <span class="box int_pass">
                <input type="password" id="input_password" name="input_password" class="int" maxlength="20">
                <span id="alertTxt">사용불가</span>
            </span>
            <span class="error_next_box"></span>
        </div>

        <!-- PW2 -->
        <div>
            <h3 class="join_title"><label for="pswd2">비밀번호 재확인</label></h3>
            <span class="box int_pass_check">
                <input type="password" id="input_password_dc" name="input_password_dc" class="int" maxlength="20">
            </span>
            <span class="error_next_box"></span>
        </div>

        <!-- NICKNAME -->
        <div>
            <h3 class="join_title"><label for="name">닉네임</label></h3>
            <span class="box int_name">
                <input type="text" id="input_nickname" name="input_nickname" class="int" maxlength="20">
            </span>
            <span id="nickname_duplicate_checker_result" class="error_next_box"></span>
        </div>


        <!-- EMAIL -->
        <div>
            <h3 class="join_title"><label for="email">본인확인 이메일<span class="optional">(선택)</span></label></h3>
            <span class="box int_email">
                <input type="text" id="input_email" name="input_email" class="int" maxlength="100" placeholder="이메일">
            </span>
            &nbsp;@&nbsp;
            <span class="box int_email">
                <input type="text" class="int" id="input_email_domain" name="input_email_domain" maxlength="100"
                       list="domain_list" placeholder="도메인">
                <datalist id="domain_list">
                    <option value="naver.com">
                    <option value="google.com">
                    <option value="hannam.ac.kr">
                </datalist>
            </span>
            <span id="email_duplicate_checker_result" class="error_next_box">이메일 주소를 다시 확인해주세요.</span>
        </div>



        <!-- JOIN BTN-->
        <div class="btn_area">
            <button type="button" id="btnJoin">
                <span style="color: #fff;" id="button_doRegist">가입하기</span>
            </button>
        </div>

    </div>
    <!-- content-->
</div>
<!-- wrapper -->
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
                    console.log(code);
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
        const input_passwordDoubleCheck = document.getElementById("input_password_dc");
        const input_nickname = document.getElementById("input_nickname");
        const input_email = document.getElementById("input_email");
        const input_email_domain = document.getElementById("input_email_domain");

        if (input_password.value !== input_passwordDoubleCheck.value) {
            alert('오류 : 비밀번호와 비밀번호 재확인이 서로 다릅니다.');
            return;
        }

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