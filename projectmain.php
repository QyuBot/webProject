

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
