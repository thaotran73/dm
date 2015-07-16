<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Config file screen
 */
class Config_file extends Controller
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
		$config_file = C_DB_PATH.C_DM_CONFIG_FILE;
		$result = '';
		//get content of config file if the first time
		if (!$act)
		{
			$content = file_get_contents($config_file);
		}
		else
		{
			//write content into config file
			if (file_put_contents($config_file, $content , false))
			{
				$result = str_replace("xxx", DM41_NAME, C_CONFIG_FILE_SUCC);
			}
			else
			{
				$result = str_replace("xxx", DM41_NAME, C_CONFIG_FILE_ERROR);
			}
			//clear session db info
			$this->utils->clearConfigSession();
		}
		$arrData['content'] = $content;
		$arrData['result'] = $result;
		$arrData['route'] = DM41_ROUTE;
		$arrData['screen_id'] = DM41_ID;
		$arrData['screen_name'] = DM41_NAME;
		$this->load->view('config_file', $arrData);
	}
}
?>