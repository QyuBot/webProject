
<style>
    .project {
        border: 1px solid gray;
        margin: 3px;
        padding: 3px;
        width: 200px;
        height: 120px;
        display: inline-block;
    }
</style>

<h1>프로젝트 목록</h1>
<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
    require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

    // 현재 유저가 참가하고 안한 프로젝트 리스트를 구한다
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    $projectList = getJoinorNotProjectList($_SESSION['sess']);
?>
<hr>
테스트 영역
<br>
<?php

?>
<hr>
<button type="button" onclick="createProject();">프로젝트 생성하기</button>

<?php
// 프로젝트가 하나도 없다면
if ((count($projectList['join']) + count($projectList['notJoin'])) == 0) {
    echo "<h3>아직 생성된 프로젝트가 없네요</h3>";
}
// 하나라도 존재한다면
else {
    echo <<<EOT
    <h3>참가한 프로젝트</h3>
    <hr>
EOT;

    if (count($projectList['join']) == 0) {
        echo "<p>아직 참가한 프로젝트가 없어요</p>";
    }
    else {
        foreach($projectList['join'] as $row) {
            $projectName = getProjectNameByProjectId($row);
            $projectNumofCollaborators = count(getNumberofProjectCollaborators($row));
            echo "<div class='project'>";
            echo "프로젝트 ID : {$row}<br>";
            echo "프로젝트 이름 : {$projectName}<br>";
            echo "참가자 수 : {$projectNumofCollaborators}<br>";
            echo "<a href='/?projectId={$row}'>프로젝트 대쉬보드로 이동하기</a>";
            echo "</div>";
        }
    }

    echo <<<EOT
    <br>
    <br>
    <br>
    <h3>참가 안한 프로젝트</h3>
    <hr>
EOT;

    if (count($projectList['notJoin']) == 0) {
        echo "<p>존재하는 모든 프로젝트에 참가중이시네요!</p>";
    }
    else {
        foreach($projectList['notJoin'] as $row) {
            $projectName = getProjectNameByProjectId($row);
            $projectNumofCollaborators = count(getNumberofProjectCollaborators($row));
            echo "<div class='project'>";
            echo "프로젝트 ID : {$row}<br>";
            echo "프로젝트 이름 : {$projectName}<br>";
            echo "참가자 수 : {$projectNumofCollaborators}<br>";
            echo "<a href='/?projectId={$row}'>참가하기</a>";
            echo "</div>";
        }
    }
    echo <<<EOT
    <br>
    <br>
    <br>
EOT;
}

?>


<script type="text/javascript">

    function createProject() {
        var input = prompt('생성할 프로젝트 이름을 입력해주세요');
        if (input != null) {
            var confirmInput = confirm(input + ' 프로젝트를 생성합니까?');
            if (confirmInput) {
                $.ajax(
                    {
                        type: "POST",
                        url:"/db/project/createProject.php",
                        data: {
                            projectName: input,
                            adminId: '<%=(String)session.getAttribute("sess")%>',
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