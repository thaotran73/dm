<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Upload - Download tables screen
 */
class Up_down extends Controller
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
		$current_db = null;
		//get connection
		$isConnection = $this->dmdb->openConn($current_db);
		//check connection of database existed?
		/******************/
		$this->utils->checkConnection($isConnection);
		/******************/
		// Load up_down_db object
		$this->load->model("up_down_db");
		$oUpDown = new Up_down_db();
		// Get POST values
		$page = $this->input->post('page');
		$SkipLine = $this->input->post('SkipLine');
		$OptType = $this->input->post('OptType');
		$delimiter = $this->input->post('delimiter');
		if ($delimiter==1)
		{
			$delimiter = '","';
		}
		else
		{
			$delimiter = ',';
		}
		$table_name = $this->input->post('table_name');
		$arrReturn = array();
		$act = $this->input->post('act');
		$arrTable = array();
		//check connection existed?
		if ($isConnection)
		{
			//init list of tables
			$allTable = $this->dmdb->loadTableListing($current_db);
			$totalTables = count($allTable);
			//get list of tables
			$arrTable = $this->dmdb->getData4BreakPage($allTable, $page, DM20_RECORD_PER_PAGE);
			//set Prev Next Page for view
			$pagination = $this->utils->getPaginationString($page, $totalTables, DM20_RECORD_PER_PAGE);
			
			//init Number of rows used for Field name
			$arrSkipLine = $this->utils->initArrayDataInt(0, 3);
			$SkipLine = !$this->utils->isNull($SkipLine)?$SkipLine:0;
			
			// Upload processing
			if ($act=="upload")
			{
				//Get mode import and number of line to skip
				$oCondition = new stdClass();
				$oCondition->OptType  = (isset($OptType) ? $OptType : 1);
				$oCondition->SkipLine = (isset($SkipLine)? $SkipLine : 0);
				// Confirm files upload with check is choosed
				$arrImport = array();
				if (!is_null($arrTable) && is_array($arrTable))
		    	{
			    	for ($i=0; $i<count($arrTable); $i++)
			    	{
			    		$oData = $arrTable[$i];
			    		if (!is_null($oData))
			    		{
			    			$value = $oData;
			    			if ($this->input->post('chk_'.$value))
							{
								$arrImport = $this->utils->addArray($arrImport, 'file_' . $value, $value);	
							}
			    		}
			    	}
		    	}		
				// Get path error file to write log
				$error_file = C_DB_PATH.C_ERROR_FILE;
				if(file_exists($error_file))
				{ 
					unlink($error_file);	
				}
				
				// Process upload and import data from files (*.csv) to tables
				$arrReturn = array();
				foreach ($arrImport as $idx => $val) 
				{
					if ($_FILES[$idx]['size']!=0)
					{
						$strPath = $oUpDown->uploadFile(C_IMPORT_FOLDER, $_FILES[$idx]);
						/** Check again if file is upload success */
						if (file_exists($strPath) && is_file($strPath))
						{
							$arrReturn = $oUpDown->importCSV($current_db, $arrReturn, $val, $strPath, $oCondition, $error_file, $delimiter);
						}
					}
				}
			}
			//clear data of a table
			else if ($act=="clear_table")
			{
				// Clear processing
				if (!$this->utils->isNull($table_name))
				{
					/** Delete data from $table_name table */
					$oUpDown->clearTable($current_db, $table_name);
				}
			}
			//export data of a table into csv file
			else if ($act=="export")
			{
				//Export processing
				$oResult = new stdClass();
				if (!$this->utils->isNull($table_name))
				{
					$arrData = array();
					// Process export to file with seperator between fiels
					$sResult = $oUpDown->exportCSV($current_db, $table_name, $table_name, $delimiter);
					if (!$this->utils->isNull($sResult))
					{
						$oResult->msg = $sResult;
					}
					$oResult->title = $table_name;
					
					// Request to export view to get real files
					$data['result'] = $oResult;
					$data['ses_Data'] = $_POST;
					$data['prepage'] = DM20_ROUTE;
					$this->dmdb->closeConn($current_db);
					$this->load->view('export', $data);
					exit;
				}
			}
		}
		// set data for view page
		$arrData['arrTable'] = $arrTable;
		$arrData['arrSkipLine'] = $arrSkipLine;
		$arrData['SkipLine'] = $SkipLine;
		$arrData['arrReturn'] = $arrReturn;
		$arrData['page'] = $page;
		$arrData['pagination'] = $pagination;
		$this->dmdb->closeConn($current_db);
		$this->load->view('up_down', $arrData);
	}
}
?>