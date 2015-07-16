<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Login screen
 */
class Login extends Controller
{
	/**
	 * Controller constructor
	 * 2008/09/25
	 **/
	function __construct()
	{
		parent::Controller();
	}

	function index()
	{
		// Get POST values
		$hdLogout = $this->input->post('hdLogout');
		if (!$this->utils->isNull($hdLogout))
		{
			// Clear current session
			$this->utils->clearSession();
		}
		
		// Get submit button
		$btnRegister = $this->input->post('btnRegister');
		if (!$this->utils->isNull($btnRegister))
		{
			
			$s_user_id = $this->input->post('s_user_id');
			$s_password = $this->input->post('s_password');
			
			// Load login db
			$this->load->model('login_db');
			// Check IP Address first
			// Get ip_mask
			$ip_mask = IP_MASK;
			// Check ip_mask; return true if ok; else
			$is_exist = $this->login_db->checkIPAdress($ip_mask);
			// IP address is not listed in dm.conf|ip_mask, the login user?fs type becomes ?gAnonymous?h
			if(!$is_exist)
			{
				// Write to access log
				$this->utils->logger(C_ACCESS_FILE, C_REMOTE_ADDR . ", " . date("Y-m-d H:i:s") . ", " . $s_user_id . ", " . $s_password .", " . C_NOT_EXIST_IP);
				
				// Go back to login again
				$data['s_user_id'] = $s_user_id;
				$data['err_message'] = C_NOT_EXIST_IP . '';
				$this->load->view("login", $data);
				return;
			}
			
			// Check : active, permission, valid account		
			$cond = new stdClass();
			$cond->s_user_id = $s_user_id;
			$cond->s_password = $s_password;
			
			$bCheckLogin = $this->login_db->checkUserLogin($cond);
			
			if ($bCheckLogin)
			{
				// Write to access log
				$this->utils->logger(C_ACCESS_FILE, C_REMOTE_ADDR . ", " . date("Y-m-d H:i:s") . ", " . $s_user_id . ", " . $s_password .", " . C_LOGIN_SUCCESS);
				
				// Set session
				$clientTime = $this->input->post('client_time');
				$cond->s_user_id = $s_user_id;
				$cond->client_time = $clientTime;
				$this->utils->setSessionLogin($cond);
				// Go to DM10 - Main - Menu	
				redirect(DM10_ROUTE, "Location");
				return;
			}
			else
			{
				// Write to access log
				$this->utils->logger(C_ACCESS_FILE, C_REMOTE_ADDR . ", " . date("Y-m-d H:i:s") . ", " . $s_user_id . ", " . $s_password .", " . C_INVALID_USERID_PASSWORD);
				
				// Go back to login again
				$data['userID'] = $s_user_id;
				$data['err_message'] = C_INVALID_USERID_PASSWORD . '';
				$this->load->view("login", $data);
				return;
			}
		}
		else
		{
	        $data = null;
			$this->load->view("login", $data);
		}
	}
}
?>