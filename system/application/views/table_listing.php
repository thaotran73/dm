<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>List of Tables</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="JavaScript">
	function formLoad()
	{
		trackInput();
		<?
			if ($hdAction=='find' || $hdAction=='replace')
			{
				//information for result replace
				if(isset($resultReplace) && $hdAction=='replace' && count($resultReplace)>0)
				{
					$resultMsg = "<div><b>Replace Result:</b></div>";
					for($i=0; $i<count($resultReplace); $i++)
					{
						if($resultReplace[$i]->result)
						{ // success
							$resultMsg .= "<div style=\'padding-left:20px;\'>".str_replace("xxx", $resultReplace[$i]->table, UPDATE_SUCC)."</div>";
						}
						else
						{ // failed
							$resultMsg .= "<div style=\'padding-left:20px;\'>".str_replace("xxx", $resultReplace[$i]->table, UPDATE_ERROR)."</div>";
						}
					}
					echo "showMessage('{$resultMsg}', 3);";
				}
				//information for result found
				else if(isset($resultFound) && $hdAction=='find')
				{
					$resultMsg = "";
					if(count($resultFound) == 0)
					{
						$resultMsg = str_replace("xxx", addslashes($txtFindText), CANNOT_FIND_TABLE);
						echo "showMessage('{$resultMsg}', 1);";
					}
					else
					{
						$resultMsg .= "<div><b>Search Result:</b></div>";
						for($i=0; $i<count($resultFound); $i++)
						{
							$resultMsg .= "<div style=\'padding-left:20px;\'>".($i+1).". ".$resultFound[$i]."</div>";
						}
						echo "showMessage('{$resultMsg}', 3);";
					}
				}
			}
		?>
	}
	
	/**
	 * Traking when user input data.
	 * If have value : enable action button
	 * Otherwise : disable
	 */
	function trackInput()
	{
		var frm = document.frmTables;
		var txtFindText = frm.txtFindText;
		if(txtFindText.value != "")
		{
			frm.btnReplace.disabled = false;
		}
		else
		{
			frm.btnReplace.disabled = true;
		}
	}
	
	/**
	 * Find all tables that contain any field have data match with input text
	 */
	function findTable()
	{
		var frm = document.frmTables;	
		// Check "Find what" input value
		var txtFindText = frm.txtFindText;
		if(txtFindText.value == "")
		{
			txtFindText.focus();
			showMessage('<?=FIND_TEXT_REQUIRED?>', 2);
			return false;
		}
		
		frm.hdAction.value = "find";
		frm.submit();
	}
	
	/**
	 * Check input data before replace text processing
	 */
	function checkInput()
	{
		var frm = document.frmTables;
		// Check "Find what" input value
		var txtFindText = frm.txtFindText;
		var txtNewText = frm.txtNewText;
		if(txtFindText.value == "")
		{
			txtFindText.focus();
			showMessage('<?=FIND_TEXT_REQUIRED?>', 2);
			return false;
		}
		if(isNull(txtNewText.value))
		{
			if (!confirm("<?echo REPLACE_TEXT_RECOMMEND?>"))
			{
				return false;
			}
		}
		// Check tables haven't been selected yet
		var hasChecked = false;
		for (var i=0;i<frm.elements.length;i++)
		{
			var e=frm.elements[i];
			sName = e.name;
			if (sName.indexOf("chkTable_")!=-1 && e.checked)
			{
				hasChecked = true;
				break;
			}
		}
			
		if(hasChecked == true)
		{
			frm.hdAction.value = "replace";
			frm.replaceTable.value = "";
			frm.submit();
		}
		else
		{
			showMessage('<?=CHOOSE_TABLE_REQUIRED?>', 2);
		}
	}
	
	/**
	 * Show message
	 */
	function showMessage(msg, level)
	{
		switch(level)
		{
			case 1:
			case 2:		color = " red";	break;
			case 3:		color = "#0061BE";	break;
			default:	color = "#0061BE";	break;
		}
		
		txtMessage = "	<table width='100%' style='border:1px solid " + color + ";' cellpadding='8' cellspacing='0'> "
					+	"	<tr> "
					+	"		<td style='color:" + color + ";' width='100%'> " + msg + " </td> "
					+	"	</tr> "
					+	"</table> ";
		
		document.getElementById('trackingProvider').innerHTML = txtMessage;
	}
</script>
  
</head>

<body onload="formLoad()">

<div align="center">
	<table border="1" width="90%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="0" bordercolordark="#FFFFFF">
		<!--Header START-->
		<tr>
			<td width="100%" colspan="2">
				<table width="100%" cellspacing="0" cellpadding="0">
					<?
						$arrHierarchy = array(DM10_NAME => DM10_ROUTE, DM30_NAME => "");
						$this->functions->header(DM30_ID, $arrHierarchy);
					?>
				</table>
			</td>
		</tr>
		<!--Header END-->
		<!--Content START-->
		<tr>
			<td valign="top" bgcolor="#FFFFFF" width="99%" colspan="2"><h1>Table Listing</h1>
			</td>
		</tr>
		<?
		$attributes = array('id' => 'frmTables', 'name' => 'frmTables');
		$arrHidden = array('tableName' => $tableName, 'page' => $page, 'pageno_detail' => '', 'hdAction' => $hdAction, 'replaceTable' => $replaceTable);
		echo form_open(DM30_ROUTE, $attributes, $arrHidden);
		?>
		<tr>
			<td valign="top" bgcolor="#FFFFFF" class="menu_list" width="25%">
			<!--left menu tables START-->
			<?
			$this->functions->leftMenuTable($arrTable, $pagination, $resultFound);
			?>
			<!--left menu tables END-->
			</td>
			<td valign="top" bgcolor="#FFFFFF" class="project" width="75%">
			<div align="center">
				<table border="0" width="90%" id="table9" cellspacing="0" cellpadding="3">
					<tr>
						<td colspan="2" height="20">　</td>
					</tr>
					<tr>
						<td colspan="2"><b>All field's data of seleted tables will be replaced</b></td>
					</tr>
					<tr>
						<td width="13%">Find what:</td>
						<td width="85%">
						<?
						$data = array(
						              'name'        => 'txtFindText',
						              'id'          => 'txtFindText',
						              'value'       => $txtFindText,
						              'class'       => 'input',
						              'onkeyup'     => 'trackInput()'
						            );
						echo form_input($data);
						?>
						</td>
					</tr>
					<tr>
						<td width="13%">Replace with:</td>
						<td width="85%">
						<?
						$data = array(
						              'name'        => 'txtNewText',
						              'id'          => '',
						              'value'       => $txtNewText,
						              'class'       => 'input'
						            );
						echo form_input($data);
						?>
						</td>
					</tr>
					<tr>
						<td width="13%">　</td>
						<td width="85%">
						<?
						$data = array(
									    'name' => 'btnFindTable',
									    'id' => 'btnFindTable',
									    'value' => 'Find Table',
									    'type' => 'button',
									    'class' => 'button',
									    'onclick' => 'findTable()'
									);
						echo form_submit($data) . "&nbsp;&nbsp;";
						$data = array(
									    'name' => 'btnReplace',
									    'id' => 'btnReplace',
									    'value' => 'Replace',
									    'type' => 'button',
									    'class' => 'button',
									    'onclick' => 'checkInput()',
									    'disabled' => true
									);
						echo form_submit($data);
						?>
						</td>
					</tr>
				</table>
				<br>
				<table border="0" width="90%" id="table9" cellspacing="0" cellpadding="3">
					<tr>
						<td colspan="2" height="20">
						<span id="trackingProvider" name="trackingProvider">
						</td>
					</tr>
				</table>
				</span>
				<p>
				<br>
				
			</div>
			</td>
		</tr>
		<?
		echo form_close();
		?>
		<!--Content END-->
		<!--Footer START-->
		<tr>
			<td width="100%" colspan="2">
				<table width="100%" cellspacing="0" cellpadding="0">
					<?echo $this->functions->footer();?>
				</table>
			</td>
		</tr>
		<!--Footer END-->
	</table>
</div>

</body>

</html>