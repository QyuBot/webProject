<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>화이트버킷 - 대쉬보드</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <!-- jQuery 임포트-->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- 아래 4줄의 코드는 Bootstreap 임포트 코드 입니다. -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>

    </style>
</head>
<body>
    <header>
        <?php $url = "/?projectId={$_GET['projectId']}"; ?>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" id="nav1" href="<?=$url?>">대시보드</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav2" href="<?=$url."&page=milestone"?>"">마일스톤</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav3" href="<?=$url."&page=issue"?>">이슈</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav4" href="<?=$url."&page=report"?>">보고서</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nav5" href="<?=$url."&page=setting"?>">프로젝트 설정</a>
            </li>
        </ul>

        <script type="text/javascript">

            function activeNav(id) {
                var nav = document.getElementById('nav' + id);
                nav.className = "nav-link active";
            }

        </script>
    </header>
    <div class="container-fluid">
        <?php
            if (isset($_GET['page'])) {
                switch ($_GET['page']) {
                    case "milestone":
                        echo "<script type='text/javascript'>activeNav(2);</script>";
                        require_once $_SERVER["DOCUMENT_ROOT"] . "/milestones.php";
                        break;
                    case "issue":
                        echo "<script type='text/javascript'>activeNav(3);</script>";
                        require_once $_SERVER["DOCUMENT_ROOT"] . "/issues.php";
                        break;
                    case "report":
                        echo "<script type='text/javascript'>activeNav(4);</script>";
                        require_once $_SERVER["DOCUMENT_ROOT"] . "/reports.php";
                        break;
                    case "setting":
                        echo "<script type='text/javascript'>activeNav(5);</script>";
                        require_once $_SERVER["DOCUMENT_ROOT"] . "/projectSettings.php";
                        break;
                    default:
                        echo "<script type='text/javascript'>activeNav(1);</script>";
                        require_once $_SERVER["DOCUMENT_ROOT"] . "/dashboard.php";
                }
            }
            else {
                echo "<script type='text/javascript'>activeNav(1);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/dashboard.php";
            }

        ?>
    </div>
</body>
</html>
