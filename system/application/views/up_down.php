<!--
created on 2008/10/01
Author: Thao Tran
-->
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>Upload/ Download tables</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="javascript">
	function Result(){
		<?
		/** Load error log or result */
		if(isset($arrReturn)){
			$intResult = count($arrReturn);
			if ($intResult>0){
				for ($i=0; $i<$intResult; $i++){
					$strResult = $arrReturn[$i];
					list($TableName, $succ, $error) = explode(";", $strResult);
					if (!$this->utils->isNull($TableName)){
						$idx = $TableName;
						$sucName = "suc_" . $idx;
						$errName = "err_" . $idx;
					?>
						var objSuc = document.getElementById('<?=$sucName?>');
						var objErr = document.getElementById('<?=$errName?>');
						objSuc.innerHTML = '<?=$succ?>';
						objErr.innerHTML = '<?=$error?>';
					<?
					}
				}
			}
		}
		?>
	}
	
	/**
 	*  load data when web page is called first 
	*  @param: frm: form is executed
 	**/
	function formLoad(frm){
		Result();
		var obj_warning = document.getElementById("warning");
		obj_warning.innerHTML = '';
		cboSelect(frm.SkipLine,'<?=(isset($_POST['SkipLine'])?$_POST['SkipLine']:0)?>');
		optSelect(frm.OptType,'<?=(isset($_POST['OptType'])?$_POST['OptType']:1)?>');
		optSelect(frm.delimiter,'<?=(isset($_POST['delimiter'])?$_POST['delimiter']:1)?>');
	}
	
	/**
 	*  set value for select object 
	*  @param: frm: form is executed
 	**/
 	function cboSelect(frm, val){
 		frm.selectedIndex = val;
 	}
 	
 	/**
 	*  set check for checkbox objects that have a same group 
	*  @param: frm: form is executed
 	**/
 	function optSelect(frm, val){
 		frm[eval(val-1)].checked = true;
 	}
 	
	/**
	 *  export data   
	 *  @param: frm: form is executed
	 *			table_name: data of this table is exported 
	 **/
	function doExport(frm, table_name){
		frm.table_name.value = table_name;
		frm.act.value="export";
		frm.submit();
	}
	
	/**
	 *  clear data of table  
	 *  @param: frm: form is executed
	 *			table_name: data of this table is deleted 
	 **/
	function doClearTable(frm, table_name){
		if (confirm("<?echo C_CLEAR_TABLE?>")){
			frm.table_name.value = table_name;
			frm.act.value="clear_table";
			frm.submit();
		}
	}
	
	/**
	 *  checkbox control is checked or not checked  
	 *  @param: FieldFile: name of file control
	 *          FieldCheck: name of checkbox control
	 **/
	function changeValue(FieldFile, FieldCheck){
		if (FieldFile.value!="") FieldCheck.checked = true;
		else FieldCheck.checked = false;
	}
	
	/**
	 *  check inputted data 
	 *  @param: frm: form is executed
	 *  @return: false if inputted data is invalid
	 **/
	function checkSubmit(frm){
		var sName = '';
		var iCount = 0;
		var obj_warning = document.getElementById("warning");
		var required_string = '<?echo C_REQUIRED_STRING?>';
		var required_check = '<?echo C_REQUIRED_CHECK_UPLOAD?>';
		for (var i=0;i<frm.elements.length;i++){
			var e=frm.elements[i];
			sName = e.name.substr(e.name.indexOf("_")+1);
			//check checkbox is checked yet?
			if (e.checked && e.type=="checkbox" && e.name!="chkAll")
			{
				iCount++;
				if (eval('frm.file_'+sName).value=="")
				{
					obj_warning.innerHTML = "<table><tr><td style='color:red'>"+required_string.replace('xxx', sName)+"<\/td><\/tr><\/table>";
					return false;
					break;
				}
			}
			//check select a file?
			else if (e.type=='file' && e.value!="")
			{
				if (eval('frm.file_'+sName).value!="")
				{
					if (!eval('frm.chk_'+sName).checked)
					{
						eval('frm.chk_'+sName).checked = true;
						obj_warning.innerHTML = "<table><tr><td style='color:red'>"+required_check.replace('xxx', sName)+"<\/td><\/tr><\/table>";
						return false;
						break;
					}
				}
			}
		}
		if (iCount==0)
		{
			obj_warning.innerHTML = "<table><tr><td style='color:red'><?echo C_SELECT_FILE?><\/td><\/tr><\/table>";
			return false;
		}
		frm.act.value = "upload";
	    frm.submit();
	}
	
	/**
	 *  open log file to view  
	 *  @param: frm: form is executed
	 **/
	function viewLog(frm){
		<?
		$error_file = C_DB_PATH.C_ERROR_FILE;
		if (file_exists($error_file)){?>
			var vWinCal = window.open ('<?=site_url("error_msg_log")?>', 'ErrorLog', 'menubar=no,scrollbars=yes,status=yes,top=0,left=0,width=500,height=600');
			vWinCal.opener = self;
			vWinCal.focus();
		<?}?>
	}
	
	//process function when click on paging control
	function process_page(index)
	{
		var frm = document.frmImport;
		frm.page.value = index;
		frm.submit();
	}
</script>
</head>

<body onload="formLoad(document.frmImport)">

<div align="center">
	<table border="1" width="90%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="2" bordercolordark="#FFFFFF">
		<!--Header START-->
		<?
			$arrHierarchy = array(DM10_NAME => DM10_ROUTE, DM20_NAME => "");
			$this->functions->header(DM20_ID, $arrHierarchy);
		?>
		<!--Header END-->
		<!--Content START-->
		<tr>
			<td valign="top" bgcolor="#FFFFFF" class="project">
			<div align="center">
			<?
			$attributes = array('id' => 'frmImport', 'name' => 'frmImport');
			$arrHidden = array('act' => '', 'table_name' => '', 'page' => $page);
			echo form_open_multipart(DM20_ROUTE, $attributes, $arrHidden)?>
				<table border="0" width="98%" id="table2" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="2">　</td>
					</tr>
					<tr>
						<td colspan="2"><h1>Upload/ Download tables</h1></td>
					</tr>
					<tr>
						<td width="99%" colspan="2">
						　</td>
					</tr>
					<tr>
						<td width="7%">
						　</td>
						<td width="92%">
						<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="4">
							<tr>
								<td width="3%" align="center">
								<?
								$data = array(
											    'name' => 'OptType',
											    'id' => 'OptType',
											    'value' => 1,
											    'checked'  => true
											);
								echo form_radio($data)?>
								</td>
								<td width="96%">全てクリア後、新規書き込み</td>
							</tr>
							<tr>
								<td width="3%" align="center">
								<?
								$data = array(
											    'name' => 'OptType',
											    'id' => 'OptType',
											    'value' => 2,
											    'checked'  => false
											);
								echo form_radio($data)
								?>
								</td>
								<td width="96%">上書および追加</td>
							</tr>
							<tr>
								<td width="3%" align="center">
								<?
								$data = array(
											    'name' => 'OptType',
											    'id' => 'OptType',
											    'value' => 3,
											    'checked'  => false
											);
								echo form_radio($data)
								?></td>
								<td width="96%">既存データに追加（上書き不可）</td>
							</tr>
							<tr>
								<td colspan="2">
								Field nameに使用する列の数&nbsp;&nbsp;&nbsp; 
								<?echo form_dropdown('SkipLine', $arrSkipLine, $SkipLine);?></td>
								</tr>
							<tr>
								<td width="99%" colspan="2">
								<table border="0" id="table8" cellspacing="0" cellpadding="2" width="100%">
									<tr>
										<td width="82">区切り文字:</td>
										<td width="20">
										<?
										$data = array(
													    'name' => 'delimiter',
													    'id' => 'delimiter',
													    'value' => 1,
													    'checked'  => true
													);
										echo form_radio($data)
										?>
										</td>
										<td width="196">comma + double quotation</td>
										<td width="20">
										<?
										$data = array(
													    'name' => 'delimiter',
													    'id' => 'delimiter',
													    'value' => 2,
													    'checked'  => false
													);
										echo form_radio($data)
										?>
										</td>
										<td>Comma</td>
									</tr>
								</table>
								</td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="7%">
						　</td>
						<td width="92%">
						　</td>
					</tr>
					<tr>
						<td width="7%">
						　</td>
						<td width="92%">
						<?
						$data = array(
								    'name' => 'btnLog',
								    'id' => 'btnLog',
								    'value' => 'Log表示',
								    'type' => 'button',
								    'class' => 'button',
								    'onclick'  => 'viewLog(document.frmImport)'
								);
						echo form_submit($data);
						?>&nbsp;&nbsp;
						<?
						$data = array(
								    'name' => 'btnUpload',
								    'id' => 'btnUpload',
								    'value' => 'Upload実行',
								    'type' => 'button',
								    'class' => 'button',
								    'onclick'  => 'checkSubmit(document.frmImport)'
								);
						echo form_submit($data);
						?>
						</td>
					</tr>
					<tr>
						<td width="99%" colspan="2" height="15"></td>
					</tr>
					<tr>
						<td width="99%" colspan="2" align="center">
							<table border="0" width="95%" cellspacing="0" cellpadding="0">
								<tr>
									<td class="div.pagination"><?echo $pagination?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="99%" colspan="2" align="center">
						<table border="0" width="95%" cellspacing="0" cellpadding="2">
							<tr>
								<td><span id="warning" name="warning"></span></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td width="99%" colspan="2" align="center">
						<table border="1" width="95%" id="table9" bordercolorlight="#808080" bordercolordark="#FFFFFF" cellspacing="0" cellpadding="2">
							<tr>
								<td class="menu_table" width="3%">
								<?
								$data = array(
								    'name'        => 'chkAll',
								    'id'          => 'chkAll',
								    'value'       => '',
								    'onclick'	  => 'checkAll(document.frmImport, document.frmImport.chkAll)',
								    'checked'     => false
								    );
								echo form_checkbox($data);
								?>
								</td>
								<td class="menu_table" width="25%" >Table Name</td>
								<td class="menu_table" width="35%" >File name</td>
								<td class="menu_table" width="8%" >成功</td>
								<td class="menu_table" width="9%" >エラー件数</td>
								<td class="menu_table" width="10%" >Down load</td>
								<td class="menu_table" width="10%" >Table Clear</td>
							</tr>
							<?$i=0;
							foreach ($arrTable as $idx => $val)
							{
								$tablename = $val;
								if ($i%2==0)
								{
									$class = "odditem";
								}
								else
								{
									$class = "evenitem";
								}
							?>
							<tr>
								<td align="center" class="<?echo $class?>">
								<?
								$data = array(
								    'name'        => 'chk_'.$tablename,
								    'id'          => 'chk_'.$tablename,
								    'value'       => $tablename,
								    'checked'     => false
								    );
								echo form_checkbox($data);
								?>
								</td>
								<td class="<?echo $class?>"><?echo $tablename?></td>
								<td class="<?echo $class?>">
								<input type="file" name="file_<?=$tablename?>" style="width:98%" onpaste="javascript:return false;" onkeydown="javascript:return false;" onkeyup="changeValue(document.frmImport.file_<?=$tablename?>, document.frmImport.chk_<?=$tablename?>)" onchange="changeValue(document.frmImport.file_<?=$tablename?>, document.frmImport.chk_<?=$tablename?>)"></td>
								<td class="<?echo $class?>" style="color:green"><div align="center"><label id="suc_<?=$tablename?>"></label>&nbsp;</div></td>
								<td class="<?echo $class?>" style="color:red"><div align="center"><label id="err_<?=$tablename?>"></label>&nbsp;</div></td>
								<td class="<?echo $class?>"><div align="center"><a href="javascript:doExport(document.frmImport, '<?=$tablename?>')">実行</a></div></td>
								<td class="<?echo $class?>"><div align="center"><a href="javascript:doClearTable(document.frmImport, '<?=$tablename?>')">実行</a></div></td>
							</tr>
							<?
							$i++;
							}
							?>
						</table>
						</td>
					</tr>
					<tr>
						<td width="99%" colspan="2" height="15"></td>
					</tr>
					<tr>
						<td width="99%" colspan="2" align="center">
							<table border="0" width="95%" cellspacing="0" cellpadding="0">
								<tr>
									<td class="div.pagination"><?echo $pagination?></td>
								</tr>
							</table>
						</td>
					</tr>
					</table>
				<?echo form_close();?>
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
