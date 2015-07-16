<?php
/**
 * define constant values
 * Author: Thao Tran
 * Created on 2008/09/23
 **/
#####################################
/*don't modify from here*/
#####################################

// get info form config file　 
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
define("C_INVALID_USERID_PASSWORD", "ユーザIDまたはパスワードが違います。");
define("C_NOT_EXIST_IP", "権限がありません。");
//Account is not actived!
define("C_ACCOUNT_DISABLE", "アカウントはアクティブしていません。");
//Admin user can access this system!
define("C_ADMIN_MSG", "管理者ユーザはこのシステムにアクセスことができます。");
//Success!
define("C_LOGIN_SUCCESS", "成功しました。");
define("C_REQUIRED_STRING", "xxxが入力されていません。");
define("C_LIMIT_REQUIRED_CHAR", "xxxはlen文字以内で入力してください。");
define("C_USERID_REQUIRED", str_replace ('xxx', 'ユーザID', C_REQUIRED_STRING));
define("C_PASSWORD_REQUIRED", str_replace ('xxx', 'パスワード', C_REQUIRED_STRING));
//Connection successed.
define("C_CONNECT_SUCC", "接続に成功しました。");
//Database not found.
define("C_DB_NOTFOUND", "データベースはありません。");
//Invalid Password.
define("C_PASSWORD_ERROR", "パスワードは正しくない。");
//Connection error.
define("C_CONNECT_ERROR", "接続エラー。");
//Connection is not established.
define("C_CONNECT_ESTABLISHED", "接続は設定されていません。");
//download screen
define("C_DATA_DOWNLOAD", "CSVファイルの出力に成功し、ダウンロードの準備ができました。");
//Data is not available to download
define("C_NODATA_DOWNLOAD", "ダウンロードしたいデータはありません。");
define("C_LINK_DOWNLOAD", "ここを押してください。");
define("C_ERROR_UP", "番目のラインにエラーが発見されました。");
define("C_IMPORT_TABLE_START", "テーブルインンポート(xxx)");
define("C_IMPORT_TABLE_END", "テーブルインポートを終了しました。(成功件数: xxx; エラー件数: yyy)");
//Delete file is error.
define("C_DELETE_FILE_ERROR", "ファイル削除中にエラーが発生しました。");
define("C_FIELD_NOT_CORRESPONDING", "入力されたデータは入力されたフィエルド数に一致しません。");
//Please check xxx when upload file.
define("C_REQUIRED_CHECK_UPLOAD", "ファイルアップロードするとき、xxxをチェックしてください。");
//Please select a file.
define("C_SELECT_FILE", "ファイルを選択してください。");
define("C_CLEAR_TABLE", "Tableをクリアしますか。");
//config file
//The 'xxx' file is updated successful.
define("C_CONFIG_FILE_SUCC", "xxxファイルの更新に成功しました。");
//The 'xxx' file is updated unsuccessful.
define("C_CONFIG_FILE_ERROR", "xxxファイルの更新に失敗しました。");
//table listing
//Please choose one or many table to replace
define("CHOOSE_TABLE_REQUIRED", "置き換えるために、１つまたは複数のテープルを選択してください。");
//Please input the text to find
define("FIND_TEXT_REQUIRED",    "文字列を入力して検索してください。");
//Are you sure you want to replace string with empty?
define("REPLACE_TEXT_RECOMMEND",    "空文字列で置き換えりたいですか。");
//Cannot find the string \'xxx\' in any table
define("CANNOT_FIND_TABLE", "\'xxx\' 列は全てのテープルにありません。");
//The \'xxx\' table is updated successful.
define("UPDATE_SUCC", "\'xxx\' テープルの更新に成功しました。");
//The \'xxx\' table is updated unsuccessful.
define("UPDATE_ERROR", "\'xxx\' テープルの更新に失敗しました。");
//Please check records to delete.
define("SELECT_RECORD_TO_DELETE", "Please check records to delete.");
//Do you want to delete?
define("DELETE_DATA_CHECKED", "レコードを削除してよろしいですか。");
//Please input xxx
define("DATA_REQUIRED", "xxxを入力してください。");
//Is the record overwrited?
define("OVERWRITE_DATA", "レコードを上書きしますか。");
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
define("DM00_NAME", "工程管理システム - DM");//Login
define("DM10_NAME", "メインメニュー画面");//Main memu
define("DM20_NAME", "テーブルアップロード／ダウンロード");//Upload/download
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