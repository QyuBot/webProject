<?php

    require $_SERVER["DOCUMENT_ROOT"]."/settings/settings.php";

    function getMySQLConnect(){
        global $MYSQL_ADDRESS, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD;
        $conn = mysqli_connect($MYSQL_ADDRESS, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD);

        if(mysqli_connect_error()){
            echo mysql_connect_error();
            return null;
        }
        return $conn;
    }

    function getDatabaseConnect(){
        global $MYSQL_ADDRESS, $MYSQL_DATABASE_NAME, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD;
        $conn = mysqli_connect($MYSQL_ADDRESS, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD, $MYSQL_DATABASE_NAME);
        if (mysqli_connect_error()){
            echo mysql_connect_error();
            return null;
        }
        return $conn;
    }

    // 연결 정보가 잘 입력되어 DB 접속이 잘 되는지 확인하는 함수
    function checkConnection($conn){
        if ($conn == null){
            echo "<br>".mysql_connect_error($conn)."<br>";
            return false;
        }
        else{
            return true;
        }
    }

    // 쿼리를 실행하고 정상적으로 실행됐는지 확인하는 함수
    function checkQuery($conn, $run){
        if ($run){
            return true;
        }
        else {
            echo "<br>".mysqli_error($conn)."<br>";
        }
    }

    // 데이터베이스가 있는지 없는지 확인하는 함수
    function checkDB(){
        global $MYSQL_DATABASE_NAME;
        $conn = getMySQLConnect();
        if ($conn != null) {
            $sql = "SHOW DATABASES LIKE '".$MYSQL_DATABASE_NAME."';";
            $result = mysqli_fetch_row(mysqli_query($conn, $sql));

            if ($result)
                return true;
            else
                return false;
        }

        return false;
    }

    // 테이블이 있는지 없는지 확인하는 함수
    function isTableExist($tableName){
        $conn = getDatabaseConnect();

        if ($conn != null) {
            $sql = "SHOW tables LIKE '".$tableName."';";
            $result = mysqli_fetch_row(mysqli_query($conn, $sql));
            if ($result)
                return true;
            else
                return false;
        }

        return false;
    }

    function getPDO() {
        global $MYSQL_ADDRESS, $MYSQL_DATABASE_NAME, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD;
        $port = "3306";

        $dbConnectionInfo = "mysql:host=".$MYSQL_ADDRESS.";port=".$port.";dbname=".$MYSQL_DATABASE_NAME.";charset=utf8";
        $dbConnection = new PDO($dbConnectionInfo, $MYSQL_USER_NAME, $MYSQL_USER_PASSWORD);
        $dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }


    // DB 초기화 SQL을 실행하는 함수
    function InitDatabase(){
        require $_SERVER["DOCUMENT_ROOT"]."/db/db_initSQL.php";
        $conn = getMySQLConnect();
        if (checkConnection($conn)){
            $run = mysqli_query($conn, $SQL_CREATE_DATABASE);
            if ($run) {

                $conn = getDatabaseConnect();

                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_PROJECTS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_PROJECTS_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_MILESTONES));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_MILESTONES_FK_PROJECTS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_ISSUES));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_ISSUES_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_ISSUES_FK_MILESTONES));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_ISSUES_FK_ISSUES));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_IMAGES));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_IMAGES_FE_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_REPORTS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_REPORTS_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_REPORTS_FK_PROJECTS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_COMMENTS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_COMMENTS_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_COMMENTS_FK_ISSUES));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_USER_PROJECT_JOIN));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_PROJECTS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_IMAGE_ISSUE_INCLUDE));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_IMAGE_ISSUE_INCLUDE_ISSUE_ID));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_IMAGE_ISSUE_INCLUDE_IMAGE_ID));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_IMAGE_REPORT_INCLUDE));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_IMAGE_REPORT_INCLUDE_REPORT_ID));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_IMAGE_REPORT_INCLUDE_IMAGE_ID));


                return "0";
            }
            return "-1";
        }
        return "-1";
    }

    // DB의 모든 정보를 지우는 함수
    function DropDatabase(){
        $conn = getMySQLConnect();
        if(checkConnection($conn)){
            $conn = getDatabaseConnect();
            checkQuery($conn, mysqli_query($conn, "DELETE FROM image_report_include"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM image_issue_include"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM projects"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM comments"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM reports"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM images"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM issues"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM milestones"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM projects"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM users"));
            return "0";

        }
        return "-1";
    }