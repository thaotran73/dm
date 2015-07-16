<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * List of tables screen
 */
class Table_listing extends Controller
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
		$tableName = $this->input->post('tableName');
		$page = $this->input->post('page');
		$page = $page?$page:1;
		$hdAction = $this->input->post('hdAction');
		$txtFindText = $this->input->post('txtFindText');
		$txtNewText = $this->input->post('txtNewText');
		$replaceTable = $this->input->post('replaceTable');
		$arrTable = array();
		$arrSearchResult = array();
		$resultReplace = array();
		$pagination = '';
		//check connection existed?
		if ($isConnection)
		{
			/*load data for left menu START*/
			//number of row
			$allTable = $this->dmdb->loadTableListing($current_db);
			$totalTables = count($allTable);
			//get list of tables
			$arrTable = $this->dmdb->getData4BreakPage($allTable, $page, DM30_RECORD_PER_PAGE);
			//set Prev Next Page for view
			$pagination = $this->utils->getPaginationString($page, $totalTables, DM30_RECORD_PER_PAGE);
			/*load data for left menu END*/
			/*load data for content START*/
			// Load table listing object
			$this->load->model("table_listing_db");
			$oTableListingDB = new Table_listing_db();
			if ($hdAction == 'find')
			{
				for($i=0; $i<$totalTables; $i++)
				{
					//search on each table in list of tables
					$oItem = $allTable[$i];
					if ($oItem)
					{
						$tableName = $oItem;
						if($oTableListingDB->find($current_db, $tableName, $txtFindText))
						{
							$arrSearchResult[] = $tableName;
						}	
					}
				}
			}	
			else if ($hdAction == 'replace')
			{
				// WRITE LOG
				$s_user_id = $this->session->userdata('s_user_id')?$this->session->userdata('s_user_id'):'Anonymous';
				$message  = "Date    : ".date('Y-m-d H:i:s')."\n";
				$message .= "User ID : ".$s_user_id."\n";
				$message .= "Action  : Replace"."\n";
				$message .= "Find    : ".$txtFindText."\n";
				$message .= "Replace : ".$txtNewText."\n";
				if ($totalTables > 0 && !is_null($allTable) && is_array($allTable))
		    	{
			    	for ($i=0; $i<$totalTables; $i++)
			    	{
			    		$oData = $allTable[$i];
			    		$replace = new stdClass();
			    		if (!is_null($oData))
			    		{
			    			$tableName = $oData;
			    			if ($this->input->post('chkTable_'.$tableName))
							{
								//find and replace data 
								if($oTableListingDB->replace($current_db, $tableName, $txtFindText, $txtNewText))
								{
									$replace->result = TRUE;
								}
								else
								{
									$replace->result = FALSE;
								}
								$replace->table  = $tableName;
								$resultReplace[] = $replace;
								
								$message .= "Table   : ".$replace->table."\n";
								$message .= "Result  : ".(($replace->result)?'success':'failed')."\n";
								$replaceTable .= $replace->table . ":" . $replace->result . "|"; 
							}
						}
					}
				}
				//write log result
				$this->utils->writeLog($message);
				//make replace result data for next page
				if (count($resultReplace)==0 && !$this->utils->isNull($replaceTable))
				{
					$arrHidTable = explode ( "|", $replaceTable);
					if (count($arrHidTable)>0)
					{
						$replaceTable = '';
						for ($i=0; $i<count($arrHidTable); $i++)
						{
							$replace = new stdClass();
							$sItem = $arrHidTable[$i];
							$arrItem = explode (":", $sItem);
							$replace->table = $arrItem[0];
							$replace->result = $arrItem[1];
							$resultReplace[] = $replace;
							$replaceTable .= $replace->table . ":" . $replace->result . "|"; 
						}
					}
				}
			}
			
			/*load data for content END*/
		}
		// set data for view page
		$arrData['arrTable'] = $arrTable;
		$arrData['pagination'] = $pagination;
		$arrData['tableName'] = $tableName;
		$arrData['page'] = $page;
		$arrData['hdAction'] = $hdAction;
		$arrData['resultFound'] = $arrSearchResult;
		$arrData['resultReplace'] = $resultReplace;
		//out put result to view 
		$arrData['replaceTable'] = substr($replaceTable, 0, -1);
		$arrData['txtFindText'] = $txtFindText;
		$arrData['txtNewText'] = $txtNewText;
		$this->dmdb->closeConn($current_db);
		$this->load->view('table_listing', $arrData);
	}
}
?>