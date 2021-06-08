<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<style type="text/css">

    .user_ID1{
        padding-left:20px;
    }

    .user_ID{
        padding-left: 60px;
    }

</style>
<main>
    <h3>프로젝트 관리</h3>
    <hr>
    <article id="managing-project">
        <div align="center"><h1> 프로젝트 사용자 정보</h1></div><hr>
        <div class="user_name">사용자 이름<span class="user_ID1"></span>사용자 ID</div>
        <div class="user_name">김한욱<span class="user_ID"></span>2016----</div>
        <div class="user_name">성민석<span class="user_ID"></span>2016----</div>
        <div class="user_name">김규보<span class="user_ID"></span>2016----</div>
        <div class="user_name">이민혁<span class="user_ID"></span>2015----</div>

    </article>
    <div class="btnList" style="padding-top: 50px;">
        <button id="new">멤버추가</button>
        <button id="ban" style="margin-left: 20px;">멤버추방</button>
    </div>
</main>