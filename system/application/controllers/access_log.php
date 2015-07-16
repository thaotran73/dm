<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Access log screen
 */
class Access_log extends Controller
{
	/**
	 * Controller constructor
	 * 2008/08/18
	 **/
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
		//get data from POST values
		$content = $this->input->post('content');
		$act = $this->input->post('act');
		$access_file = C_DB_PATH.C_ACCESS_FILE;
		$result = '';
		//get content of config file if the first time
		if (!$act)
		{
			$content = file_get_contents($access_file);
		}
		else
		{
			//write content into access log file
			if (file_put_contents($access_file, $content , false))
			{
				$result = str_replace("xxx", DM42_NAME, C_CONFIG_FILE_SUCC);
			}
			else
			{
				$result = str_replace("xxx", DM42_NAME, C_CONFIG_FILE_ERROR);
			}
		}
		$arrData['content'] = $content;
		$arrData['result'] = $result;
		$arrData['route'] = DM42_ROUTE;
		$arrData['screen_id'] = DM42_ID;
		$arrData['screen_name'] = DM42_NAME;
		$this->load->view('config_file', $arrData);
	}
}
?>