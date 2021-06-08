
<?php
if(!defined('DirectAccessCheck')){
    header('HTTP/1.0 404 Not Found', true, 404);
    exit;
}
?>

<!-- Report, Comment -->
<main>
    <section>
        <h3>Report</h3>
        <hr>
        <div class="btnList">
            <button id="new">글 작성</button>
            <button id="ref">글 수정</button>
            <button id="del">글 삭제</button>
        </div>
    </section>

    <hr>

    <section>
        <div id="comments">
            <div id=comment-head class="comment-row">
                <span id="comments-count">0</span> Comment(s)
            </div>
        </div>
        <div class="comment-row">
            <textarea id="new-comment" name="new_comment" rows=5 placeholder="New Comment"></textarea>
            <button type="submit" onclick="submitComment()">작성</button>
        </div>
    </section>


<script type="text/javascript">
    function warnEmpty(){
        alert("내용을 입력해주세요.");
    } /*빈칸일때 경고함수*/

    function dateToString(date){
        var dateString = date.toISOString();
        var dateToString = dateString.substring(0, 10) + " " + dateString.substring(11, 19);
        return dateToString;
    } /*날짜 받아오기*/

    function submitComment(){
        var newcommentEL = document.getElementById("new-comment");
        var newcomment = newcommentEL.value.trim();

        if(newcomment){
            var dateEL = document.createElement('div');
            dateEL.classList.add("comment-date");
            var dateString = dateToString(new Date());
            dateEL.innerText = dateString;

            var contentEL = document.createElement('div');
            contentEL.classList.add("comment-content");
            contentEL.innerText = newcomment;

            var commentEL = document.createElement('div');
            commentEL.classList.add('comment-row');
            commentEL.appendChild(dateEL);
            commentEL.appendChild(contentEL);

            document.getElementById('comments').appendChild(commentEL);
            newcommentEL.value="";

            var countEL = document.getElementById('comments-count');
            var count = countEL.innerText;
            countEL.innerText = parseInt(count)+1;
        } else warnEmpty();
    } // 댓글 입력
</script>