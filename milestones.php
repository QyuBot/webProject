<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
?>

<h1>마일스톤 목록</h1>
<br>
<button type="button" onclick="createMilestone();">마일스톤 생성하기</button>
<br>
<br>

<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/db/milestone/milestoneService.php";
$milestones = getMilestoneListinProject($_GET['projectId']);
if (count($milestones) == 0)
    echo "이정표(마일스톤)가 없네요. 길을 잃었어요";
else {
    foreach ($milestones as $milestone) {
        $containsIssues = getIssueContainsMilestone($milestone['milestone_id']);
        echo "<div class='milestone'>";
        echo "마일스톤 이름 : {$milestone['milestone_name']}<br>";
        echo "포함된 이슈 갯수 : ".count($containsIssues)."<br>";
        echo "진행도 : ".getPrograssPercentage($milestone['milestone_id'])." %<br>";
        echo "<button type='button' onclick='deleteMilestone({$milestone['milestone_id']});'>마일스톤 삭제하기</button>";
        echo "<button type='button' onclick='renameMilestone({$milestone['milestone_id']});'>마일스톤 이름 변경하기</button>";
        echo "</div>";
    }
}

?>

<br>
<script type="text/javascript">

    function createMilestone() {
        var input = prompt('생성할 마일스톤 이름을 입력해주세요');
        if (input != null) {
            var confirmInput = confirm(input + ' 마일스톤을 생성합니까?');
            if (confirmInput) {

                const projectId = getParameterByName('projectId');

                $.ajax(
                    {
                        type: "POST",
                        url: "/db/milestone/createMilestone.php",
                        data: {
                            projectId: projectId,
                            milestoneName: input,
                        },
                        success: (code) => {
                            switch(code) {
                                case "no_args":
                                    alert("매개변수 오류");
                                    break;
                                case "access_denied":
                                    alert("접근이 거부되었습니다");
                                    break;
                                case "duplicate_name":
                                    alert("이미 존재하는 마일스톤 이름 입니다");
                                    break;
                                case "success":
                                    alert("마일스톤이 생성되었습니다");
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

    function renameMilestone(milestoneId) {
        var input = prompt('변경할 마일스톤 이름을 입력해주세요');
        if (input != null) {
            var confirmInput = confirm('마일스톤 이름을 ' + input + ' 으로 변경합니까??');
            if (confirmInput) {
                $.ajax(
                    {
                        type: "POST",
                        url: "/db/milestone/renameMilestone.php",
                        data: {
                            milestoneId: milestoneId,
                            newName: input,
                        },
                        success: (code) => {
                            switch (code) {
                                case "no_args":
                                    alert("매개변수 오류");
                                    break;
                                case "access_denied":
                                    alert("접근이 거부되었습니다");
                                    break;
                                case "duplicate_name":
                                    alert("이미 존재하는 마일스톤 이름 입니다");
                                    break;
                                case "success":
                                    alert("마일스톤 이름이 변경되었습니다");
                                    location.reload();
                                    break;
                                default:
                                    alert("예외 발생");
                            }
                        }
                    }
                )
            }

        }
    }

    function deleteMilestone(milestoneId) {
        var confirmInput = confirm('정말 마일스톤을 삭제합니까? 배정되었던 이슈들은 배정이 해제됩니다.');
        if (confirmInput) {
            $.ajax(
                {
                    type: "POST",
                    url: "/db/milestone/deleteMilestone.php",
                    data: {
                        milestoneId: milestoneId,
                    },
                    success: (code) => {
                        switch(code) {
                            case "no_args":
                                alert("매개변수 오류");
                                break;
                            case "access_denied":
                                alert("접근이 거부되었습니다");
                                break;
                            case "success":
                                alert("마일스톤이 삭제되었습니다");
                                location.reload();
                                break;
                            default:
                                alert("예외 발생");
                        }
                    }
                }
            )
        }
    }

</script>