<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Main - Menu screen
 */
class Main extends Controller
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
		// Load main db
		$this->load->model('main_db');
		//get database listing
		$arrDB = $this->main_db->getDatabaseListing();
		// Get POST values
		$cbDatabase = $this->input->post('cbDatabase');
		$db_username = $this->input->post('db_username');
		$db_password = $this->input->post('db_password');
		$db_name = $this->input->post('db_name');
		//1:display information db; 2: check connection
		$hidAction = $this->input->post('hidAction');
		//init status value
		$status = '';
		//get db information
		if ($hidAction == "1")
		{
			//clear config file session
			$this->utils->clearConfigSession();
			//get database info
			$oDBInfo = $this->dmdb->getDatabaseInfo($cbDatabase);
			$db_name = $oDBInfo->db_name;
			$db_password = '';
			$db_username = $oDBInfo->db_owner;
		}
		// check connection db
		else if ($hidAction == "2") 
		{
			$oCon = new stdClass();
			$oCon->db_username = $db_username;
			$oCon->db_password = $db_password;
			$oCon->db_name = $db_name;
			if ($this->utils->isNull($db_password))
			{
				//clear session db info
				$this->utils->clearConfigSession();
				$status = C_PASSWORD_ERROR;
			} 
			else if ($this->utils->isNull($db_name))
			{
				//clear session db info
				$this->utils->clearConfigSession();
				$status = C_DB_NOTFOUND;
			}
			else
			{
				$connectDB = $this->dmdb->loadDatabase($oCon);
				$bConnect = $this->dmdb->checkConn($connectDB);
				if ($bConnect)
				{
					$status = C_CONNECT_SUCC;
					//set session db info
					$this->utils->setGeneralSession('db_name', $db_name);
					$this->utils->setGeneralSession('db_username', $db_username);
					$this->utils->setGeneralSession('db_password', $db_password);
					$this->utils->setGeneralSession('status', $status);
				}
				else
				{
					//clear session db info
					$this->utils->clearConfigSession();
					$status = C_CONNECT_ERROR;	
				}
				$this->dmdb->closeConn($connectDB);
			}
		}
		// clear info
		else if ($hidAction == "3") 
		{
			//clear config file session
			$this->utils->clearConfigSession();
			$cbDatabase = '';
			$db_username = '';
			$db_password = '';
			$db_name = '';
		}
		// set data for view page
		$data['cbDatabase'] = !$this->utils->isNull($db_name)?$db_name:$this->session->userdata('db_name');
		$data['db_username'] = !$this->utils->isNull($db_username)?$db_username:$this->session->userdata('db_username');
		$data['db_password'] = !$this->utils->isNull($db_password) ?$db_password:$this->session->userdata('db_password');
		$data['status'] = !$this->utils->isNull($status)?$status:$this->session->userdata('status');
		$data['arrDB'] = $arrDB;
		$this->load->view('main', $data);
	}
}
?>