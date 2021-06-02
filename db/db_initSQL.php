<?php

    // 이 파일은 ~/db/db_function.php 에서 사용합니다.
    require $_SERVER["DOCUMENT_ROOT"]."/settings/db_info.php";

    // DB 생성 SQL
    $SQL_CREATE_DATABASE = "CREATE DATABASE IF NOT EXISTS " . $MYSQL_DATABASE_NAME . ";";

    // 유저 테이블 생성 SQL
    $SQL_CREATE_TABLE_USERS = "CREATE TABLE users
        (
            `user_id`            BIGINT         NOT NULL    AUTO_INCREMENT COMMENT '유저 ID', 
            `user_login_id`      VARCHAR(64)    NOT NULL    COMMENT '유저 로그인 ID', 
            `user_password`      VARCHAR(64)    NOT NULL    COMMENT '유저 비밀번호', 
            `user_nickname`      VARCHAR(45)    NOT NULL    COMMENT '유저 닉네임', 
            `user_email`         VARCHAR(64)    NOT NULL    COMMENT '유저 이메일', 
            `user_email_domain`  VARCHAR(32)    NOT NULL    COMMENT '유저 이메일 도메인', 
            `user_tutorialflag`  TINYINT(1)     NOT NULL    COMMENT '유저 튜토리얼을봤는가', 
            CONSTRAINT  PRIMARY KEY (user_id, user_login_id)
        );";

    // 프로젝트 테이블 생성 SQL
    $SQL_CREATE_TABLE_PROJECTS = "CREATE TABLE projects
        (
            `project_id`        BIGINT         NOT NULL    AUTO_INCREMENT COMMENT '프로젝트 ID', 
            `project_admin_id`  BIGINT         NOT NULL    COMMENT '프로젝트 관리자 ID', 
            `project_name`      VARCHAR(45)    NOT NULL    COMMENT '프로젝트 이름', 
            CONSTRAINT  PRIMARY KEY (project_id)
        );";

    $SQL_ALTER_TABLE_PROJECTS_FK_USERS = "ALTER TABLE projects
    ADD CONSTRAINT FK_projects_project_admin_id_users_user_id FOREIGN KEY (project_admin_id)
        REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 마일스톤 테이블 생성 SQL
    $SQL_CREATE_TABLE_MILESTONES = "CREATE TABLE milestones
        (
            `milestone_id`                    BIGINT         NOT NULL    AUTO_INCREMENT COMMENT '마일스톤 ID', 
            `milestone_name`                  VARCHAR(45)    NOT NULL    COMMENT '마일스톤 이름', 
            `milestone_inclusion_project_id`  BIGINT         NOT NULL    COMMENT '마일스톤 포함인 프로젝트 ID', 
            CONSTRAINT  PRIMARY KEY (milestone_id, milestone_name)
        );";

    $SQL_ALTER_TABLE_MILESTONES_FK_PROJECTS = "ALTER TABLE milestones
    ADD CONSTRAINT FK_milestones_milestone_inclusion_project_id_projects_project_id FOREIGN KEY (milestone_inclusion_project_id)
        REFERENCES projects (project_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 이슈 테이블 생성 SQL
    $SQL_CREATE_TABLE_ISSUES = "CREATE TABLE issues
        (
            `issue_id`                      BIGINT         NOT NULL    AUTO_INCREMENT COMMENT '이슈 ID', 
            `issue_title`                   VARCHAR(64)    NOT NULL    COMMENT '이슈 제목', 
            `issue_creator_id`              BIGINT         NOT NULL    COMMENT '이슈 작성자 ID', 
            `issue_priority`                TINYINT        NOT NULL    COMMENT '이슈 우선순위', 
            `issue_article`                 BLOB           NOT NULL    COMMENT '이슈 본문', 
            `issue_create_time`             TIMESTAMP      NOT NULL    COMMENT '이슈 작성 시간', 
            `issue_status`                  TINYINT        NOT NULL    COMMENT '이슈 상태', 
            `issue_inclusion_milestone_id`  BIGINT         NULL        COMMENT '이슈 포함인 마일스톤 ID', 
            `issue_inclusion_project_id`    BIGINT         NOT NULL    COMMENT '이슈 포함인 프로젝트 ID', 
            CONSTRAINT  PRIMARY KEY (issue_id, issue_title)
        );";

    $SQL_ALTER_TABLE_ISSUES_FK_USERS = "ALTER TABLE issues
    ADD CONSTRAINT FK_issues_issue_creator_id_users_user_id FOREIGN KEY (issue_creator_id)
        REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_ISSUES_FK_MILESTONES = "ALTER TABLE issues
    ADD CONSTRAINT FK_issues_issue_inclusion_milestone_id_milestones_milestone_id FOREIGN KEY (issue_inclusion_milestone_id)
        REFERENCES milestones (milestone_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_ISSUES_FK_ISSUES = "ALTER TABLE issues
        ADD CONSTRAINT FK_issues_issue_inclusion_project_id_projects_project_id FOREIGN KEY (issue_inclusion_project_id)
            REFERENCES projects (project_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 이미지 테이블 생성 SQL
    $SQL_CREATE_TABLE_IMAGES = "CREATE TABLE images
        (
            `image_id`                 BIGINT          NOT NULL    AUTO_INCREMENT COMMENT '이미지 ID', 
            `image_original_filename`  VARCHAR(256)    NOT NULL    COMMENT '이미지 원본 파일명', 
            `image_save_filename`      VARCHAR(256)    NOT NULL    COMMENT '이미지 저장 파일명', 
            `image_type`               VARCHAR(45)     NOT NULL    COMMENT '이미지 타입', 
            `image_size`               INT             NOT NULL    COMMENT '이미지 크기', 
            `upload_time`              TIMESTAMP       NOT NULL    COMMENT '업로드 시간', 
            `upload_user_id`           BIGINT          NOT NULL    COMMENT '업로드 유저 ID', 
            CONSTRAINT  PRIMARY KEY (image_id)
        );";

    $SQL_ALTER_TABLE_IMAGES_FE_USERS = "ALTER TABLE images
        ADD CONSTRAINT FK_images_upload_user_id_users_user_id FOREIGN KEY (upload_user_id)
            REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 보고서 테이블 생성 SQL
    $SQL_CREATE_TABLE_REPORTS = "CREATE TABLE reports
        (
            `report_id`                    BIGINT         NOT NULL    AUTO_INCREMENT COMMENT '보고서 ID', 
            `report_title`                 VARCHAR(64)    NOT NULL    COMMENT '보고서 제목', 
            `report_creator_id`            BIGINT         NOT NULL    COMMENT '보고서 작성자 ID', 
            `report_article`               BLOB           NOT NULL    COMMENT '보고서 본문', 
            `report_create_time`           TIMESTAMP      NOT NULL    COMMENT '보고서 작성 시간', 
            `report_inclusion_project_id`  BIGINT         NOT NULL    COMMENT '보고서 포함인 프로젝트 ID', 
            CONSTRAINT  PRIMARY KEY (report_id, report_title)
        );";

    $SQL_ALTER_TABLE_REPORTS_FK_USERS = "ALTER TABLE reports
        ADD CONSTRAINT FK_reports_report_creator_id_users_user_id FOREIGN KEY (report_creator_id)
            REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_REPORTS_FK_PROJECTS = "ALTER TABLE reports
        ADD CONSTRAINT FK_reports_report_inclusion_project_id_projects_project_id FOREIGN KEY (report_inclusion_project_id)
            REFERENCES projects (project_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 댓글 테이블 생성 SQL
    $SQL_CREATE_TABLE_COMMENTS = "CREATE TABLE comments
        (
            `comment_id`                  BIGINT          NOT NULL    AUTO_INCREMENT COMMENT '댓글 ID', 
            `comment_creator_id`          BIGINT          NOT NULL    COMMENT '댓글 작성자 ID', 
            `comment_content`             VARCHAR(512)    NOT NULL    COMMENT '댓글 내용', 
            `comment_create_time`         TIMESTAMP       NOT NULL    COMMENT '댓글 작성 시간', 
            `comment_inclusion_issue_id`  BIGINT          NOT NULL    COMMENT '댓글 포함인 이슈 ID', 
            CONSTRAINT  PRIMARY KEY (comment_id)
        );";

    $SQL_ALTER_TABLE_COMMENTS_FK_USERS = "ALTER TABLE comments
        ADD CONSTRAINT FK_comments_comment_creator_id_users_user_id FOREIGN KEY (comment_creator_id)
            REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_COMMENTS_FK_ISSUES = "ALTER TABLE comments
        ADD CONSTRAINT FK_comments_comment_inclusion_issue_id_issues_issue_id FOREIGN KEY (comment_inclusion_issue_id)
            REFERENCES issues (issue_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 유저 프로젝트 소속 관계 테이블 생성 SQL
    $SQL_CREATE_TABLE_USER_PROJECT_JOIN = "CREATE TABLE user_project_join
        (
            `user_id`     BIGINT    NOT NULL    COMMENT '유저 ID', 
            `project_id`  BIGINT    NOT NULL    COMMENT '프로젝트 ID'
        );";

    $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_USERS = "ALTER TABLE user_project_join
        ADD CONSTRAINT FK_user_project_join_user_id_users_user_id FOREIGN KEY (user_id)
            REFERENCES users (user_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_USER_PROJECT_JOIN_FK_PROJECTS = "ALTER TABLE user_project_join
        ADD CONSTRAINT FK_user_project_join_project_id_projects_project_id FOREIGN KEY (project_id)
            REFERENCES projects (project_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";


    // 이미지 테이블 관계 테이블 생성 및 외래 키 설정 SQL
    // 이슈 <-> 이미지
    $SQL_CREATE_TABLE_IMAGE_ISSUE_INCLUDE = "CREATE TABLE image_issue_include
        (
            `issue_id`  BIGINT    NOT NULL    COMMENT '이슈 ID', 
            `image_id`  BIGINT    NOT NULL    COMMENT '이미지 ID'
        );";

    $SQL_ALTER_TABLE_IMAGE_ISSUE_INCLUDE_ISSUE_ID = "ALTER TABLE image_issue_include
        ADD CONSTRAINT FK_image_issue_include_issue_id_issues_issue_id FOREIGN KEY (issue_id)
            REFERENCES issues (issue_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_IMAGE_ISSUE_INCLUDE_IMAGE_ID = "ALTER TABLE image_issue_include
        ADD CONSTRAINT FK_image_issue_include_image_id_images_image_id FOREIGN KEY (image_id)
            REFERENCES images (image_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    // 보고서 <-> 이미지
    $SQL_CREATE_TABLE_IMAGE_REPORT_INCLUDE = "CREATE TABLE image_report_include
        (
            `report_id`  BIGINT    NOT NULL    COMMENT '보고서 ID', 
            `image_id`   BIGINT    NOT NULL    COMMENT '이미지 ID'
        );";

    $SQL_ALTER_TABLE_IMAGE_REPORT_INCLUDE_REPORT_ID = "ALTER TABLE image_report_include
            ADD CONSTRAINT FK_image_report_include_image_id_reports_report_id FOREIGN KEY (image_id)
                REFERENCES reports (report_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";

    $SQL_ALTER_TABLE_IMAGE_REPORT_INCLUDE_IMAGE_ID = "ALTER TABLE image_report_include
        ADD CONSTRAINT FK_image_report_include_report_id_images_image_id FOREIGN KEY (report_id)
            REFERENCES images (image_id) ON DELETE RESTRICT ON UPDATE RESTRICT;";


