<?php
/**
 * define constant values
 * Author: Thao Tran
 * Created on 2008/09/23
 **/
#####################################
/*don't modify from here*/
#####################################

// get info form config file�@ 
define("C_LIB_PATH", APPPATH . "libraries/");
define("C_DB_PATH", APPPATH . "db/");
define("C_DM_CONFIG_FILE", "dm.conf");
define("C_ACCESS_FILE", "access.log");
define("C_ERROR_FILE",  "error.log");
require_once(C_LIB_PATH . 'getconfig.inc.php');

define('DM_USER', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "dm_user")));
define('DM_PASS', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "dm_pass")));
define('DB_HOST', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "db_host")));
define('PORT', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "port")));
define('DB_USERNAME', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "db_username")));
//define('DB_DRIVER', trim(getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "db_driver")));
define('DB_DRIVER', 'postgre');
define('WEB_HOST', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "web_host"));
define('FILE_SIZE', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "File_Size"));
define('BASE_PATH', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "base_path"));
define('RECODE_PER_PAGE', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "Num_Record_Per_Page"));
define('IP_MASK', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "ip_mask"));
define('SEPERATOR', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "seperator"));
define('SEPERATOR_REPLACE', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "seperator_replace"));
define('BOTTOM_CONTENT', getConfigFile(C_DB_PATH.C_DM_CONFIG_FILE, "bottom_content"));
define('DB_LIST', 'db_list');

#####################################
//define path of files
define("C_CSS_FILE_PATH", "system/application/views/css/dm.css");
define("C_JS_PATH", "system/application/views/js/");
define("C_IMG_PATH", "system/application/views/images/");

define("C_REMOTE_ADDR", $_SERVER["REMOTE_ADDR"]);

define("C_IMPORT_FOLDER",  BASE_PATH. "application/import/");
define("C_EXPORT_FOLDER",  BASE_PATH. "application/export/");

define("SCHEMA", "public");

#####################################
// error message
define("C_INVALID_USERID_PASSWORD", "���[�UID�܂��̓p�X���[�h���Ⴂ�܂��B");
define("C_NOT_EXIST_IP", "����������܂���B");
//Account is not actived!
define("C_ACCOUNT_DISABLE", "�A�J�E���g�̓A�N�e�B�u���Ă��܂���B");
//Admin user can access this system!
define("C_ADMIN_MSG", "�Ǘ��҃��[�U�͂��̃V�X�e���ɃA�N�Z�X���Ƃ��ł��܂��B");
//Success!
define("C_LOGIN_SUCCESS", "�������܂����B");
define("C_REQUIRED_STRING", "xxx�����͂���Ă��܂���B");
define("C_LIMIT_REQUIRED_CHAR", "xxx��len�����ȓ��œ��͂��Ă��������B");
define("C_USERID_REQUIRED", str_replace ('xxx', '���[�UID', C_REQUIRED_STRING));
define("C_PASSWORD_REQUIRED", str_replace ('xxx', '�p�X���[�h', C_REQUIRED_STRING));
//Connection successed.
define("C_CONNECT_SUCC", "�ڑ��ɐ������܂����B");
//Database not found.
define("C_DB_NOTFOUND", "�f�[�^�x�[�X�͂���܂���B");
//Invalid Password.
define("C_PASSWORD_ERROR", "�p�X���[�h�͐������Ȃ��B");
//Connection error.
define("C_CONNECT_ERROR", "�ڑ��G���[�B");
//Connection is not established.
define("C_CONNECT_ESTABLISHED", "�ڑ��͐ݒ肳��Ă��܂���B");
//download screen
define("C_DATA_DOWNLOAD", "CSV�t�@�C���̏o�͂ɐ������A�_�E�����[�h�̏������ł��܂����B");
//Data is not available to download
define("C_NODATA_DOWNLOAD", "�_�E�����[�h�������f�[�^�͂���܂���B");
define("C_LINK_DOWNLOAD", "�����������Ă��������B");
define("C_ERROR_UP", "�Ԗڂ̃��C���ɃG���[����������܂����B");
define("C_IMPORT_TABLE_START", "�e�[�u���C�����|�[�g(xxx)");
define("C_IMPORT_TABLE_END", "�e�[�u���C���|�[�g���I�����܂����B(��������: xxx; �G���[����: yyy)");
//Delete file is error.
define("C_DELETE_FILE_ERROR", "�t�@�C���폜���ɃG���[���������܂����B");
define("C_FIELD_NOT_CORRESPONDING", "���͂��ꂽ�f�[�^�͓��͂��ꂽ�t�B�G���h���Ɉ�v���܂���B");
//Please check xxx when upload file.
define("C_REQUIRED_CHECK_UPLOAD", "�t�@�C���A�b�v���[�h����Ƃ��Axxx���`�F�b�N���Ă��������B");
//Please select a file.
define("C_SELECT_FILE", "�t�@�C����I�����Ă��������B");
define("C_CLEAR_TABLE", "Table���N���A���܂����B");
//config file
//The 'xxx' file is updated successful.
define("C_CONFIG_FILE_SUCC", "xxx�t�@�C���̍X�V�ɐ������܂����B");
//The 'xxx' file is updated unsuccessful.
define("C_CONFIG_FILE_ERROR", "xxx�t�@�C���̍X�V�Ɏ��s���܂����B");
//table listing
//Please choose one or many table to replace
define("CHOOSE_TABLE_REQUIRED", "�u�������邽�߂ɁA�P�܂��͕����̃e�[�v����I�����Ă��������B");
//Please input the text to find
define("FIND_TEXT_REQUIRED",    "���������͂��Č������Ă��������B");
//Are you sure you want to replace string with empty?
define("REPLACE_TEXT_RECOMMEND",    "�󕶎���Œu�������肽���ł����B");
//Cannot find the string \'xxx\' in any table
define("CANNOT_FIND_TABLE", "\'xxx\' ��͑S�Ẵe�[�v���ɂ���܂���B");
//The \'xxx\' table is updated successful.
define("UPDATE_SUCC", "\'xxx\' �e�[�v���̍X�V�ɐ������܂����B");
//The \'xxx\' table is updated unsuccessful.
define("UPDATE_ERROR", "\'xxx\' �e�[�v���̍X�V�Ɏ��s���܂����B");
//Please check records to delete.
define("SELECT_RECORD_TO_DELETE", "Please check records to delete.");
//Do you want to delete?
define("DELETE_DATA_CHECKED", "���R�[�h���폜���Ă�낵���ł����B");
//Please input xxx
define("DATA_REQUIRED", "xxx����͂��Ă��������B");
//Is the record overwrited?
define("OVERWRITE_DATA", "���R�[�h���㏑�����܂����B");
//Insert data is incorrect. Please try again.
define("INCORRECT_INPUT_DATA", "Insert data is incorrect. Please try again.");

#####################################
// define name for controller
define("C_MSG_LIST_ROUTE", "message_listing");//message_listing
define("DM00_ROUTE", "login");//Login
define("DM10_ROUTE", "main");//Main memu
define("DM20_ROUTE", "up_down");//Upload/download
define("DM30_ROUTE", "table_listing");//Table Listing 
define("DM31_ROUTE", "table_info");//Table View
define("DM41_ROUTE", "config_file");//Config file
define("DM42_ROUTE", "access_log");//Access log

#####################################
// define constants
define("C_ENCODING", "shift-jis");
#####################################

// define screen id
define("DM00_ID", "DM00");//Login
define("DM10_ID", "DM10");//Main memu
define("DM20_ID", "DM20");//Upload/download
define("DM30_ID", "DM30");//Table Listing 
define("DM31_ID", "DM31");//Table View
define("DM41_ID", "DM41");//Config file
define("DM42_ID", "DM42");//Access log

#####################################
// define screen name
define("DM00_NAME", "�H���Ǘ��V�X�e�� - DM");//Login
define("DM10_NAME", "���C�����j���[���");//Main memu
define("DM20_NAME", "�e�[�u���A�b�v���[�h�^�_�E�����[�h");//Upload/download
define("DM30_NAME", "Table Listing");//Table Listing 
define("DM31_NAME", "Table View");//Table View
define("DM41_NAME", "Config file");//Config file
define("DM42_NAME", "Access log");//Access log

#####################################
// define database info
define('C_TB_USERS', 'users');
define('C_ADMIN', 'admin');

define('DEFAULT_RECORD_PER_PAGE', 10);
define('DM30_RECORD_PER_PAGE', 50);
define('DM31_RECORD_PER_PAGE', 100);
define('DM20_RECORD_PER_PAGE', 50);
?>