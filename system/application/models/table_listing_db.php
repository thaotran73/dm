<?php
/**
 * Date modified : 2008/10/01
 * List of tables 
 */
require_once(C_LIB_PATH.'constant.inc.php');

class Table_listing_db extends Model 
{
	
	function __construct()
	{
        // Call the Model constructor
        parent::Model();     
    }
    
    /**
	 * Replace value of all field of table with new value
	 * @param	table name, old value, new value
	 * @return	boolean
	 */
	function replace($current_db, $tableName, $findText, $newText)
	{
		
		$types = array("varchar", "char", "text", "bpchar", "name");
		//set shift-jis encoding for execute db
		$conn = $this->dmdb->getConn($current_db);
		
		// get field's info
		$fields = $this->dmdb->getFieldInfo($current_db, $tableName);
		
		$encoding = $this->utils->getEncoding($conn);
		pg_set_client_encoding($conn, C_ENCODING);

		$paras = array();
		$SET = array();
		//make list of field names for search condition
		foreach ($fields as $field)
		{
			$sFieldType = $field->type;
			$sFieldName = $field->name;
			if(in_array($sFieldType, $types))
			{
				$paras[] = $findText;
				$paras[] = $newText;
				$SET[] = " {$sFieldName} = REPLACE({$sFieldName}, ?, ?) ";
			}
		}
		//make sql string
		if(count($SET))
		{
			$sQuery = "UPDATE ". SCHEMA . "." ."{$tableName} SET ". implode(",", $SET);
		}
		//execute query
		$rs = $current_db->query($sQuery, $paras);
		unset($paras);
		unset($SET);
		//restore encoding for client
		pg_set_client_encoding($conn, $encoding);
		
		if ($rs)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Compare field's data of table with input data
	 * @param	table name, old value, new value
	 * @return	boolean
	 */
	function find($current_db, $tableName, $findText){
		
		$types = array("varchar", "char", "text", "bpchar", "name");
		//set shift-jis encoding for execute db
		$conn = $this->dmdb->getConn($current_db);
		
		// get field's info
		$fields = $this->dmdb->getFieldInfo($current_db, $tableName);
		
		$encoding = $this->utils->getEncoding($conn);
		pg_set_client_encoding($conn, C_ENCODING);

		$paras = array();
		$WHERE = array();
		//make list of field names for search condition
		foreach ($fields as $field)
		{
			$sFieldType = $field->type;
			$sFieldName = $field->name;
			if(in_array($sFieldType, $types))
			{
				$paras[] = $findText;
				$WHERE[] = " POSITION(? IN {$sFieldName}) > 0 ";
			}
			
		}
		//make sql string
		if(count($WHERE) && count($paras))
		{
			$sQuery = "SELECT COUNT(*) AS total FROM ". SCHEMA . "." ."{$tableName} WHERE ". implode(" OR ", $WHERE);
			//execute query
			$rs = $current_db->query($sQuery, $paras);
			unset($paras);
			unset($WHERE);
			if ($rs && $rs->row()->total > 0)
			{
				//restore encoding for client
				pg_set_client_encoding($conn, $encoding);
				return TRUE;
			}
		}
		//restore encoding for client
		pg_set_client_encoding($conn, $encoding);
		return FALSE;
	}
}
?>