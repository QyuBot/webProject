
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>
<style type="text/css">
    .setting-projectbox{
        margin-top: 20px;
        border: 1px solid;
        float: left;
        width: 80%;
        padding: 20px;
    }

    .setting-memberbox{
        border: 1px solid;
        float: left;
        width: 80%;
        padding: 20px;
        margin-top: 30px;
    }
    .setting-membernamebox{
        margin-left: 15px;
    }
</style>
<main>
    <h3>프로젝트 제목 : <?php echo($project['project_name']); /* $project 는 index.php 에서 선언되어 내려옴 */  ?></h3>
    <hr>
    <div class="setting-projectbox">
        <h3>프로젝트 정보</h3>
        <div style="padding-top: 10px;">프로젝트 이름 : </div>
        <div>구성원 수 : </div>
        <div>시작 날짜 : </div>

    </div>
    <div class="setting-memberbox">
        <h3>구성원 권한</h3>
        <div style="padding-top: 20px;">홍길동(DB사용)
            <span class="setting-membernamebox">
                    <select id="setting-mname">
                        <option value="01">방장</option>
                        <option value="02">매니저</option>
                        <option value="03">조원</option>
                    </select>
                </span>
            </div>
        </div>
    </div>
</main>