<!--
Created on 2008/09/25
Author: Thao Tran
-->
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>Login</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="javascript">
	var errmsg = "";
	var err_userempty = "<?=C_USERID_REQUIRED?>";	// error message for text fiel user id if value is null
	var err_passempty = "<?=C_PASSWORD_REQUIRED?>";	// error message for text fiel password if value is null
	/*
	* check for focus consor
	*/
	function checkEmptyLoginField(){
		if(document.frmLogin.s_user_id.value == ""){
			document.frmLogin.s_user_id.focus();
			errmsg = err_userempty;
			return false;
		}
		if(document.frmLogin.s_password.value == ""){
			document.frmLogin.s_password.focus();
			errmsg = err_passempty;
			return false;
		}
		
		return true;
	}
	
	/*
	* display error message
	*/
	function show_error_message(opt){
		obj_warning = document.getElementById("warning");
		if(opt==true){
			obj_warning.innerHTML  = "<table><tr><td style='color:red'>"+ errmsg +"<\/td><\/tr><\/table>";
		}else{
			obj_warning.innerHTML  = "";
		}
	}
	
	/*
	* validate data info
	*/
	function checkFormValidate(){
		if(checkEmptyLoginField()){
			clientTime = new Date();
			document.frmLogin.client_time.value = clientTime.getTime();
			return true;
		}else{
			show_error_message(true);
			return false;
		}
	}
	
	/*
	* init information page in the first time
	*/
	function loadPage(){
		<? if(isset($err_message)){ ?>
			errmsg = '<?=$err_message?>';
			show_error_message(true);
		<? } ?>
		
		<? if(isset($s_user_id)){ ?>
			document.frmLogin.s_user_id.value = '<?=$s_user_id?>';
			document.frmLogin.s_password.focus();
		<? }else{ ?>
			document.frmLogin.s_user_id.focus();
		<? } ?>
	}			
</script>
</head>

<body onload="loadPage()" topmargin="50px">

<div align="center">
	<table width="370">
		<tr>
			<td height="50">　</td>
		</tr>
		<tr>
			<td >
				<table border="1" width="100%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="2" bordercolordark="#FFFFFF">
					<tr>
						<td class="menu">DB Manager</td>
					</tr>
					<?$attributes = array('id' => 'frmLogin', 'name' => 'frmLogin', 'onsubmit' => 'return checkFormValidate()');
					$hidden = array('client_time' => '');
					echo form_open(DM00_ROUTE, $attributes, $hidden)?>
					<tr>
						<td class="login" height="220" width="0" valign="top">
						<div align="center">
						<table border="0" width="95%" id="table2" cellspacing="0" cellpadding="4">
							<tr>
								<td colspan="2" height="20"></td>
							</tr>
							<tr>
								<td colspan="2" width="100%" align="center"><span id="warning" name="warning"></span></td>
							</tr>
							<tr>
								<td width="24%">ユーザID</td>
								<td width="72%">
								<?
								$data = array(
								              'name'        => 's_user_id',
								              'id'          => 's_user_id',
								              'value'       => '',
								              'maxlength'   => '10',
								              'size'        => '20',
								              'class'       => 'text_disable',
								              'onpaste'     => 'return false',
								              'onkeypress'  => 'inputAlphaNumericOnly(event)',
								              'autocomplete'=> 'off'
								            );
								echo form_input($data);
								?>
								</td>
							</tr>
							<tr>
								<td width="24%">パスワード</td>
								<td width="72%">
								<?
								$data = array(
								              'name'        => 's_password',
								              'id'          => 's_password',
								              'value'       => '',
								              'maxlength'   => '10',
								              'size'        => '20',
								              'class'       => 'text_disable',
								              'onpaste'     => 'return false',
								              'onkeypress'  => 'inputAlphaNumericOnly(event)',
								              'autocomplete'=> 'off'
								            );
								echo form_password($data)
								?>
								</td>
							</tr>
							<tr>
								<td width="24%">　</td>
								<td width="72%">
								<?
								echo form_submit('btnRegister', 'ログイン', 'class="button"');
								$data = array(
											    'name' => 'btnReset',
											    'id' => 'btnReset',
											    'value' => 'リセット',
											    'type' => 'reset',
											    'class' => 'button'
											);
								echo form_submit($data);
								?>
							</tr>
						</table>
						</div>
						</td>
					</tr>
					<?echo form_close()?>
				</table>
			</td>
		</tr>
	</table>
</div>

</body>

</html>