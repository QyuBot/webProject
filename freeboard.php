<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<style>
button {
    font-size: 16px;
    width: 80px;
    text-align: center;
    height: 46px;
    border-radius:10px;
    border-color: #917b56;
    color: #fff;
    margin-left: 25px;
}
</style>

<main>
    <h3>FreeBoard</h3>
    <hr>
    <div class="btnList">
         <button id="new">글 작성</button>
         <button id="ref">글 수정</button>
         <button id="del">글 삭제</button>
    </div>
</main>