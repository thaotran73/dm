<?php
/*
 * Created on 2008/09/25
 * Author Thao Tran
 * Information of table screen
 */
class Table_info extends Controller
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
		$strError = "";
		$strOverwrite = "";
		//variable to insert/update data
		$vars = array();
		$current_db = null;
		$isConnection = $this->dmdb->openConn($current_db);
		//check connection of database existed?
		/******************/
		$this->utils->checkConnection($isConnection);
		/******************/
		$tableName = $this->input->post('tableName');
		//check table name eixsted?
		/******************/
		if (!$tableName)
		{
			// Go to DM30 - Table Lisitng	
			redirect(DM30_ROUTE, "Location");
		}
		//get primary key for TableName
		$arrKeyName = array();
		$this->dmdb->getPrimaryKey($current_db, $tableName, $arrKeyName);
		/******************/

		//update num
		$update_no = $this->input->post('update_no');

		//order by data	
		$field_order = $this->input->post('field_order');
		$order_type = $this->input->post('order_type');
		$page = $this->input->post('page');
		$pageno_detail = $this->input->post('pageno_detail');
		$page = $page?$page:1;
		$pageno_detail = $pageno_detail?$pageno_detail:1;
		//get info from table listing
		$hdAction = $this->input->post('hdAction');
		$txtFindText = $this->input->post('txtFindText');
		$txtNewText = $this->input->post('txtNewText');
		$pagination = '';
		$arrFieldName = array();
		$arrOldKey = array();
		$arrOldKeyValue = array();
		$oFieldInfo = $this->dmdb->getFieldInfo($current_db, $tableName);
		//get data from post values to insert/update
		$arrFieldName = $this->dmdb->getFieldName($current_db, $tableName);
		$iTotalField = count($arrFieldName);
		for ($i=0; $i<$iTotalField; $i++)
		{
			$vars[$arrFieldName[$i]] = '';
		}
		$arrFieldList = array();
		$pagination_detail = '';
		$conn = $this->dmdb->getConn($current_db);
		$encoding = $this->utils->getEncoding($conn);
		
		//check connection existed?
		if ($isConnection)
		{
			// Load up_down_db object
			$this->load->model("up_down_db");
			$oUpDown = new Up_down_db();
			$act = $this->input->post('act');
			$total_record = $this->input->post('total_record');
			//select data to update
			if ($act=="select_data")
			{
				for ($i=1; $i<=$total_record; $i++)
				{
					if ($i==$update_no)
					{
						$arrKeyValue = array();
						for ($j=0; $j<count($arrKeyName); $j++)
						{
							$arrKeyValue[] = $this->input->post('pri_'.$update_no . '_' . $arrKeyName[$j]);
						}
						//get data
						//changed by Thao Tran on 20100309 START
						pg_set_client_encoding($conn, C_ENCODING);
						//changed by Thao Tran on 20100309 END
						$oItem = $oUpDown->selectRow($current_db, $tableName, $arrKeyName, $arrKeyValue);
						//restore encoding for client
						pg_set_client_encoding($conn, $encoding);
						$iTotalField = count($arrFieldName);
						if ($iTotalField > 0)
						{
							$oItem = $oItem->row();
							$vars = array();
							for ($k=0; $k<$iTotalField; $k++)
							{
// 								if (!$this->utils->isNull($oItem->$arrFieldName[$k]))
// 								{
									$vars[$arrFieldName[$k]] = $oItem->$arrFieldName[$k];
									//check and get old key values
									if (in_array($arrFieldName[$k], $arrKeyName))
									{
										$arrOldKey[$arrFieldName[$k]] = $oItem->$arrFieldName[$k];
									}
// 								}
							}
						}
					}
				}
			}
			// delete processing
			else if ($act=="delete")
			{
				for ($i=1; $i<=$total_record; $i++)
				{
					if ($this->input->post('chk_'.$i))
					{
						$arrKeyValue = array();
						for ($j=0; $j<count($arrKeyName); $j++)
						{
							$arrKeyValue[] = $this->input->post('pri_'.$this->input->post('chk_'.$i) . '_' . $arrKeyName[$j]);
						}
						//delete data
						$oUpDown->deleteRow($current_db, $tableName, $arrKeyName, $arrKeyValue);
					}
				}
			}
			//register processing
			else if ($act=="register" || $act=="update")
			{
				if ($current_db->table_exists($tableName))
				{
					//changed by Thao Tran on 20100303 START
					$iTotalField = count($arrFieldName);
					$vars = array();
					for ($i=0; $i<$iTotalField; $i++)
					{
// 						if (!$this->utils->isNull($this->input->post($arrFieldName[$i])))
// 						{
							$vars[$arrFieldName[$i]] = $this->input->post($arrFieldName[$i]);
// 						}
					}
					//get key values
					$arrKeyValue = array();
					for ($j=0; $j<count($arrKeyName); $j++)
					{
						$arrKeyValue[] = $this->input->post($arrKeyName[$j]);
						if ($this->input->post('pri_old_' . $arrKeyName[$j]))
						{
							$arrOldKeyValue[] = $this->input->post('pri_old_' . $arrKeyName[$j]);
						}
						$arrOldKey[$arrKeyName[$j]] = $this->input->post('pri_old_' . $arrKeyName[$j]);
					}

					//check data existed?
					$oItem = $oUpDown->selectRow($current_db, $tableName, $arrKeyName, $arrKeyValue);

					if (isset($oItem->num_rows) && $oItem->num_rows > 0)
					{
						if ($this->input->post('overwrite')=="1")
						{
							//changed by Thao Tran on 20100305 START
							pg_set_client_encoding($conn, C_ENCODING);
							//changed by Thao Tran on 20100305 END
							//overwrite data
							if ($oItem->num_rows > 0)
							{
								//delete old data
//								if (count($arrOldKeyValue)>0)
//								{
//									$oUpDown->deleteRow($current_db, $tableName, $arrKeyName, $arrOldKeyValue);
//								}
								//update data
								$result = $oUpDown->updateRow($current_db, $tableName, $vars, $arrKeyName, $arrKeyValue);
							}
							else
							{
								$oUpDown->deleteRow($current_db, $tableName, $arrKeyName, $arrKeyValue);
								//insert new data
								$result = $oUpDown->insertRow($current_db, $tableName, $vars);
							}
							//restore encoding for client
							pg_set_client_encoding($conn, $encoding);	
							//error when input data
							if ($this->utils->isNull($result))
							{
								$strError .= INCORRECT_INPUT_DATA;
							}
						}
						else
						{
							//insert case
							if ($act=='register')
							{
								//confirm overwrite data
								$strOverwrite .= OVERWRITE_DATA;							
							}
							//update case
							else if ($act=='update')
							{
								$bFlag = false;
								$arrKeyValue = array();
								for ($j=0; $j<count($arrKeyName); $j++)
								{
									$arrOldKeyValue[] = $this->input->post('pri_old_' . $arrKeyName[$j]);
									$arrKeyValue[] = $this->input->post($arrKeyName[$j]);
									if ($this->input->post('pri_old_' . $arrKeyName[$j])!=$this->input->post($arrKeyName[$j]))
									{
										$bFlag = true;
										break;
									}
								}
								//check existed?
								if ($bFlag)
								{
									//check data existed?
									$oItem = $oUpDown->selectRow($current_db, $tableName, $arrKeyName, $arrKeyValue);
				
									if ($oItem->num_rows > 0)
									{
										//confirm overwrite data
										$strOverwrite .= OVERWRITE_DATA;
									}
								}
								if ($strOverwrite=='')
								{
									//changed by Thao Tran on 20100305 START
									pg_set_client_encoding($conn, C_ENCODING);
									//changed by Thao Tran on 20100305 END
									//overwrite data
									//delete old data
									$oUpDown->deleteRow($current_db, $tableName, $arrKeyName, $arrOldKeyValue);
									//insert new data
									$result = $oUpDown->insertRow($current_db, $tableName, $vars);
									//restore encoding for client
									pg_set_client_encoding($conn, $encoding);	
									//error when input data
									if ($this->utils->isNull($result))
									{
										$strError .= INCORRECT_INPUT_DATA;
									}
								}
							}
						}
					}
					else
					{
						//changed by Thao Tran on 20100305 START
						pg_set_client_encoding($conn, C_ENCODING);
						//changed by Thao Tran on 20100305 END
						//insert case
						if ($act=='register')
						{
							$result = $oUpDown->insertRow($current_db, $tableName, $vars);
						}
						//update case
						else if ($act=='update')
						{
							if (count($arrOldKeyValue)>0)
							{
								$arrKeyValue = $arrOldKeyValue;
							}
//							$oUpDown->deleteRow($current_db, $tableName, $arrKeyName, $arrKeyValue);
							//insert new data
							$result = $oUpDown->insertRow($current_db, $tableName, $vars);
						}
						//restore encoding for client
						pg_set_client_encoding($conn, $encoding);
						if ($this->utils->isNull($result))
						{
							$strError .= INCORRECT_INPUT_DATA;
						}
					}
					//changed by Thao Tran on 20100303 END
				}
				//set new data to save old key
				if ($this->utils->isNull($strError) && $this->utils->isNull($strOverwrite))
				{
					for ($k=0; $k<$iTotalField; $k++)
					{
// 						if (!$this->utils->isNull($this->input->post($arrFieldName[$k])))
// 						{
							//check and get old key values
							if (in_array($arrFieldName[$k], $arrKeyName))
							{
								$arrOldKey[$arrFieldName[$k]] = $this->input->post($arrFieldName[$k]);
							}
// 						}
					}
				}
			}
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
			if (!$this->utils->isNull($tableName))
			{
				if ($current_db->table_exists($tableName))
				{
					$arrFieldName = $this->dmdb->getFieldName($current_db, $tableName);
					// Load table listing object
					$this->load->model("table_info_db");
					$oTableInfoDB = new Table_info_db();
					$iTotal = $oTableInfoDB->getTableInfoTotal($current_db, $tableName);
					$arrFieldList = $oTableInfoDB->getTableInfo($current_db, $tableName, $pageno_detail, DM31_RECORD_PER_PAGE, $field_order, $order_type);
					//set Prev Next Page for view detail
					$pagination_detail = $this->utils->getPaginationString($pageno_detail, $iTotal, DM31_RECORD_PER_PAGE, 'process_page_detail');
				}
			}
			/*load data for content END*/
		}
		// set data for view page
		$arrData['arrTable'] = $arrTable;
		$arrData['pagination'] = $pagination;
		$arrData['totalRecord'] = $iTotal;
		$arrData['arrKeyName'] = $arrKeyName;
		$arrData['page'] = $page;
		$arrData['old_field_order'] = $field_order;
		$arrData['old_order_type'] = $order_type;
		$arrData['tableName'] = $tableName;
		$arrData['arrFieldName'] = $arrFieldName;
		$arrData['arrFieldList'] = $arrFieldList;
		$arrData['oFieldInfo'] = $oFieldInfo;
		$arrData['pagination_detail'] = $pagination_detail;
		$arrData['pageno_detail'] = $pageno_detail;
		$arrData['hdAction'] = $hdAction;
		$arrData['txtFindText'] = $txtFindText;
		$arrData['txtNewText'] = $txtNewText;
		$arrData['encoding'] = $encoding;
		$arrData['warning1'] = $strError;
		$arrData['strOverwrite'] = $strOverwrite;
		$arrData['vars'] = $vars;
		$arrData['update_no'] = $update_no;
		$arrData['arrOldKey'] = $arrOldKey;
		$this->dmdb->closeConn($current_db);
		$this->load->view('table_info', $arrData);
	}
}
?>