<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>글쓰기 테스트</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="lang/summernote-ko-KR.js"></script>
    <style>
        body {
            padding: 2%;
        }
    </style>
</head>
<body>
<div class="col-12 col-md-12 item">
    <form action="#" method="post">
        <input type="hidden" name="imgUrl" id="imgUrl" value="">
        <input type="hidden" name="attachFile" id="attachFile" value="">
        <div class="form-group">
            <input type="text" class="form-control" id="subject" placeholder="제목">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="childName" placeholder="이름">
        </div>
        <div class="form-group">
            <div id="summernote"></div>
        </div>
        <div class="form-group">
            <div id="attach_site">
                <div id="attachFiles">
                </div>
                <input type="file" multiple class="form-input" name="afile" id="afile" />
            </div>
        </div>
        <button type="button" class="btn contact-btn"  onclick="saveUp();">WRITE</button>
    </form>
</div>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function() {
        var $summernote = $('#summernote').summernote({
            codeviewFilter: false,
            codeviewIframeFilter: true,
            lang: 'ko-KR', // default: 'en-US'
            height: 400,
            callbacks: {
                onImageUpload: function (files) {
                    for(var i=0; i < files.length; i++) {
                        if(i>20){
                            alert('파일은 20개까지만 등록할 수 있습니다.');
                            return;
                        }
                    }
                    for(var i=0; i < files.length; i++) {
                        if(i>20){
                            alert('파일은 20개까지만 등록할 수 있습니다.');
                            return;
                        }
                        sendFile($summernote, files[i]);
                    }
                }
            }
        });
    });

    function sendFile($summernote, file) {
        var formData = new FormData();
        formData.append("file", file);
        $.ajax({
            url: '/board/saveImage.php',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data) {
                if (data == -1){
                    alert('용량이 너무크거나 이미지 파일이 아닙니다.');
                    return;
                } else {
                    $summernote.summernote('insertImage', data, function ($image) {
                        $image.attr('src', data);
                        $image.attr('class', 'childImg');
                    });
                    var imgUrl = $("#imgUrl").val();
                    if(imgUrl){
                        imgUrl = imgUrl+",";
                    }
                    $("#imgUrl").val(imgUrl+data);
                }
            }
        });

    }

</script>