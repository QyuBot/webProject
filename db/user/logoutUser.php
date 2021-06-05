<?php
session_start();
session_unset();
echo "<script type='text/javascript'>alert('로그아웃 되었습니다.');</script>";
echo "<script type='text/javascript'>window.location.href='/';</script>";