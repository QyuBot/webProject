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
    <a href="" onclick="quitProject();">프로젝트 나가기</a>
    <br>
    <?php
    if (session_status() == PHP_SESSION_NONE)
        session_start();
    // 현재 접속한 유저가 프로젝트 관리자 일 경우
    if ($project['project_admin_id'] == $_SESSION['sess'])
        echo "<a href='' onclick='deleteProject();'>프로젝트 삭제하기</a>";

    ?>
    <br>
    <input type="hidden" id="projectId" value="<?= $project['project_id']?>">
    <input type="hidden" id="projectName" value="<?= $project['project_name']?>">
    <hr>
    <article id="managing-project">
        <div align="center"><h1> 프로젝트 사용자 정보</h1></div><hr>


    </article>
    <div class="btnList" style="padding-top: 50px;">
    </div>
</main>

<script>
    function quitProject() {

        const projectId = document.getElementById('projectId').value;
        const projectName = document.getElementById('projectName').value;

        if (confirm('정말 \"' + projectName + '\" 프로젝트에서 탈퇴합니까? 작성한 이슈와 댓글은 유지되나, 접근할 수 없게 됩니다.')) {
            $.ajax(
                {
                    type: "POST",
                    url: "/db/project/quitMember.php",
                    data: {
                        projectId: projectId,
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


</script>