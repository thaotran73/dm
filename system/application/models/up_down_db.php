<?php
/**
 * Date modified : 2008/10/01
 * Process import and export between files and database
 */
require_once(C_LIB_PATH.'constant.inc.php');

class Up_down_db extends Model 
{
	
	function __construct()
	{
        // Call the Model constructor
        parent::Model();     
    }
    
	/**
	 * Update a file
	 * @param: $strPathSaveTo: destination path, $file: file
	 * @return: string of path
	 * Date modified : 2008/10/01
	 **/
	function uploadFile($strPathSaveTo, $file)
	{
		if ($file['name'])
		{
			if (!file_exists($strPathSaveTo))
			{
				$this->utils->makeDir($strPathSaveTo, '/');
			}
			$strPath = $strPathSaveTo . $file['name'];
			if (is_file($strPath))
			{
				unlink($strPath);
			} 
			if (!is_file($strPath))
			{ 
				copy($file['tmp_name'], $strPath);	
			}
			return $strPath;
		}
	}
	
	/**
	 * Delete data of table
	 * @param: $conn: connect to database   
	 * 		   $table_name: name of table is deleted
	 * Date modified : 2008/10/01
	 **/
	function clearTable($current_db, $table_name)
	{
		
		if ($table_name!=C_TB_USERS)
		{
			$sQuery = "DELETE FROM $table_name";
		}
		else
		{
			$sQuery = "DELETE FROM $table_name WHERE s_user_id!='".C_ADMIN."'";
		}
		$current_db->query($sQuery);
	}
	
	/**
	 * Export data into csv excel
	 * @param : filename, table name
	 * @return : file name
	 * Date modified : 2008/10/01
	 **/
	function exportCSV($current_db, $table_name, $filename, $delimiter)
	{		
		$conn = $this->dmdb->getConn($current_db);
		
		$arrFields = $this->dmdb->getFieldName($current_db, $table_name);
		
		$encoding = $this->utils->getEncoding($conn);
		pg_set_client_encoding($conn, C_ENCODING);
		
		$sReturn = "";
		$csv_output = "";
		$intFields = count($arrFields);
		$strFieldName = implode(",", $arrFields);
		
		$sQuery = "SELECT $strFieldName FROM $table_name";
		$result = $current_db->query($sQuery);
		//restore encoding for client
		pg_set_client_encoding($conn, $encoding);
		$arrData = $result->result();
		$iCount = count($arrData);
		
		if ($iCount != 0)
		{
        	foreach($arrData as $data)
        	{
				$sTmp = "";
				for($i = 0; $i < $intFields; $i++)
				{
					$sFieldName = $arrFields[$i];
					$value = $data->$sFieldName;
					//convert comma to special char for import with comma delimiter
					if ($delimiter == ',')
					{
						$value = str_replace(SEPERATOR, SEPERATOR_REPLACE, $value);
					}
					if ($i != 0)
					{
						$sTmp .= $delimiter . $this->fieldClean(trim($value));
					}
					else
					{
						$sTmp .= $this->fieldClean(trim($value));
					}
				}
				$begin_char = '';
				if ($delimiter == '","')
				{
					$begin_char = '"';
				}
				$csv_output .=  $begin_char . $sTmp . $begin_char . "\r\n";
            }
        }
        
		//write data from a table to csv file
		$filename = C_EXPORT_FOLDER.$filename . "_" . date("Ymdhis") . ".csv";
		
		if (!file_exists(C_EXPORT_FOLDER))
		{
			mkdir (C_EXPORT_FOLDER, 0777);
		}
		
		if ($fp = fopen($filename, 'a')) 
		{
			fwrite($fp, $csv_output);
			fclose($fp);
			$sReturn = $filename;
		}
			
		return $sReturn;
	}
	
	/**
	 * Formats a value or expression for sql purposes
	 * @param $type The type of the field
	 * @param $format VALUE or EXPRESSION
	 * @param $value The actual value entered in the field.  Can be NULL
	 * @return The suitably quoted and escaped value.
	 */
	function formatValue($arrFieldType, $fieldName, $value, $delimiter) 
	{
		$type = 'text';
		foreach ($arrFieldType as $fName => $fType)
		{
			if ($fName == $fieldName)
			{
				$type = $fType;
				break;
			}
		}
		
		switch ($type) 
		{
			case 'bool':
			case 'boolean':
				if ($value == 't')
				{
					return TRUE;
				}
				elseif ($value == 'f')
				{
					return FALSE;
				}
				elseif (trim($value) == '')
				{
					return NULL;
				}
				else
				{
					return $value;
				}
				break;		
			default:
				// Checking variable fields is difficult as there might be a size
				// attribute...	
				if (strpos($type, 'time') === 0) 
				{
					// Assume it's one of the time types...
					if ($this->utils->isNull($value))
					{ 
						return NULL;
					}
					elseif (strcasecmp($value, 'CURRENT_TIMESTAMP') == 0 
							|| strcasecmp($value, 'CURRENT_TIME') == 0
							|| strcasecmp($value, 'CURRENT_DATE') == 0
							|| strcasecmp($value, 'LOCALTIME') == 0
							|| strcasecmp($value, 'LOCALTIMESTAMP') == 0) 
					{
						return "{$value}";
					}
					return "{$value}";
				}
				else if (strpos($type, 'int') === 0 || strpos($type, 'float') === 0) 
				{
					if (trim($value) == '')
					{ 
						return NULL;
					}
					return $value;
				}
				else 
				{
					if ($delimiter==',' && ($type == 'varchar' || $type == 'text'))
					{
						$value = str_replace(SEPERATOR_REPLACE, SEPERATOR, $value);
					}
					if ($value)
					{
						$value = str_replace('""', '"', $value);
					}
					return "{$value}";
				}
		}
	}
	
	/**
	 * get data from csv file with format file is comma
	 * @param: $sFileName - file name string
	 * 		   $arrFieldName: field name array
	 * 		   $sSkipLine: where line is start
	 * 		   $csv_max_line: length of file
	 * @return: array data
	 * */
	function getDataFromCSVFile($sFileName, $arrFieldName, $csv_max_line, $delimiter)
	{
		$intFields = count($arrFieldName);
		$arrReturn = array();
		$arrTmp = array();
		$strTmp = "";
		$iCount = 0;
		$handle = fopen($sFileName, 'r');
		if ($delimiter==",")
		{
			//for comma delimiter
			while (!feof($handle)) 
			{
				$buffer = fgets($handle, $csv_max_line);
				$data = explode($delimiter, $buffer);
				$this->detectData($data, $intFields, $iCount, $strTmp, $arrTmp, $arrReturn, '');
			}
		}
		else
		{
			//for comma + double quotation delimiter
			while ($data = fgetcsv($handle, $csv_max_line, ",")) 
			{
				$this->detectData($data, $intFields, $iCount, $strTmp, $arrTmp, $arrReturn, '\n');
			}
		}
		return $arrReturn;
		fclose ($handle);
	}
	
	/**
	 * detect data of a record
	 * @param:  $data - a line data
	 * 			$intFields - num of fields
	 * 			$iCount - count for compare with num of fields
	 * 			$strTmp, $arrTmp, $arrReturn - temp values
	 * 			$flash - none with comma and "\n" with comma and double quotation
	 * **/
	function detectData($data, $intFields, &$iCount, &$strTmp='', &$arrTmp, &$arrReturn, $flash)
	{
		//check num of fields in a line
		if (count($data)<$intFields)
		{
			if ($iCount==0)
			{
				 $strTmp = implode(",", $data);
				$iCount++;
			}
			else
			{
				$strTmp .= $flash . implode(",", $data);
				$arrTmp = explode(",", $strTmp);
				if (count($arrTmp)==$intFields)
				{
					$iCount = 0;
					$strTmp = "";
					$data = $arrTmp;
					unset($arrTmp);
				}
			}
		}
		//check num of fields of record
		if ($iCount==0 && count($data)==$intFields)
		{
			$arrReturn[] = $data;
		}
	}
	
	/**
	 * get field type from list of field name
	 * @param	$current_db - database name
	 * 			$sTableName - table name
	 * 			$arrFieldName - list of field name
	 * @return array field name
	 * **/
	function getFieldType($current_db, $sTableName, $arrFieldName)
	{
		$arrFieldType = array();
		for($i=0;$i<count($arrFieldName); $i++)
		{
			$arrFieldType[$arrFieldName[$i]] = $this->dmdb->getFieldType($current_db, $sTableName, $arrFieldName[$i]);
		}
		return $arrFieldType;
	}

	/**
	 * Does an import to a particular table from a text file
	 * @param: $oCondition: OptType: 1: clear table and new data; 2: Overwrite; 3:Never overwrite, SkipLine: Numner of lines to skipped
	 * @return: array result (succ, and error)
	 * Date modified : 2008/10/13
	 * **/
	function importCSV($current_db, $arrReturn, $sTableName, $sFileName, $oCondition, $error_file, $delimiter)
	{
		$conn = $this->dmdb->getConn($current_db); 
		pg_set_client_encoding($conn, C_ENCODING);
		$encoding = $this->utils->getEncoding($conn);
		//get length of a file
		if (file_exists($sFileName))
		{
		     $iLength = 1;
		     $array = file($sFileName);
			 $intTotal = count($array);
		     for($i=0; $i<$intTotal; $i++)
		     {
		     	if ($iLength < strlen($array[$i]))
		        {
		        	$iLength = strlen($array[$i]);
				}
			}
		    unset($array);
		}
		$succ  = 0;
		$error = 0;
		$sOptType  = $oCondition->OptType;
		$sSkipLine = $oCondition->SkipLine;
		$strError = "";

		if (!file_exists(C_DB_PATH))
		{
			$this->utils->makeDir(C_DB_PATH, '/');
		}
		
		$strError .= "* " . $sTableName . str_replace ('xxx', substr($sFileName, strrpos($sFileName, "/")+1), C_IMPORT_TABLE_START) . "\r\n";
				
		if ($sOptType==1)
		{
			//delete old data
			$arrKeyName = array();
			$arrKeyData = array();
			if ($sTableName==C_TB_USERS)
			{
				//changed by Thao Tran on 2009/08/26 START
				$arrKeyName = array('s_user_id !=');
				//changed by Thao Tran on 2009/08/26 END
				$arrKeyData = array(C_ADMIN);
			}
			else
			{
				$arrKeyName = array('1');
				$arrKeyData = array(1);
			}
			$result = $this->deleteRow($current_db, $sTableName, $arrKeyName, $arrKeyData);
			if ($this->utils->isNull($result))
			{
				$strError .= "- " . date("Y/m/d H:i:s") . ": " . C_DELETE_FILE_ERROR . ".\r\n";
			}
		}

		//get num of fields and name of fields from db if SkipLine=0
		$intFields = $this->dmdb->numFields($current_db, $sTableName);
		$iLength    = $iLength + $intFields;
		$arrFieldName = $this->dmdb->getFieldName($current_db, $sTableName);
		$arrFieldType = $this->getFieldType($current_db, $sTableName, $arrFieldName);
		//get primary key for TableName
		$arrKeyName = array();
		$this->dmdb->getPrimaryKey($current_db, $sTableName, $arrKeyName);
		$arrKeyData = array();
		
		// XXX: Length of CSV lines limited to 100k
		$csv_max_line = $iLength;
		// Set delimiter to tabs or commas
		$delimiter = $delimiter?$delimiter:',';
		// Get first line of field names
		$fields = $this->dmdb->getFieldName($current_db, $sTableName);
		//get and detect data from csv file
		$data = $this->getDataFromCSVFile($sFileName, $arrFieldName, $csv_max_line, $delimiter);
		$iLine = 1;//We start on the line
		foreach ($data as $key => $line)
		{
			//check start line
			if ($iLine*1 > $sSkipLine*1)
			{
				// Build value map
				$vars = array();
				$arrKeyData = array();
				$i = 0;
				foreach ($fields as $fieldName) 
				{
					// Check that there is a column
					if (!isset($line[$i])) 
					{
						$strError .= "- " . date("Y/m/d H:i:s") . ": ". ($iLine) . C_ERROR_UP . "\r\n";
						exit;
					}
					$value = $this->formatValue($arrFieldType, $fieldName, $line[$i], $delimiter);
					// Add to value array
					$vars[$fieldName] = $value;
					//check and get key value for update case
					if (in_array($fieldName, $arrKeyName))
					{
						$arrKeyData[] = $value;
					}
					$i++;
				}
				$result = $this->insertRow($current_db, $sTableName, $vars);
				
				if (!$this->utils->isNull($result))
				{
					$succ++;
				}
				else
				{
					//append record, never overwrite
					if ($sOptType==1 || $sOptType==3)
					{
						$strError .= "- " . date("Y/m/d H:i:s") . ": ". ($iLine) . C_ERROR_UP . "\r\n";
						$error++;
					//add and overwrite
					}
					else if ($sOptType==2)
					{
						$result = $this->updateRow($current_db, $sTableName, $vars, $arrKeyName, $arrKeyData);
						
						if (!$this->utils->isNull($result))
						{
							$succ++;
						}
						else
						{
							$strError .= "- " . date("Y/m/d H:i:s") . ": ". ($iLine) . C_ERROR_UP . "\r\n" . $result . "\r\n";
							$error++;
						}
					}
				}
			}
			$iLine++;
		}
		if ($iLine>1 && $succ==0 && $error==0)
		{
			$strError .= "- " . date("Y/m/d H:i:s") . ": ". ($iLine-1) . C_ERROR_UP . "\r\n" . C_FIELD_NOT_CORRESPONDING . "\r\n";
			$error++;
		}

		$strError .= "* " . $sTableName . str_replace ('yyy', $error, str_replace ('xxx', $succ, C_IMPORT_TABLE_END)) . "\r\n";
				
		//write log START
		if ($flog = fopen($error_file, 'a')) 
		{
			fwrite($flog, $strError);
		}
		fclose($flog);
		//write log END 
		$tempArray = array($sTableName . ";" . $succ . ";" . $error);
		$arrReturn = array_merge ($arrReturn, $tempArray);
		//restore encoding for client
		pg_set_client_encoding($conn, $encoding);
		return $arrReturn;
	}
	
	/**
	 * write log for upload tables
	 * @param $error_file error file
	 * @param $strError error string
	 * @return true if ok else false
	 * **/
	function writeErrorLog($error_file, $strError)
	{
		if (file_exists($error_file)) 
		{
			if ($flog = fopen($error_file, 'a')) 
			{
				fwrite($flog, $strError);
			}
			fclose($flog);
			return true;
		}
		return false;
	}
	
	/**
	 * Adds a new row to a table
	 * @param $current_db current database
	 * @param $table The table in which to insert
	 * @param $var An array mapping new values for the row
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function insertRow($current_db, $table, $vars) 
	{
		$current_db->set($vars);
		return $current_db->insert($table, $vars);
	}
	
	/**
	 * select a row from a table
	 * @param $current_db current database
	 * @param $table The table in which to insert
	 * @param $arrConKey, $arrConValue array condition
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function selectRow($current_db, $table_name, $arrConKey, $arrConValue)
	{
		if (!is_array($arrConKey) || !is_array($arrConValue))
		{
			return -1;
		}
		else
		{
			for ($i=0; $i<count($arrConKey); $i++)
			{
				$current_db->where($arrConKey[$i], $arrConValue[$i], 'AND ');
			}
			return $current_db->getwhere($table_name);
		}
	}
	
	/**
	 * update a row to a table
	 * @param $current_db current database
	 * @param $table The table in which to insert
	 * @param $var An array mapping new values for the row
	 * @param $arrConKey, $arrConValue array condition
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function updateRow($current_db, $table_name, $vars, $arrConKey, $arrConValue)
	{
		if (!is_array($vars) || !is_array($arrConKey) || !is_array($arrConValue))
		{
			return -1;
		}
		else
		{
			for ($i=0; $i<count($arrConKey); $i++)
			{
				$current_db->where($arrConKey[$i], $arrConValue[$i], 'AND ');
			}
			return $current_db->update($table_name, $vars);
		}
	}
	
	/**
	 * delete a row to a table
	 * @param $current_db current database
	 * @param $table The table in which to insert
	 * @param $var An array mapping new values for the row
	 * @param $arrConKey, $arrConValue array condition
	 * @return 0 success
	 * @return -1 invalid parameters
	 */
	function deleteRow($current_db, $table_name, $arrConKey, $arrConValue)
	{
		if (!is_array($arrConKey) || !is_array($arrConValue))
		{
			return -1;
		}
		else
		{
			for ($i=0; $i<count($arrConKey); $i++)
			{
				$current_db->where($arrConKey[$i], $arrConValue[$i], 'AND ');
			}
			return $current_db->delete($table_name);
		}
	}
	
	/**
	 * Cleans (escapes) an object name (eg. table, field)
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function fieldClean(&$str) {
		if ($str === null)
		{
			return null;
		}
		$str = str_replace('"', '""', $str);
		return $str;
	}
	
	/**
	 * Cleans (escapes) a string
	 * @param $str The string to clean, by reference
	 * @return The cleaned string
	 */
	function clean(&$str) {
		if ($str === null)
		{
			return null;
		}
		$str = str_replace("\r\n","\n",$str);
		if (function_exists('pg_escape_string'))
		{
			$str = pg_escape_string($str);
		}
		else
		{
			$str = addslashes($str);
		}
		return $str;
	}
}
?>
