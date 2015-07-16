<?php
/*
 * define database connection
 * Author: Thao Tran
 * Created on 2008/09/23
 */
 
class Dmdb	
{ 
	function Dmdb() {
		$this->dm = &get_instance();
	}
	
	/**
	 * Close connection to database
	 * @param  : none
	 * @return : none
	 * Data create : 20080810
	 */
	function closeConnectDB()
	{
		$this->dm->db->close();
	}

	/**
	 * Created : 2008/09/29
	 * load other database
	 * @return: database object
	 */
	function loadDatabase($oStructure)
	{		
		$db['hostname'] = DB_HOST;
		$db['username'] = isset($oStructure->db_username)?$oStructure->db_username:'';
		$db['password'] = isset($oStructure->db_password)?$oStructure->db_password:'';
		$db['database'] = isset($oStructure->db_name)?$oStructure->db_name:'';
		$db['dbdriver'] = DB_DRIVER;
		$db['port'] = PORT;
		
		$db['dbprefix'] = "";
		$db['pconnect'] = TRUE;
		$db['db_debug'] = FALSE;
		$db['cache_on'] = FALSE;
		$db['cachedir'] = "";
		
		$dbConnection = $this->dm->load->database($db, true);

		return $dbConnection;
	}
	
	/**
	 * get connection
	 * @return connection
	 **/
	function getConn($dbConnect=null)
	{
		if (isset($dbConnect) && !empty($dbConnect))
		{
			return $dbConnect->conn_id;
		}
		else
		{
			return $this->dm->db->conn_id;
		}
	}
	
	/**
	* open connection
	* @return conn if connection succ else return false
	* */
    function openConn(&$current_db=null)
    {
    	$db_username = $this->dm->session->userdata('db_username');
    	$db_password = $this->dm->session->userdata('db_password');
    	$db_name = $this->dm->session->userdata('db_name');
    	$bConnect = false;
    	$oCon = new stdClass();
		$oCon->db_username = $db_username;
		$oCon->db_password = $db_password;
		$oCon->db_name = $db_name;
		$current_db = $this->loadDatabase($oCon);
		
		if ($current_db->conn_id)
		{
			$bConnect = true;
		}
		
		return $bConnect;
    }
    
    function checkConn($current_db)
    {
    	$bConnect = false;
    	if ($current_db && $current_db->conn_id)
		{
			$bConnect = true;
		}
		
		return $bConnect;
    }
    
    /**
	* close connection
	* @param conn : connection
	* */
    function closeConn($current_db)
    {
    	if ($current_db)
    	{ 
    		$current_db->close();
    	}
    }
	
	/**
     * Created on 2008/09/29
     * Author: Thao Tran
     * get list of databases from postgresDB
     * */
    function getDatabaseListing()
    {
    	$sql = "SELECT pdb.datname AS db_name
    					, pg_encoding_to_char(encoding) AS encoding_type
				FROM pg_catalog.pg_database pdb LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)  
				WHERE true 
					AND pr.rolname='".DB_USERNAME."'
					AND NOT pdb.datistemplate
					AND pdb.datname!='postgres'
				ORDER BY pdb.datname";
		$query = $this->dm->db->query($sql);
		return $query->result();
    } 
    
    /**
     * Created on 2008/09/29
     * Author: Thao Tran
     * get database information from postgresDB
     * */
    function getDatabaseInfo($db_name)
    {
//    	$sql = "SELECT pdb.datname AS db_name
//    					, pr.rolname AS db_owner
//    					, pg_encoding_to_char(encoding) AS encoding_type
//				FROM pg_catalog.pg_database pdb LEFT JOIN pg_catalog.pg_roles pr ON (pdb.datdba = pr.oid)  
//				WHERE true 
//					AND pr.rolname='".DB_USERNAME."'
//					AND NOT pdb.datistemplate
//					AND pdb.datname!='postgres'
//					AND pdb.datname=?
//				ORDER BY pdb.datname";
//				
//		$param = array($db_name);
//		$query = $this->dm->db->query($sql, $param);
//		return $query->row();
		$oItem = new stdClass();
		$oItem->db_name = $db_name;
		$oItem->db_owner = DB_USERNAME;
		return $oItem;
    }
	
	/**
     * Created on 2008/09/29
     * Author: Thao Tran
     * get list of tables from current DB in postgres
     * */
	function loadTableListing($current_db)
	{
		return $current_db->list_tables();
	}
	
	/**
	 * get data in break page case
	 * @param: $arrData : array data
	 * 		   $iPageNo : page no
	 * 		   $iRowPerPage : row per page
	 * @return: array data
	 * **/
	function getData4BreakPage($arrData, $iPageNo=1, $iRowPerPage=10)
	{
		$arrReturn = array();
		if (is_array($arrData) && count($arrData)>0)
		{
			$iTotal = count($arrData);
			//init data for row per page
			$iRowPerPage = isset($iRowPerPage)?$iRowPerPage:DEFAULT_RECORD_PER_PAGE;
			//init data for page number
			if($iPageNo == 0 || is_null($iPageNo))
			{
				$iPageNo = 1;
			}
			//get from, to data for array start 
			$iTo = $iPageNo*$iRowPerPage;
	        $iFrom = $iTo-$iRowPerPage;
	        $iTo = $iTo > $iTotal?$iTotal:$iTo;
	        for ($i=$iFrom; $i<$iTo; $i++)
	        {
	        	$arrReturn[] = $arrData[$i];
	        }
		}
		return $arrReturn;
	}

	/**
	 * get primary key of a table
	 * @param : $conn connection
	 * @param : $tableName string
	 * @param : $arrKeyName array key name (reference value)
	 * @return : none
	 * */
	function getPrimaryKey($current_db, $tableName, &$arrKeyName)
	{
		$sReturn = "";
		$sQuery = "SELECT c.column_name
					FROM information_schema.table_constraints tc 
					JOIN information_schema.constraint_column_usage AS ccu USING (constraint_schema, constraint_name) 
					JOIN information_schema.columns AS c ON c.table_schema = tc.constraint_schema AND tc.table_name = c.table_name AND ccu.column_name = c.column_name
					where constraint_type = 'PRIMARY KEY' and tc.table_name = '".$tableName."'";
		$result = $current_db->query($sQuery);
		$arrData = $result->result();
		foreach($arrData as $data)
        {
			if ($data->column_name)
			{
				$arrKeyName[] = $data->column_name;
			}
        }
        return;
	}
	
	/**
	* field info of a table
	* @param : $tableName string
	* return : array
	* */
	function getFieldInfo($current_db, $tableName)
	{
		$sQuery = "	SELECT
			                a.attnum,
			                a.attname AS name,
							CASE WHEN t.typname='bpchar' OR t.typname='varchar' THEN (a.atttypmod - 4)
							ELSE 
								a.attlen
							END AS max_length,
			                t.typname AS type,
			                a.attnotnull AS notnull
			        FROM
			                pg_class c,
			                pg_attribute a,
			                pg_type t
			        WHERE
			                c.relname = '" . $tableName . "'
			                and a.attnum > 0
			                and a.attrelid = c.oid
			                and a.atttypid = t.oid
			        ORDER BY a.attnum";
		$result = $current_db->query($sQuery);
		return $result->result();
	}
	
	/**
	* list of field name of a table
	* @param : $tableName string
	* return : array
	* */
	function getFieldName($current_db, $tableName)
	{
		return $current_db->list_fields($tableName);
	}
	
	/**
	* field type of a field of a table
	* @param : $current_db: current connection
	* 		   $tableName: string
	* 		   $fieldName: string
	* return : string
	* */
	function getFieldType($current_db, $tableName, $fieldName)
	{
		$fields = $this->getFieldInfo($current_db, $tableName);//$current_db->field_data($tableName);
		foreach ($fields as $field)
		{
			if ($field->name == $fieldName)
			{
				return $field->type;
			}
		}
		return '';
	}
	
	/**
	* length of a field of a table
	* @param : $current_db: current connection
	* 		   $tableName: string
	* 		   $fieldName: string
	* return : integer
	* */
	function getFieldLength($current_db, $tableName, $fieldName)
	{
		$fields = $this->getFieldInfo($current_db, $tableName);//$current_db->field_data($tableName);
		foreach ($fields as $field)
		{
			if ($field->name == $fieldName)
			{
				return $field->max_length;
			}
		}
		return '';
	}
	
	/**
	 * count field number of table
	 * @param : $tableName string
	 * return : integer
	 **/
	function numFields($current_db, $TableName)
	{
		return count($this->getFieldName($current_db, $TableName));
	}
	
	

}
?>
