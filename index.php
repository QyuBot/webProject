<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 메인</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css?after">
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
<header-script>
    <!-- 이곳의 내용은 여러 페이지에서 공용으로 사용합니다. -->
    <?php
    // 경로 탐색으로 직접 접근하는 행위 예방하는 코드 (ex : http://localhost/projects.php 로 접속)
    define('DirectAccessCheck', TRUE);

    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

    // 로그인이 되어있지 않을 경우 -> 로그인 페이지로 이동
    $loginUserNickname = sessionToNickname();
    if ($loginUserNickname == null || $loginUserNickname == -1)
        echo "<script type='text/javascript'>window.location.href='/login.php';</script>";

    // 프로젝트가 선택되었다 = 클라이언트가 URL에 &projectId=? 를 포함된 상태로 접속하고, ? 가 존재하는 프로젝트의 ID 인 경우
    // 현재 선택된 프로젝트를 구해와서 존재하는 프로젝트인 경우 플래그를 설정하는 로직
    $isProjectSelected = false;
    $project = null;
    if (isset($_GET['projectId'])) {

        $projectId = $_GET['projectId'];
        $project = getProjectByProjectId($projectId);

        // URL에 프로젝트 ID가 설정되어 있으나, 해당 ID의 프로젝트가 없는 경우 -> 프로젝트 목록으로 강제 이동
        if (empty($project)) {
            echo "<script type='text/javascript'>alert('존재하지 않는 프로젝트 입니다.');</script>";
            echo "<script type='text/javascript'>window.location.href='/';</script>";
        }
        else {

            if (session_status() == PHP_SESSION_NONE)
                session_start();

            // 유저가 프로젝트에 참가한 경우(접근 권한이 있는 경우)
            if (isUserJoinedProject($projectId, $_SESSION['sess'])) {
                $isProjectSelected = true;
                $url = "/?projectId={$_GET['projectId']}";
            }
            // 유저가 참가하지 않은 프로젝트에 접근하는 경우 (접근 권한이 있는 경우)
            else {
                echo "<script type='text/javascript'>alert('접근 권한이 없습니다.');</script>";
                echo "<script type='text/javascript'>window.location.href='/';</script>";
            }
        }
    }

    ?>
</header-script>
<script type="text/javascript">

    // 현재 클라이언트가 접속한 URL에서 GET 메소드를 가져오는 함수
    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    // 네브바 활성화 시키기
    function activeNav(id) {
        var nav = document.getElementById('nav' + id);
        nav.className = 'nav__link active';
    }

</script>
<body id="body-pd">
    <!-- Navbar Start -->
    <div class="l-navbar" id="navbar">
        <nav class="nav">
            <div>
                <div class="nav__brand">
                    <ion-icon name="menu-outline" class="nav__toggle" id="nav-toggle"></ion-icon>
                    <a href="/" class="nav__logo"><?=$loginUserNickname?> 님</a>
                </div>
                <div class="nav__list">
                    <a href="/" id='nav0' class="nav__link">
                        <ion-icon name="home-outline" class="nav__icon"></ion-icon>
                        <span class="nav_name">프로젝트 목록</span>
                    </a>

                    <?php

                    // 프로젝트가 잘 선택되어 있으면
                    if ($isProjectSelected) {
                        echo "<a href='{$url}' id='nav1' class='nav__link'>
                            <ion-icon name='folder-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>대쉬보드</span>
                            </a>";

                        echo "<a href='{$url}&page=milestone' id='nav2' class='nav__link'>
                            <ion-icon name='albums-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>이정표</span>
                            </a>";

                        echo "<a href='{$url}&page=issue' id='nav3' class='nav__link'>
                            <ion-icon name='alert-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>목표</span>
                            </a>";

                        echo "<a href='{$url}&page=member' id='nav4' class='nav__link'>
                            <ion-icon name='people-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>프로젝트 맴버</span>
                            </a>";

                        echo "<a href= '{$url}&page=report' id='nav5' class='nav__link'>
                            <ion-icon name='document-text-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>보고서</span>
                            </a>";

                        echo "<a href= '{$url}&page=freeboard' id='nav6' class='nav__link'>
                            <ion-icon name='chatbubbles-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>자유게시판</span>
                            </a>";

                        echo "<a href='{$url}&page=setting' id='nav7' class='nav__link'>
                            <ion-icon name='settings-outline' class='nav__icon'></ion-icon>
                            <span class='nav_name'>프로젝트 설정</span>
                            </a>";

                    }
                    ?>

                    <a href="/db/user/logoutUser.php" class="nav__link">
                        <ion-icon name="log-out-outline" class="nav__icon"></ion-icon>
                        <span class="nav_name">로그아웃</span>
                    </a>
                    <a href="footer.html" class="nav__link">
                        <ion-icon name="search" class="nav__icon"></ion-icon>
                        <span class="nav_name">Footer</span>
                    </a>

                </div>
        </nav>
    </div>
    <!-- IONICONS -->
    <script type="module" src="https://unpkg.com/ionicons@5.2.3/dist/ionicons/ionicons.esm.js"></script>
    <!-- JS -->
    <script src="assets/js/main.js"></script>

    <!-- Navbar End -->
    <header>
        <h2>Bucket</h2>
    </header>

    <img class="head_img" src ="assets/image/bucket.svg">

    <?php
    // GET 으로 선택한 프로젝트가 없을 경우 -> 프로젝트 목록 표시
    if (!$isProjectSelected) {
        echo "<script type='text/javascript'>activeNav(0);</script>";
        require_once $_SERVER["DOCUMENT_ROOT"] . "/projects.php";
    }
    // 프로젝트가 선택된 경우 -> 대쉬보드 표시
    else
        require_once $_SERVER["DOCUMENT_ROOT"] . "/projectmain.php";
    ?>


</body>
</html>