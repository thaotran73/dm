<!--
Created on 2008/09/29
Author: Thao Tran
-->
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>Main - Menu</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="javascript">
	
	/*
	* init information page in the first time
	*/
	function loadPage()
	{
		var frm = document.frmLoadDB;
		frm.db_name.value = '<?echo addslashes($cbDatabase)?>';
		frm.db_username.value = '<?echo addslashes($db_username)?>';
		frm.db_password.value = '<?echo addslashes($db_password)?>';
		frm.status.value = '<?echo addslashes($status)?>';
		frm.status.disabled = true;
	}
	
	/**
	* reset data
	*/
	function resetInfo()
	{
		var frm = document.frmLoadDB;
		//type = 3: clear information
		frm.hidAction.value = '3';
		frm.action = '<?echo site_url('main')?>';
		frm.submit();
	}
	
	/*
	* get database info
	*/
	function getDBInfo()
	{
		var frm = document.frmLoadDB;
		var errmsg = '';
		if (isNull(frm.cbDatabase.value))
		{
			frm.db_name.value = '';
			frm.db_username.value = '';
			frm.db_password.value = '';
			frm.status.value = '';
			frm.cbDatabase.focus();
			errmsg = '<?echo str_replace ('xxx', 'DB Name', C_REQUIRED_STRING)?>';
			document.getElementById("warning").innerHTML = errmsg;
			return;
		}
		else
		{
			//type=1: display db info;
			frm.hidAction.value = '1';
			frm.action = '<?echo site_url('main')?>';
			frm.submit();
		}
	}
	
	/*
	* check connection
	*/
	function doConnect()
	{
		var frm = document.frmLoadDB;
		var errmsg = '';
		if (isNull(frm.db_name.value) || isNull(frm.db_username.value) || isNull(frm.db_password.value))
		{
			if (isNull(frm.db_name.value))
			{
				errmsg += '<?echo str_replace ('xxx', 'DB Name', C_REQUIRED_STRING)?><br>';
			}
			if (isNull(frm.db_username.value))
			{
				errmsg += '<?echo str_replace ('xxx', 'PG UserID', C_REQUIRED_STRING)?><br>';
			}
			if (isNull(frm.db_password.value))
			{
				errmsg += '<?echo str_replace ('xxx', 'PG Password', C_REQUIRED_STRING)?><br>';
			}
			document.getElementById("warning").innerHTML = errmsg;
			return;
		}
		else
		{
			//type = 2: check connection
			frm.hidAction.value = '2';
			frm.action = '<?echo site_url('main')?>';
			frm.submit();
		}
	}
	
	/*
	* go to next page
	*/
	function doNextPage(next_page)
	{
		var frm = document.frmLoadDB;
		<?if (trim($status) == C_CONNECT_SUCC)
		{?>
			if (frm.db_name.value=='<?echo addslashes($cbDatabase)?>' && frm.db_username.value=='<?echo addslashes($db_username)?>' && frm.db_password.value=='<?echo addslashes($db_password)?>')
			{
				top.location.href = next_page;
			}
			else
			{
				document.getElementById("warning").innerHTML = '<?echo C_CONNECT_ESTABLISHED?>';
			}
		<?
		}
		else
		{
			echo "document.getElementById(\"warning\").innerHTML = '".C_CONNECT_ESTABLISHED."';";
		}
		?>
	}

</script>
</head>

<body onload="loadPage();">

<div align="center">
	<table border="1" width="90%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="2" bordercolordark="#FFFFFF">
		<!--Header START-->
		<?
			$arrHierarchy = array(DM10_NAME => "");
			$this->functions->header(DM10_ID, $arrHierarchy);
		?>
		<!--Header END-->
		<!--Content START-->
		<tr>
			<td valign="top" bgcolor="#FFFFFF" class="project">
			<div align="center">
				<br>
				</div>
			<div align="center">
				<table border="1" width="70%" id="table7" cellspacing="0" cellpadding="0" bgcolor="#F2F2F2" bordercolorlight="#C0C0C0" bordercolordark="#FFFFFF">
					<tr>
						<td>
				<table border="0" width="100%" id="table8" cellspacing="0" cellpadding="2">
					<tr>
						<td><h1>Select data base</h1></td>
					</tr>
					<tr>
						<td>
						<div align="center">
							<?$attributes = array('id' => 'frmLoadDB', 'name' => 'frmLoadDB');
							$arrHidden = array('hidAction' => '');
							echo form_open(DM10_ROUTE, $attributes, $arrHidden)?>
							<table border="0" width="90%" id="table9" cellspacing="0" cellpadding="4">
								<tr>
									<td width="101">　</td>
									<td align="left">
									<span style='color:red' id="warning" name="warning"></span>
									</td>
								</tr>
								<tr>
									<td width="101">　</td>
									<td>
									<?echo form_dropdown('cbDatabase', $arrDB, $cbDatabase);?>
									&nbsp;&nbsp;
									<?
									$data = array(
											    'name' => 'btnDisplay',
											    'id' => 'btnDisplay',
											    'value' => '表示',
											    'type' => 'button',
											    'class' => 'button',
											    'onclick'  => 'getDBInfo()'
											);
									echo form_submit($data) . "&nbsp;&nbsp;";
									$data = array(
											    'name' => 'btnReset',
											    'id' => 'btnReset',
											    'value' => 'リセット',
											    'type' => 'button',
											    'class' => 'button',
											    'onclick'  => 'resetInfo()'
											);
									echo form_submit($data);
									?>
									</td>
								</tr>
								<tr>
									<td width="101">DB Name</td>
									<td>
									<?
									$data = array(
									              'name'        => 'db_name',
									              'id'          => 'db_name',
									              'value'       => '',
									              'maxlength'   => '32',
									              'size'        => '32',
									              'class'       => 'input'
									            );
									echo form_input($data);
									?>
									</td>
								</tr>
								<tr>
									<td width="101">PG UserID</td>
									<td>
									<?
									$data = array(
									              'name'        => 'db_username',
									              'id'          => 'db_username',
									              'value'       => '',
									              'maxlength'   => '32',
									              'size'        => '32',
									              'class'       => 'input'
									            );
									echo form_input($data);
									?>
									</td>
								</tr>
								<tr>
									<td width="101">PG Password</td>
									<td>
									<?
									$data = array(
									              'name'        => 'db_password',
									              'id'          => 'db_password',
									              'value'       => '',
									              'maxlength'   => '32',
									              'size'        => '32',
									              'type'        => 'password',
									              'class'       => 'input'
									            );
									echo form_input($data);
									?>
									</td>
								</tr>
								<tr>
									<td width="101">　</td>
									<td>
									<?
									$data = array(
											    'name' => 'btnConnect',
											    'id' => 'btnConnect',
											    'value' => '接続',
											    'type' => 'button',
											    'class' => 'button',
											    'onclick'  => 'doConnect()'
											);
									echo form_submit($data);
									?>
									</td>
								</tr>
								<tr>
									<td width="101">Status</td>
									<td>
									<?
									$data = array(
									              'name'        => 'status',
									              'id'          => 'status',
									              'value'       => '',
									              'maxlength'   => '32',
									              'size'        => '32',
									              'class'       => 'input',
									            );
									echo form_input($data);
									?>
									</td>
								</tr>
								<tr>
									<td colspan="2">　</td>
								</tr>
								<tr>
									<td colspan="2">
									<table border="0" width="100%" id="table10" cellspacing="0" cellpadding="2">
										<tr>
											<td width="141"><a href="javascript:doNextPage('<?echo DM20_ROUTE?>')">Upload/ Download</a>
											</td>
											<td><?echo DM20_ID?></td>
										</tr>
										<tr>
											<td width="141"><a href="javascript:doNextPage('<?echo DM30_ROUTE?>')">View/ Find/ Replace</a></td>
											<td><?echo DM30_ID?></td>
										</tr>
									</table>
									</td>
								</tr>
								<tr>
									<td colspan="2">　</td>
								</tr>
							</table>
							<?echo form_close();?>
						</div>
						</td>
					</tr>
					<tr>
						<td>
						<table border="0" width="100%" id="table11" cellspacing="0" cellpadding="2">
							<tr>
								<td width="159"><a href="<?echo DM41_ROUTE?>">File editor (Config file)</a></td>
								<td><?echo DM41_ID?></td>
							</tr>
							<tr>
								<td width="159"><a href="<?echo DM42_ROUTE?>">File editor (Access log)</a></td>
								<td><?echo DM42_ID?></td>
							</tr>
						</table>
						</td>
					</tr>
				</table>
						</td>
					</tr>
				</table>
			</div>
			</td>
		</tr>
		<!--Content END-->
		<!--Footer START-->
		<?echo $this->functions->footer();?>
		<!--Footer END-->
	</table>
</div>

</body>

</html>
