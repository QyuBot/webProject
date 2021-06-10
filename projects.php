<!-- jQuery 임포트-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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

<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

// 현재 유저가 참가하고 안한 프로젝트 리스트를 구한다
if (session_status() == PHP_SESSION_NONE)
    session_start();
$projectList = getJoinorNotProjectList($_SESSION['sess']);
?>

<main>
    <button type="button" onclick="createProject();">프로젝트 생성하기</button>
    <h3>프로젝트 제목</h3>
    <hr>
    <article id="my-project">
        <h3>참가한 프로젝트 : <?= count($projectList['join']) ?>개</h3>
        <div class="my-box">
            <?php
            if (count($projectList['join']) == 0)
                echo "<p>아직 참가한 프로젝트가 없어요</p>";
            else {
                foreach($projectList['join'] as $row) {
                    $project = getProjectByProjectId($row);
                    $projectNumofCollaborators = count(getAllProjectCollaborators($row));
                    echo "<div class='project'>";
                    if ($nowLoginUser['user_id'] == $project['project_admin_id'])
                        echo "내가 관리자인 프로젝트<br>";
                    echo "프로젝트 ID : {$row}<br>";
                    echo "프로젝트 이름 : {$project['project_name']}<br>";
                    echo "참가자 수 : {$projectNumofCollaborators}<br>";
                    echo "<a href='/?projectId={$row}'>프로젝트 대쉬보드로 이동하기</a>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </article>
    <article id="you-project">
        <h3>다른 프로젝트 : <?= count($projectList['notJoin']) ?>개</h3>
        <div class="you-box">
            <?php
            if (count($projectList['notJoin']) == 0)
                echo "<p>존재하는 모든 프로젝트에 참가중이시네요!</p>";
            else {
                foreach($projectList['notJoin'] as $row) {
                    $projectName = getProjectNameByProjectId($row);
                    $projectNumofCollaborators = count(getAllProjectCollaborators($row));
                    echo "<div class='project'>";
                    echo "프로젝트 ID : {$row}<br>";
                    echo "프로젝트 이름 : {$projectName}<br>";
                    echo "참가자 수 : {$projectNumofCollaborators}<br>";
                    echo "<a href='' onclick='joinProject({$row}, \"{$projectName}\");'>참가하기</a>";
                    echo "</div>";
                }
            }

            ?>
        </div>
    </article>
</main>

<script type="text/javascript">

    function createProject() {
        var input = prompt('생성할 프로젝트 이름을 입력해주세요');
        if (input != null) {
            var confirmInput = confirm(input + ' 프로젝트를 생성합니까?');
            if (confirmInput) {
                $.ajax(
                    {
                        type: "POST",
                        url: "/db/project/createProject.php",
                        data: {
                            projectName: input,
                            adminId: '<%=(String)session.getAttribute("sess")%>',
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
            else
                alert('프로젝트가 생성되지 않았습니다.');
        }
        else
            alert('프로젝트가 생성되지 않았습니다.');

    }

    function joinProject(projectId, projectName) {
        if (confirm('정말 ' + projectName + ' 프로젝트에 참가하겠습니까?')) {

            $.ajax(
                {
                    type: "POST",
                    url: "/db/project/addMember.php",
                    data: {
                        projectId: projectId,
                    },
                    success: (code) => {
                        console.log(code);
                        switch(code) {
                            case "no_args":
                                alert("매개변수 오류");
                                break;
                            case "prject_not_exist":
                                alert("이미 해당 프로젝트에 참가 중 입니다.");
                                break;
                            case "already_join":
                                alert("존재하지 않는 프로젝트 입니다.");
                                break;
                            case "success":
                                alert("프로젝트에 참가하였습니다.");
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