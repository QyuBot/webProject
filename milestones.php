
<style>
    .milestone {
        border: 1px solid gray;
        margin: 3px;
        padding: 3px;
        width: 600px;
        height: 140px;
    }
</style>

<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
echo "<input type='hidden' id='projectId' value='{$_GET['projectId']}'>";
?>

<h1>마일스톤 목록</h1>
<br>
<button type="button" onclick="createMilestone();">마일스톤 생성하기</button>
<br>
<br>

<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";
echo "ID : {$_GET['projectId']}<br>";
$milestones = getMilestoneList($_GET['projectId']);
if (count($milestones) == 0)
    echo "이정표(마일스톤)가 없네요. 길을 잃었어요";
else {
    foreach ($milestones as $milestone) {
        $containsIssues = getIssueContainsMilestone($milestone['milestone_id']);
        echo "<div class='milestone'>";
        echo "마일스톤 이름 : {$milestone['milestone_name']}<br>";
        echo "포함된 이슈 갯수 : ".count($containsIssues)."<br>";
        echo "진행도 : ".getPrograssPercentage($milestone['milestone_id'])." %<br>";
        echo "</div>";
    }
}

?>

<script type="text/javascript">

    function createMilestone() {
        var input = prompt('생성할 마일스톤 이름을 입력해주세요');
        if (input != null) {
            var confirmInput = confirm(input + ' 마일스톤을 생성합니까?');
            if (confirmInput) {
                const projectId = document.getElementById('projectId').value;
                $.ajax(
                    {
                        type: "POST",
                        url:"/db/milestone/createMilestone.php",
                        data: {
                            projectId: projectId,
                            milestoneName: input,
                        },
                        success: (code) => {
                            console.log(code);
                            switch(code) {
                                case "no_args":
                                    alert("매개변수 오류");
                                    break;
                                case "access_denied":
                                    alert("접근이 거부되었습니다");
                                    break;
                                case "duplicate_name":
                                    alert("이미 존재하는 프로젝트 ID 입니다");
                                    break;
                                case "success":
                                    alert("프로젝트가 생성되었습니다");
                                    location.reload();
                                    break;
                                default:
                                    alert("예외 발생");
                            }
                        }
                    }
                )
            }
            else {
                alert('프로젝트가 생성되지 않았습니다.');
            }
        }
        else {
            alert('프로젝트가 생성되지 않았습니다.');
        }

    }

</script>