<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 메인</title>
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

    </style>
</head>
<body>
    <header>
        <?php
        define('DirectAccessCheck', TRUE);

        require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
        require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

        $loginUserNickname = sessionToNickname();

        // 로그인이 되어있지 않을 경우 -> 로그인 페이지로 이동
        if ($loginUserNickname == null || $loginUserNickname == -1)
            echo "<script type='text/javascript'>window.location.href='/login.php';</script>";


        ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">White Bucket</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?=$loginUserNickname?> 님
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/db/user/logoutUser.php">로그아웃</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <?php
    // GET 으로 선택한 프로젝트가 없을 경우 -> 프로젝트 목록으로 이동
    if (!isset($_GET['projectId'])) {
        require_once $_SERVER["DOCUMENT_ROOT"] . "/projects.php";
    }
    else {

        $projectId = $_GET['projectId'];
        $projectInfo = getProject($projectId);

        // GET 으로 선택한 프로젝트 ID가 없는 프로젝트 ID 일 경우 -> GET 매개변수를 지우고 메인으로 이동
        if (empty($projectInfo)) {
            echo "<script type='text/javascript'>alert('존재하지 않는 프로젝트 입니다.');</script>";
            echo "<script type='text/javascript'>window.location.href='/';</script>";
        }
        // 존재하는 프로젝트 ID 인 경우 -> 권한 채크 후 프로젝트 대쉬보드로 이동
        else {
            if (session_status() == PHP_SESSION_NONE)
                session_start();
            
            if (isUserJoinedProject($projectId, $_SESSION['sess'])) {
                require_once $_SERVER["DOCUMENT_ROOT"] . "/projectmain.php";
            }
            else {
                echo "<script type='text/javascript'>alert('접근 권한이 없습니다.');</script>";
                echo "<script type='text/javascript'>window.location.href='/';</script>";
            }
        }
    }
    ?>


</body>
</html>

<script type="text/javascript">

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

</script>