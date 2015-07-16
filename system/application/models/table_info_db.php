<?php
/**
 * Date modified : 2008/10/10
 * List of tables 
 */
require_once(C_LIB_PATH.'constant.inc.php');

class Table_info_db extends Model 
{
	
	function __construct()
	{
        // Call the Model constructor
        parent::Model();    
         
    }
    
    function getTableInfoTotal($current_db, $table_name)
    {
    	//set shift-jis encoding for execute db
		$conn = $this->dmdb->getConn($current_db);
		$encoding = $this->utils->getEncoding($conn);
		pg_set_client_encoding($conn, C_ENCODING);
		$sQuery = "SELECT COUNT(*) AS total FROM $table_name";
		//execute query
		$query = $current_db->query($sQuery);
		//restore encoding for client
		pg_set_client_encoding($conn, $encoding);
		if($query->num_rows() > 0)
		{
			return $query->row()->total;
		}
		return 0;
    }
    
    function getTableInfo($current_db, $table_name, $pageno_detail, $iRowPerPage, $sFieldOrder='', $sOrderType='DESC')
    {
    	//get current page from post method
		$sLimit = $this->utils->getLimitString($pageno_detail, $iRowPerPage);
    	//set shift-jis encoding for execute db
		$conn = $this->dmdb->getConn($current_db);
		$encoding = $this->utils->getEncoding($conn);
		pg_set_client_encoding($conn, $encoding);
		$sQuery = "SELECT * FROM $table_name";
		if ($sFieldOrder)
		{
			$sQuery .= " ORDER BY " . $sFieldOrder . " " . $sOrderType;
		}
		if ($sLimit)
		{
			$sQuery .= $sLimit;
		}
		//execute query
		$query = $current_db->query($sQuery);
		
		if($query->num_rows() > 0)
		{
			return $query->result();
		}
		return null;
    }
}
?>
