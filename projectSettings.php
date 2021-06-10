<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>
<style type="text/css">
    .setting-projectbox{
        border: 1px solid;
        float: left;
        width: 49%;
        padding: 10px;
    }

    .setting-memberbox{
        border: 1px solid;
        margin-left: 10px;
        float: left;
        width: 50%;
        padding: 10px;
    }
</style>
<main>
    <h3>Setting</h3>
    <hr>
    <div class="setting-projectbox">
        <h3>프로젝트 정보</h3>
        <div>프로젝트 이름 : </div>
        <div>구성원 수 : </div>
        <div>시작 날짜 : </div>

    </div>
    <div class="setting-memberbox">
        <h3>구성원 권한</h3>
        <div>홍길동(DB사용)
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