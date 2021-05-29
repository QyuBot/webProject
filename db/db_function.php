<?php

    require $_SERVER["DOCUMENT_ROOT"]."/settings/db_info.php";

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

    function checkConnection($conn){
        if ($conn == null){
            echo "<br>".mysql_connect_error($conn)."<br>";
            return false;
        }
        else{
            return true;
        }
    }

    function checkQuery($conn, $run){
        if ($run){
            return true;
        }
        else {
            echo "<br>".mysqli_error($conn)."<br>";
        }
    }

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

    function checkTable($tableName){
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
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_REPORTS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_REPORTS_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_REPORTS_FK_PROJECTS));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_COMMENTS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_COMMENTS_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_COMMENTS_FK_ISSUES));
                checkQuery($conn, mysqli_query($conn, $SQL_CREATE_TABLE_USER_PROJECT_JOIN));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_USERS));
                checkQuery($conn, mysqli_query($conn, $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_PROJECTS));

                return "0";
            }
            return "-1";
        }
        return "-1";
    }

    function DropDatabase(){
        $conn = getMySQLConnect();
        if(checkConnection($conn)){
            $conn = getDatabaseConnect();
            checkQuery($conn, mysqli_query($conn, "DELETE FROM projects"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM comments"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM reports"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM issues"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM milestones"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM projects"));
            checkQuery($conn, mysqli_query($conn, "DELETE FROM users"));
            return "0";

        }
        return "-1";
    }


?>

