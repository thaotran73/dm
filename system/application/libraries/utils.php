<?php
/**
 * Created on 2008/09/23
 * Utils class
 **/
require_once(C_LIB_PATH.'getconfig.inc.php');
require_once(C_LIB_PATH.'constant.inc.php');

class Utils	
{ 

	function Utils() {
		$this->dm = &get_instance();
	}
	
	/**
	 * Reqire login
	 * Redirect to login form if need check privilegd or session
	 */
	 function requireLogin($isAdmin=FALSE)
	 {
 		if($isAdmin){
			if(!$this->checkAdmin())
			{
		 		redirect(DM00_ROUTE, "Location");
				die();
		 	}
 		}
 		else
 		{
 			if(!$this->checkLogin())
 			{
		 		redirect(DM00_ROUTE, "Location");
				die();
		 	}
 		}
	 }
	 
	 function checkConnection($isConn)
	 {
	 	if (!$isConn)
	 	{
	 		redirect(DM10_ROUTE, "Location");
			die();
	 	}
	 }
	 
	 /**
	 * Check login session for require web form
	 * reurn : boolean value : TRUE (session exist) | FALSE (session is expire or not exist)
	 */
	 function checkLogin(){
	 	$logged = $this->dm->session->userdata('logged');
	 	if($logged)
	 	{
	 		return TRUE;
	 	}
	 	return FALSE;
	 } 

	/**
	 * Check admin privilegd
	 * reurn : boolean value : TRUE | FALSE
	 */
	 function checkAdmin()
	 {
	 	if(!$this->checkLogin())
	 	{
	 		return FALSE;
	 	}
	 	return TRUE;
	 }
	
	/**
     * Created on 2008/09/26
     * Set working session
     * @param : userID
     * @return : session[userID], session[logged]
     */
    function setSessionLogin($oCond){

		$clientTime = intval($oCond->client_time/1000);
		/**
		 * Because IE is using client to run session.
		 * So we need asign client time at begin create session start
		 */
		if(isset($_SERVER['HTTP_USER_AGENT']))
		{
			$agent = trim($_SERVER['HTTP_USER_AGENT']);
			if(strpos($agent, 'MSIE')||strpos($agent, 'Internet Explorer')){
				$this->dm->session->now = $clientTime;
				$this->dm->session->sess_length = $this->dm->session->sess_length + abs(time()-$clientTime);
			}
		}
		$session_data['s_user_id'] = $oCond->s_user_id;
		$session_data['logged'] = TRUE;

		$this->dm->session->set_userdata($session_data);
    }
    
    /**
	 * Create general session
	 * Author: Thao Tran
	 * Created Date: 2008/09/30
	 **/
	function setGeneralSession($sKey, $sValue)
	{
		if(isset($sKey) && !is_null($sValue))
		{
			$session_info = $this->dm->session->userdata;
			$session_info[$sKey] = $sValue;
			$this->dm->session->set_userdata($session_info);			
		}
	}
	
	/**
	 * clear general session
	 * Author: Thao Tran
	 * Created Date: 2008/09/30
	 **/
	function clearGeneralSession($sKey)
	{
		if(isset($sKey))
		{
			$this->dm->session->unset_userdata(array($sKey));
		}
	}
    
    /**
     * Created on 2008/09/26
     * Clear current session
     * @param : none
     * @return : none
     */
    function clearSession(){
		
		$this->dm->session->sess_destroy();
    }
    
    /**
     * set session for config information
     * created on 2008/10/01
     * Author: Thao Tran
     * **/
    function clearConfigSession()
    {
    	//clear session db info
		$this->setGeneralSession('db_name', '');
		$this->setGeneralSession('db_username', '');
		$this->setGeneralSession('db_password', '');
		$this->setGeneralSession('status', '');
    }
	
	/**
	 * get encoding from database
	 * @param  resource 	$conn 
	 * @return string 		(UTF8, EUC_JP)
	 */
	function getEncoding($conn)
	{
		return pg_client_encoding($conn);
	}
	
	/**
	 * Created on 2008/09/23
	 * convert string into shift-jis encoding(Japanese)
	 * @param  string : $s_sample 
	 * 		   string:	$encoding	
	 * @return string string in Shift-jis formatting
	 */
	function jPEncoding($s_sample,$encoding='EUC_JP')
	{		
		if (is_null($s_sample))
		{
			return "";
		}
		
		if('UTF8' == strtoupper($encoding))
		{
			return mb_convert_encoding($s_sample, 'Shift-jis', 'UTF8');
		}
		return mb_convert_encoding($s_sample, 'Shift-jis', 'EUC_JP');
	}
	
	/**
	 * Checks whether object is null
	 * @param : object
	 * @return boolean - returned result
	 */
	function isNull($obj)
	{
		if (is_null($obj) || (strlen(trim($obj))==0) || empty($obj)) 
		{
			return TRUE;
		}
		return FALSE; 
	}
	
	/**
	 * Load pagination pages
	 * @param : 	page number 	current page
	 * 				totalitems 		total items (rows)
	 * 				limit 			rows per page
	 * 				adjacents
	 * @return : 	string - The string presents pagination
	 */
	function getPaginationString($page = 1, $totalitems, $limit, $fn_process_page=null, $adjacents = 2)
	{
		//defaults
		if (!$fn_process_page)
		{
			$fn_process_page = 'process_page';
		}
		if(!$adjacents)
		{
			$adjacents = 1;	
		}
		if(!$limit)
		{
			$limit = 15;	
		}
		
		if($page == 0 || $this->isNull($page))
		{
			$page = 1;
		}
		
		//other vars
		$prev = $page - 1;
		$next = $page + 1;
		if ($limit!=0)
		{
			$lastpage = ceil($totalitems / $limit);
		}
		
		$lpm1 = $lastpage - 1;
		
		/**
		 * Now we apply our rules and draw the pagination object.
		 * We're actually saving the code to a variable in case we want to draw it more than once.
		 **/
		$pagination = "";
		if($lastpage > 1)
		{
			$pagination .= "<div class=\"pagination\"";
			if(isset($margin) || isset($padding))
			{
				$pagination .= " style=\"";
				if(isset($margin))
				{
					$pagination .= "margin: $margin;";
				}
				if(isset($padding))
				{
					$pagination .= "padding: $padding;";
				}
				$pagination .= "\"";
			}
			$pagination .= ">";
	
			//previous button
			if ($page > 1)
			{
				$pagination .= "<a href=\"javascript:$fn_process_page('$prev')\"><< prev</a>";
			}	
			else
			{
				$pagination .= "<span class=\"disabled\"><< prev</span>";
			}
			
			//pages	
			if ($lastpage < 5 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
					{
						$pagination .= "<span class=\"current\">$counter</span>";
					}
					else
					{
						$pagination .= "<a href=\"javascript:$fn_process_page('$counter')\">$counter</a>";
					}
				}
			}
			elseif($lastpage >= 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < (1 + ($adjacents * 3)))		
				{
					for ($counter = 1; $counter < (4 + ($adjacents * 2)); $counter++)
					{
						if ($counter == $page)
						{
							$pagination .= "<span class=\"current\">$counter</span>";
						}
						else
						{
							$pagination .= "<a href=\"javascript:$fn_process_page('$counter')\">$counter</a>";
						}
					}
					$pagination .= "...";
					$pagination .= "<a href=\"javascript:$fn_process_page('$lpm1')\">$lpm1</a>";
					$pagination .= "<a href=\"javascript:$fn_process_page('$lastpage')\">$lastpage</a>";
				}
				//in middle; hide some front and some back
				elseif(($lastpage - ($adjacents * 2)) > $page && $page > ($adjacents * 2))
				{
					$pagination .= "<a href=\"javascript:$fn_process_page('1')\">1</a>";
					$pagination .= "<a href=\"javascript:$fn_process_page('2')\">2</a>";
					$pagination .= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
						{
							$pagination .= "<span class=\"current\">$counter</span>";
						}
						else
						{
							$pagination .= "<a href=\"javascript:$fn_process_page('$counter')\">$counter</a>";
						}
					}
					$pagination .= "...";
					$pagination .= "<a href=\"javascript:$fn_process_page('$lpm1')\">$lpm1</a>";
					$pagination .= "<a href=\"javascript:$fn_process_page('$lastpage')\">$lastpage</a>";
				}
				//close to end; only hide early pages
				else
				{
					$pagination .= "<a href=\"javascript:$fn_process_page('1')\">1</a>";
					$pagination .= "<a href=\"javascript:$fn_process_page('2')\">2</a>";
					$pagination .= "...";
					for ($counter = ($lastpage - (1 + ($adjacents * 3))); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
						{
							$pagination .= "<span class=\"current\">$counter</span>";
						}
						else
						{
							$pagination .= "<a href=\"javascript:$fn_process_page('$counter')\">$counter</a>";
						}
						
					}
				}
			}
			//next button
			if ($page < ($counter - 1))
			{
				$pagination .= "<a href=\"javascript:$fn_process_page('$next')\">next >></a>";
			}
			else
			{
				$pagination .= "<span class=\"disabled\">next >></span>";
			}
			$pagination .= "</div>\n";
		
		}
		return $pagination;
	}

	/**
	 * Author: Thao Tran
	 * Created on 20080812
	 * get offset of a page 
	 * @param $nPageNo: page no
	 * 		  $nRowPerPage: num of records on a page 
	 * @return: string data
	 **/	
	function getLimitString($nPageNo, $iRowPerPage)
	{
		$sReturn = '';
		if (isset($iRowPerPage) && $iRowPerPage>0)
		{
			if($nPageNo == 0 || is_null($nPageNo))
			{
				$nPageNo = 1;
			}
			$iOffset = ($nPageNo-1)*$iRowPerPage;
			$sReturn = " LIMIT ".$iRowPerPage." OFFSET " . $iOffset;
		}
		return $sReturn;
	}
	
	/**
	* Logger
	* - Writes data to a specified log
	* @author Derek Gathright <drgath@gmail.com>
	*/
	function logger($fileName, $msg, $path=NULL)
	{
		// Set default path if path is not specified
		if($this->isNull($path))
		{
			$path = C_DB_PATH;
		}
		
		// Check valid file name and fix spaces in string 
		$fileName = trim($fileName);
		if($this->isNull($fileName))
		{
			return false;
		}
		
		// Get file path to write
		$file = $path . $fileName;
		if (!file_exists($path))
		{
			$this->makeDir($path, "/");
		}
		
		// a+ : append type
		if ($log = fopen($file, 'a+'))
		{
			fwrite($log, $msg . "\r\n");	
			fclose($log);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Write Log File
	 *
	 * Generally this function will be called using the global log_message() function
	 *
	 * @access	public
	 * @param	string	the error message
	 * @return	bool
	 */
	function writeLog($msg)
	{
		$filepath = C_DB_PATH.'/'.'log-'.date('Y-m-d').'.log';
		
		if(!file_exists(C_DB_PATH))
		{
			$this->makeDir(C_DB_PATH, '/');
		}
		
		if(!$fp = @fopen($filepath, "a"))
		{
			return FALSE;
		}
		
		$message = $msg."\n";
		
		fwrite($fp, $message);
		fclose($fp);
		
		@chmod($filepath, 0666);
		return TRUE;
	}
	
	/**
	 * make directory of folder
	 * @Author: Thao Tran
	 * Date: 2008/09/26
	 * @param: $strPath - path of directory
	 * 		   $strSeparator - Separator
	 * @return: true if ok; false if not ok
	 * */
	function makeDir($strPath, $strSeparator)
	{
		$bResult = true;
		if (!empty($strPath) && !empty($strSeparator))
		{
			$arrPath = explode($strSeparator, $strPath);
			if (is_array($arrPath) && count($arrPath)>0)
			{
				$strPathTemp = $strSeparator;
				$intCount = count($arrPath);
				$intFrom = 0;
				$intTo = $intCount;
				//check if have first strSeparator then cancel it
				if (strpos($strPath, $strSeparator)==0)
				{
					$intFrom = 1;
				}
				//check if have last strSeparator then cancel it
				if (strrpos($strPath, $strSeparator)==(strlen($strPath)-1))
				{
					$intTo = $intCount-1;
				}
				for ($i=$intFrom; $i<$intTo; $i++)
				{
					$strPathTemp .= $arrPath[$i] . $strSeparator;
					if (!file_exists($strPathTemp) && $bResult)
					{
						$bResult = mkdir($strPathTemp, 0777, true);
					}
				}
			}
		}else{
			$bResult = false;
		}
		return $bResult;
	}
	
	/**
	 * @param - string		$sFromDate	(YYYY/MM/DD H:i)
	 * 			string		$sToDate	(YYYY/MM/DD H:i)
	 * @return  true if $sFromDate is smaller than $sToDate; otherwise false
	 **/
	function compareFromToDate($sFromDate, $sToDate)
	{
		if (empty($sToDate) || empty($sFromDate))
		{
			return false;
		}

		$n_hour_diff = (strtotime($sToDate)-strtotime($sFromDate)) / 3600;
		
		if 	($n_hour_diff >= 0)
		{ 
			return true;
		}
		else
		{ 
			return false;
		}	
	}

	/**
	 * update a file and rename this file
	 * @param: $strPathSaveTo: destination path, $file: file; $s_file_name: destination file name
	 * @return: true if ok; else false
	 **/
	function uploadFileAndRename($strPathSaveTo, $file, $s_file_name)
	{
		$bReturn = true;
		if ($file['name'])
		{
			if (!file_exists($strPathSaveTo))
			{
				mkdir ($strPathSaveTo, 0777);
			}
			$strPath = $strPathSaveTo . $s_file_name;
			if (is_file($strPath))
			{
				unlink($strPath);	
			}
			if (!is_file($strPath))
			{
				if (!copy($file['tmp_name'], $strPath))
				{
					$bReturn = false;
				}
			}
		}
		return $bReturn;
	}
	
	/**
	 * load array files from another folder
	 * @param: $sPathOfFolder: path of folder; $sFileName: file name
	 * @return true: existed else none
	 **/
	function checkFileExistInFolder($sPathOfFolder, $sFileName)
	{
		$bReturn = false;
		//if exist folder then list all files on this folder 
		
		if (is_dir($sPathOfFolder))
		{
			if ($handle = opendir($sPathOfFolder))
			{
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != "..")
					{
						if ($sFileName == $file)
						{
							$bReturn = true;
							break;
						}
					} 
				}
				closedir($handle); 
			}
		}
		return $bReturn;
	}
	
	/**
	 * Created : 2008/08/12
	 * download a file from server and rename this file is $s_file_name
	 * @param 	string	 	$strPathFileName 	- 	path of file
	 * @param	string		$s_file_name 		- 	new file name
	 * @return 	boolean 						-	true if download success else return false
	 * */
	function downloadFileRenameFile($strPathFileName, $s_file_name){
		if (file_exists($strPathFileName))
		{
			$size = filesize($strPathFileName);
			$file_extension = strtolower(substr(strrchr($s_file_name,"."),1));
			if ($file_extension != "txt")
			{
				$sType = "application/octet-stream";
			}else{
				$sType = "text/plain";
			}
			//Begin writing headers
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public"); 
			header("Content-Description: File Transfer");
			//Use the switch-generated Content-Type
			header("Content-Type: $sType");
			//Force the download
			$header="Content-Disposition: attachment; filename=".$s_file_name.";";
			header($header);
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$size);
			readfile($strPathFileName);
			exit;
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * download a file from server
	 * @param - $strPathFileName : path of file
	 * @return - true if download success else return false
	 **/
	function downloadFile($strPathFileName)
	{
		$s_file_name = "";
		if (file_exists($strPathFileName))
		{
			$s_file_name = substr($strPathFileName, strrpos($strPathFileName, "/")+1);
		}
		return $this->downloadFileRenameFile($strPathFileName, $s_file_name);
	}
	
	/**
	 * convert from array to object
	 * make sure your array have key for each value
	 * a key in array is a field in object
	 * para: array contain your info 
	 **/
	function convert_array_to_object($array)
	{
		$obj = "";
		if (!is_null($array) && is_array($array) && count($array)!=0)
		{
			foreach($array as $key => $value)
			{	
				$obj->$key = $value;
			}
		}
		return $obj;
	}

	/**
	 *  init array data
	 * @param : int				$from 			
	 * 			int				$to 			
	 * 			boolean			$bStartBlank	-	true if the first of array has empty value, otherwise it is false
	 * return aray
	 **/
	function initArrayDataInt($from, $to, $bStartBlank = false)
	{
		$arrData = array();
		
		if(is_null($from) || is_null($to) || $to < $from || !is_int($from) || !is_int($to))
		{
			return $arrData;
		}
		
		if (true == $bStartBlank)
		{
			$arrData[""] = "";
		}
		for($i = $from; $i <= $to; $i++)
		{
			$arrData["".sprintf("%2s", $i).""] = sprintf("%2s", $i);
		}
		return $arrData;
	}
	
	/**
	 * get day of week
	 * @param string of day
	 * @return day name of week
	 **/
	function convertDayOfWeek($sDay)
	{
		$sDayOfWeekName = "";
		$sDay = strtolower($sDay);
		if ($sDay=='sun')
		{
			$sDayOfWeekName = "“ú";
		}
		else if ($sDay=='mon')
		{
			$sDayOfWeekName = "ŒŽ";
		}
		else if ($sDay=='tue')
		{
			$sDayOfWeekName = "‰Î";
		}
		else if ($sDay=='wed')
		{
			$sDayOfWeekName = "…";
		}
		else if ($sDay=='thu')
		{
			$sDayOfWeekName = "–Ø";
		}
		else if ($sDay=='fri')
		{
			$sDayOfWeekName = "‹à";
		}
		else if ($sDay=='sat')
		{
			$sDayOfWeekName = "“y";
		}
		return $sDayOfWeekName;
	}
	
	/**
	 * split string date
	 * @param sData : string date Y/m/d H:i:s
	 * @return date - string yyyy”NmmŒŽdd“ú ~—j“ú H:i:s 
	 **/
	function convertToJPNDate($sData)
	{
		$sReturn = "";
		if (!empty($sData))
		{
			$new_dt = strtotime($sData);
			$sReturn .= date("Y", $new_dt) . "”N" . date("m", $new_dt) . "ŒŽ" . 
			date("d", $new_dt) . "“ú " . $this->convertDayOfWeek(date("D", $new_dt)) . "—j“ú " . 
			date("H:i:s", $new_dt);
		}
		return $sReturn;
	}
	
	/**
	 * merge 2 array
	 * @param - $arrImport: array input, $var: value, $id: index
	 * @return array merged
	 * */
	function addArray($arrImport, $var, $id)
	{
		$tempArray = array( $var => $id);
		$arrImport = array_merge($arrImport, $tempArray);
		return $arrImport;
	}
	
	/**
	* get list of data form config file
	* @param - $sFileName: config file name
	* 		 - $sName	 : field name
	* @return - array	**/
	function getConfigDataList($sFileName, $sFieldName)
	{
		$arrReturn = array();
	   	// read config file
	   	$lines = @file($sFileName);
	   	foreach ($lines as $line_num => $line)
    	{
    		$pos = strpos($line, $sFieldName);
    		if ($pos !== false)
    		{
    			$arrLine = explode("=",trim($line));
    			if (is_array($arrLine) && count($arrLine) == 2)
				{ 
					$data = $arrLine[1];
					if ($data)
					{
						$arrItem = explode(",",trim($data));
						if (is_array($arrItem) && count($arrItem) == 2)
						{
							$arrReturn[$arrItem[1]] = $arrItem[0];
						}
					}
				}
				
    		}
	    }
	    return $arrReturn;
	}
}
?>