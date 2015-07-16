<?php
/**
 * Created on 2008/09/26
 * Author : Thao Tran
 * Process get info of users and manipulate with session
 */
class Login_db extends Model {
	function __construct()
	{
        /** Call the Model constructor */
        parent::Model();
    }
    
    /**
	 * check ip address of pc with other ip addresses
	 * @param: $ip_mask is array ip addresses
	 * @returm: true if ok; else false
	 **/
	function checkIPAdress($ip_mask){
		$bReturn = false;
		$arrIP_Mask = explode(',', $ip_mask);
	
		foreach($arrIP_Mask as $idx=>$value){
			if ($this->checkIPAdress_Sub($value)) $bReturn = true;
		}
	
		return $bReturn;
	}
	
	/**
	 * check sub mask
	 * @param: $sValue: ip address check
	 * @returm: true if ok; else false
	 **/
	function checkIPAdress_Sub($sValue){
		$bFlag = true;
		$arrValue = explode('.', $sValue);
		$arrDomain = explode('.', C_REMOTE_ADDR);
	
		foreach($arrValue as $idx1=>$Value1){
			if ($Value1 != $arrDomain[$idx1] && $Value1 != "*"){
	
				$bFlag = false;
				break;
			}
		}
		return $bFlag;
	}
	
	/**
	 * check username and password login
	 * @param: cond condition (username, password)
	 * @return: true if ok else not ok
	 * **/
	function checkUserLogin($cond)
	{
		$s_user_id = $cond->s_user_id;
		$s_password = $cond->s_password;
		if (DM_USER == $s_user_id && DM_PASS == base64_encode($s_password))
		{
			return true;
		}
		return false;
	}
}
?>
