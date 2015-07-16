<?php
/**
 * Created on 2008/10/02
 * Author : Thao Tran
 * This page will read text from error.log and show on window.
 */
require_once(C_LIB_PATH.'constant.inc.php');

class Error_msg_log extends Controller {

	function __construct()
	{
            parent::Controller();
    }
	
	function index()
	{
		//require login
		/******************/
		$this->utils->requireLogin(true);
		/******************/
		// Get path logs
		$error_file = C_DB_PATH.C_ERROR_FILE;
		if (file_exists($error_file)){
		    header("Content-Type: text/plain; charset=shift-jis");
			header("Content-Disposition: inline; filename=$error_file");
			$errContent = file_get_contents($error_file);
			$arrData['err'] = $errContent;
			$this->load->view('error_msg_log', $arrData);
		}
	}
}
?>
