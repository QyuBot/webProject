<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>
<main>
    <h3>FreeBoard</h3>
    <hr>
    <div class="btnList">
         <button id="new">글 작성</button>
         <button id="ref">글 수정</button>
         <button id="del">글 삭제</button>
    </div>
</main>