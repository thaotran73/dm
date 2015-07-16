<?php
/**
 * Created on 2008/09/29
 * Author : Thao Tran
 * Load data information for main page
 **/
class Main_db extends Model 
{
	function __construct()
	{
        /** Call the Model constructor */
        parent::Model();
    }
    
    /**
     * Created on 2008/09/29
     * Author: Thao Tran
     * get list of databases from postgresDB and put into them to option data
     * */
    function getDatabaseListing()
    {
		//get database listing from current db
//		$arrData = $this->dmdb->getDatabaseListing();
		//get db listing from config file
		$arrDBList = $this->utils->getConfigDataList(C_DB_PATH.C_DM_CONFIG_FILE, DB_LIST);
		
		$arrReturn = array();
		$arrReturn[' '] = ' ';
		
		$arrReturn = array_merge($arrReturn, $arrDBList);
		
//		if (!is_null($arrData) && is_array($arrData))
//    	{
//	    	for ($i=0; $i<count($arrData); $i++)
//	    	{
//	    		$oData = $arrData[$i];
//	    		if (!is_null($oData))
//	    		{
//	    			$db_name = $oData->db_name;
//	    			if (array_key_exists($db_name, $arrDBList))
//	    			{
//	    				$arrReturn[$oData->db_name] = $arrDBList[$db_name];
//	    			}
//	    		}
//	    	}
//    	}
		return $arrReturn;
    } 
}
?>
