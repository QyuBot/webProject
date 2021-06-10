
<?php $url = "/?projectId={$_GET['projectId']}"; ?>



<?php
    // GET page 값에 따라 nav바(사이드바) active 세팅 및 별도의 페이지 출력
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
            case "issueEditor":
                echo "<script type='text/javascript'>activeNav(3);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/issueEditor.php";
                break;
            case "issueViewer":
                echo "<script type='text/javascript'>activeNav(3);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/issueViewer.php";
                break;
            case "member":
                echo "<script type='text/javascript'>activeNav(4);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/member.php";
                break;
            case "report":
                echo "<script type='text/javascript'>activeNav(5);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/reports.php";
                break;
            case "reportEditor":
                echo "<script type='text/javascript'>activeNav(5);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/reportEditor.php";
                break;
            case "reportViewer":
                echo "<script type='text/javascript'>activeNav(5);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/reportViewer.php";
                break;
            case "freeboard":
                echo "<script type='text/javascript'>activeNav(6);</script>";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/freeboard.php";
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
