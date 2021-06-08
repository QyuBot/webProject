<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>아이디 찾기</title>
	<style type="text/css">

  #body-image{

    height: 100vh;
    background-image:url("assets/image/login.jpg");
    background-repeat:no-repeat;
    background-position:center;
    background-size:cover;  	
}

#wrapper{
 display: flex;
 justify-content: center;
 align-items: center;
 min-height:100vh;
 text-align: center;
}
body {
    color: #917b56;
}
button {
    border-radius: .5rem;
    background-color: #C9BA9B;
    color: #fff;
}
</style>
</head>
<body id="body-image">
  <!-- header -->
  <div id="header">


  </div>


  <!-- wrapper -->
  <div id="wrapper">

    <!-- content-->
    <div id="content">

       <h1>아이디 찾기</h1>


       <!-- NAME -->
       <div>
        <h3 class="join_title"><label for="name">이름</label></h3>
        <span class="box int_name">
            <input type="text" id="name" class="int" maxlength="20">
        </span>
        <span class="error_next_box"></span>
    </div>



    <!-- MOBILE -->
    <div>
        <h3 class="join_title"><label for="phoneNo">휴대전화</label></h3>
        <span class="box int_mobile">
            <input type="tel" id="mobile" class="int" maxlength="16" placeholder="전화번호 입력">
        </span>
        <span class="error_next_box"></span>    
    </div>
    <br><br><br>


    <!-- JOIN BTN-->
    <button type="button" id="btnJoin">
            <span>ID찾기</span>
        </button>
        <div class="btn_area" style="padding-top: 20px;">
            <button type="button" id="btnJoin">
                <span>회원가입</span>
            </button>
    </div>


</div> 
<!-- content-->

</div> 
<!-- wrapper -->
<script src="main.js"></script>

</body>
</html>