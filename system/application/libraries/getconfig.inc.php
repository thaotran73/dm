<?php
/**
* get value of config file
* @param - $sFileName: config file name
* 		 - $sName	 : field name
* @return - value
**/
function getConfigFile($sFileName, $sName){
    $bFlag = true;
	$sValue = "";
   	// read config file
   	if (!$cfg = @file($sFileName))
   	{
        $bFlag = false;
    }
    if ($bFlag)
    {
    	while (list($key,$val) = each($cfg))
    	{
			if (!is_null($val))
			{
				$arrItem = explode("=",trim($val), 2);
			}
			if (count($arrItem) == 2)
			{        
	       		if (trim($sName)==trim($arrItem[0]))
	       		{
	           		$sValue = trim($arrItem[1]);
		           	break;
		       	}
			}
	    }
    }
    return $sValue;
}
?>
