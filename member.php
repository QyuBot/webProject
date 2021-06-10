<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<!-- jQuery 임포트-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<style type="text/css">
    .user_ID1{
        padding-left:20px;
    }

    .user_ID{
        padding-left: 60px;
    }
    button {
        font-size: 16px;
        width: 100px;
        text-align: center;
        height: 46px;
        border-radius:10px;
        border-color: #917b56;
        color: #fff;
    }
</style>
<main>
    <h3>멤버</h3>
    <br>
    <?php
    // 프로젝트 나가기 버튼
    echo "<a href='' onclick='quitProject({$nowLoginUser['user_id']});'>프로젝트 나가기</a><br>";

    ?>
    <br>
    <input type="hidden" id="projectId" value="<?= $project['project_id']?>">
    <input type="hidden" id="projectName" value="<?= $project['project_name']?>">
    <hr>
    <article id="managing-project">
        <div align="center"><h1> 프로젝트 사용자 정보</h1></div><hr>
            <table border="1">
                <tr>
                    <td>사용자 닉네임</td>
                    <td>작성한 이슈 수</td>
                    <td>작성한 댓글 수</td>
                    <td></td>
                </tr>
                <?php

                require_once $_SERVER["DOCUMENT_ROOT"] . "/db/user/userService.php";
                require_once $_SERVER["DOCUMENT_ROOT"] . "/db/project/projectService.php";

                $collaborators = getAllProjectCollaborators($project['project_id']);

                foreach ($collaborators as $collaborator) {

                    $user = getUserByUserId($collaborator['user_id']);
                    $issues = getAllWritedIssuesInProject($project['project_id'], $user['user_id']);
                    $comments = getAllWritedCommentsInProject($project['project_id'], $user['user_id']);

                    echo "<tr>";
                    echo "<td>{$user['user_nickname']}";

                    // 지금 행이 프로젝트의 관리자인 경우
                    if ($user['user_id'] == $project['project_admin_id'])
                        echo "[관리자] ";

                    // 지금 행이 내 정보인 경우
                    if ($user['user_id'] == $nowLoginUser['user_id'])
                        echo "(나) ";
                    echo "</td>";
                    echo "<td>".count($issues)." 개</td>";
                    echo "<td>".count($comments)." 개</td>";

                    // 현재 접속한 유저가 프로젝트 관리자 이면서, 지금 행이 내가 아닐 경우 -> 추방하기 버튼 출력
                    if ($project['project_admin_id'] == $_SESSION['sess'] && $nowLoginUser['user_id'] != $user['user_id'])
                        echo "<td><a href='' onclick='kickUser(".$user['user_id'].", ".$user['user_nickname'].");'>추방하기</a></td>";

                    // 아니면 빈칸 출력
                    else
                        echo "<td></td>";
                    echo "</tr>";
                }
                ?>
            </table>
    </article>
    <div class="btnList" style="padding-top: 50px;">
    </div>
</main>

<script>
    function quitProject(userId) {

        const projectId = document.getElementById('projectId').value;
        const projectName = document.getElementById('projectName').value;

        if (confirm('정말 \"' + projectName + '\" 프로젝트에서 탈퇴합니까? 작성한 이슈와 댓글은 유지되나, 접근할 수 없게 됩니다.')) {
            $.ajax(
                {
                    type: "POST",
                    url: "/db/project/quitMember.php",
                    data: {
                        projectId: projectId,
                        userId: userId,
                    },
                    success: (code) => {
                        console.log(code)
                        switch(code) {
                            case "missing_arg(s)":
                                alert("매개변수 오류");
                                break;
                            case "access_denied":
                                alert("접근이 거부되었습니다");
                                break;
                            case "is_admin":
                                alert("관리자는 프로젝트에서 나갈 수 없습니다. 다른 유저를 관리자로 지정한 후 시도해주세요.");
                                break;
                            case "success":
                                alert("프로젝트에서 탈퇴했습니다.");
                                window.location.href='/';
                                break;
                            default:
                                alert("예외 발생");
                        }
                    }
                }
            )

        }
    }

    function deleteProject() {

        const projectId = document.getElementById('projectId').value;
        const projectName = document.getElementById('projectName').value;

        var prom = prompt('정말 \"' + projectName + '\" 프로젝트를 삭제합니까?' +
            '\n프로젝트의 프로젝트의 모든 진행 내용(마일스톤, 이슈, 댓글, 보고서 등)이 삭제되고 되돌릴 수 없습니다.' +
            '\n삭제를 원하시면 프로젝트 이름을 입력해주세요.')

        if (prom === projectName) {
            $.ajax(
                {
                    type: "POST",
                    url: "/db/project/deleteProject.php",
                    data: {
                        projectId: projectId,
                        prom: prom,
                    },
                    success: (code) => {
                        switch(code) {
                            case "missing_arg(s)":
                                alert("매개변수 오류");
                                break;
                            case "access_denied":
                                alert("접근이 거부되었습니다");
                                break;
                            case "success":
                                alert("프로젝트가 삭제되었습니다.");
                                window.location.href='/';
                                break;
                            default:
                                alert("예외 발생");
                        }
                    }
                }
            )

        }
        else {
            alert('프로젝트를 삭제하지 않았습니다.');
        }
    }

    function kickUser(userId, name) {

        const projectId = document.getElementById('projectId').value;

        if (confirm('정말 \"' + name + '\" 유저를 추방합니까? 작성한 이슈와 댓글은 유지됩니다.')) {
            $.ajax(
                {
                    type: "POST",
                    url: "/db/project/quitMember.php",
                    data: {
                        projectId: projectId,
                        userId: userId,
                    },
                    success: (code) => {
                        console.log(code)
                        switch(code) {
                            case "no_args":
                                alert("매개변수 오류");
                                break;
                            case "access_denied":
                                alert("접근이 거부되었습니다");
                                break;
                            case "is_admin":
                                alert("관리자는 프로젝트에서 나갈 수 없습니다. 다른 유저를 관리자로 지정한 후 시도해주세요.");
                                break;
                            case "success":
                                alert("유저를 프로젝트에서 추방시켰습니다.");
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