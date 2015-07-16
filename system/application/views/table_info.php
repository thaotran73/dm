<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>Table Reviewer</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="javascript">
	//load form
	function formLoad()
	{
		var frm = document.frmTableInfo;
		frm.tableName.value = '<?echo $tableName?>';
		<?
		if ($warning1)
		{
		?>
			document.getElementById("warning1").innerHTML = "<table><tr><td style='color:red'><?=$warning1?><\/td><\/tr><\/table>";
		<?
		}
		else if ($strOverwrite)
		{
		?>
			if (confirm('<?echo $strOverwrite?>'))
			{
				frm.act.value = "register";
				frm.overwrite.value = "1";
				frm.action = '<?echo DM31_ROUTE?>';
			    frm.submit();
			}
		<?
		}
		?>
	}

	//process function when click on paging control
	function process_page_detail(index)
	{
		var frm = document.frmTableInfo;
		frm.pageno_detail.value = index;
		frm.submit();
	}
	
	/*go back table listing*/
	function doGoBack()
	{
		var frm = document.frmTables;
		frm.action = '<?echo DM30_ROUTE?>';
		frm.submit();
	}
	
	/**
	 *  check to deleted records are checked
	 *  @param: frm: form is executed
	 *  @return: false if inputted data is invalid
	 **/
	function checkDelete(frm)
	{
		var frm = document.frmTableInfo;
		var sName = '';
		var iCount = 0;
		var obj_warning = document.getElementById("warning");
		for (var i=0;i<frm.elements.length;i++)
		{
			var e=frm.elements[i];
			if (e.checked && e.type=="checkbox" && e.name.indexOf("_")!=false)
			{
				iCount++;
			}
		}
		obj_warning.innerHTML = "";
		if (iCount==0)
		{
			obj_warning.innerHTML = "<table><tr><td style='color:red'><?echo SELECT_RECORD_TO_DELETE?><\/td><\/tr><\/table>";
			return false;
		}
		else
		{
			if (confirm('<?echo DELETE_DATA_CHECKED?>'))
			{
				frm.act.value = "delete";
				frm.action = '<?echo DM31_ROUTE?>';
			    frm.submit();
			}
		}
	}
	
	//process function when click on paging control
	function doOrder(field_order, order_type)
	{
		var frm = document.frmTableInfo;
		frm.field_order.value = field_order;
		frm.order_type.value = order_type;
		frm.submit();
	}
	
	/**
	 *  check to register a record to database
	 *  @param: frm: form is executed
	 *  @return: none
	 **/
	function checkRegister(frm)
	{
		var obj_warning = document.getElementById("warning1");
		obj_warning.innerHTML = "";
		<?
		foreach ($oFieldInfo as $field)
		{
			if ($field->notnull=='t')
			{
				?>
				if (eval('frm.<?=$field->name?>').value=='')
				{
					var strMsg = '<?=DATA_REQUIRED?>';
					strMsg = strMsg.replace('xxx', '<?=$field->name?>');
					obj_warning.innerHTML = "<table><tr><td style='color:red'>" + strMsg + "<\/td><\/tr><\/table>";
					frm.<?=$field->name?>.focus();
					return false;
				}
				<?
			}
		}
		?>
		if (frm.update_no.value!="")
		{
			frm.act.value = "update";	
		}
		else
		{
			frm.act.value = "register";
		}
		frm.action = '<?echo DM31_ROUTE?>';
	    frm.submit();
	}
	
	/**
	* update a record
	* @param: num: number order to update
	*  @return: none
	**/
	function selectData(num)
	{
		var frm = document.frmTableInfo;
		frm.update_no.value=num;
		frm.act.value = "select_data";
		frm.action = '<?echo DM31_ROUTE?>';
	    frm.submit();
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
						$arrHierarchy = array(DM10_NAME => DM10_ROUTE, DM31_NAME => "");
						$this->functions->header(DM31_ID, $arrHierarchy);
					?>
				</table>
			</td>
		</tr>
		<!--Header END-->
		<!--Content START-->
		<tr>
			<td valign="top" bgcolor="#FFFFFF" width="99%" colspan="2"><h1>Table Viewer</h1>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FFFFFF" class="menu_list" width="25%">
			<!--left menu tables START-->
			<?
			$attributes = array('id' => 'frmTables', 'name' => 'frmTables');
			$arrHidden = array('tableName' => $tableName, 'page' => $page, 'pageno_detail' => $pageno_detail, 'hdAction' => $hdAction, 'txtFindText' => $txtFindText, 'txtNewText' => $txtNewText);
			echo form_open(DM31_ROUTE, $attributes, $arrHidden);
			$this->functions->leftMenuTable($arrTable, $pagination);
			echo form_close();
			?>
			<!--left menu tables END-->
			</td>
			<?
			$attributes = array('id' => 'frmTableInfo', 'name' => 'frmTableInfo');
			$arrHidden = array('field_order' => $old_field_order, 'old_field_order' => $old_field_order, 'order_type' => $old_order_type, 'old_order_type' => $old_order_type, 'act' => '', 'tableName' => $tableName, 'total_record' => count($arrFieldList), 'page' => $page, 'pageno_detail' => $pageno_detail, 'hdAction' => $hdAction, 'txtFindText' => $txtFindText, 'txtNewText' => $txtNewText, 'overwrite' => '', 'update_no' => $update_no); 
			echo form_open(DM31_ROUTE, $attributes, $arrHidden);
			?>
			<td valign="top" bgcolor="#FFFFFF" class="project" width="75%">
			<div align="left">
				<br>
				<table border="0" width="800" id="table11" cellspacing="0" cellpadding="2">
					<tr>
						<td width="10%"><a href="javascript:doGoBack()">Go to Back</a></td>
						<td width="60%" align="left" class="div.pagination"><?echo $pagination_detail?></td>
						<td width="30%" align="left"><font color="red"><?echo $totalRecord?> records</font></td>
					</tr>
					<tr>
						<td colspan="3" height="10"></td>
					</tr>
					<tr>
						<td width="100%" colspan="3" align="left">
						<span id="warning" name="warning"></span>
						</td>
					</tr>
				</table>
				<table cellPadding="2" border="0" id="table10" width="100%">
					<tr>
						<td class="hdtitle">
						<?
						$data = array(
								    'name' => 'btnDelete',
								    'id' => 'btnDelete',
								    'value' => '削除',
								    'type' => 'button',
								    'class' => 'small_button',
								    'onclick'  => 'checkDelete(document.frmTableInfo)'
								);
						echo form_submit($data);
						?>
						</td>
						<td class="hdtitle" colspan="<?echo count($arrFieldName)+1?>"><?echo $tableName?></td>
					</tr>
					<tr>
					<td class="hditem" width="5%" align="center">
					<?
					$data = array(
					    'name'        => 'chkAll',
					    'id'          => 'chkAll',
					    'value'       => '',
					    'onclick'	  => 'checkAll(document.frmTableInfo, document.frmTableInfo.chkAll)',
					    'checked'     => false
					    );
					echo form_checkbox($data);
					?>
					</td>
					<td class="hditem" width="5%">ID No.</td>
					<?
					$iTotalField = count($arrFieldName);
					for ($i=0; $i<$iTotalField; $i++)
					{
						$order_type = 'DESC';
						if ($old_field_order == $arrFieldName[$i])
						{
							$order_type = $old_order_type=='DESC'?'ASC':'DESC';
						}
						echo "<td class='hditem'><a href='javascript:doOrder(\"{$arrFieldName[$i]}\", \"$order_type\")'>{$arrFieldName[$i]}</a></td>";
					}			
					?>
					</tr>
					<?
					if (is_array($arrFieldList) && count($arrFieldList)>0)
					{
						$iCount = 0;
						foreach ($arrFieldList as $key => $data)
						{
							if ($iCount%2==0)
							{
								$class = 'odditem';
							}
							else
							{
								$class = 'evenitem';
							}
							echo "<tr>\n";
							//determine checkbox to delete data					
							echo "<td class='{$class}' align='center'>";
							$data1 = array(
								    'name'        => 'chk_'.($key + 1),
								    'id'          => 'chk_'.($key + 1),
								    'value'       => ($key + 1),
								    'checked'     => false
								    );
							echo form_checkbox($data1);
							echo "</td>";
							echo "<td class='{$class}'><a href='javascript:selectData(". ($key + 1)  .")'>" . (($pageno_detail-1)*DM31_RECORD_PER_PAGE + $key + 1) . "</a></td>";
							foreach ($data as $k => $v)
							{
								for ($i=0; $i<$iTotalField; $i++)
								{
									if ($k == $arrFieldName[$i])
									{
										//check and get key value for update case
										if (in_array($k, $arrKeyName))
										{
											echo form_hidden('pri_' . ($key + 1) . '_' . $k, $v);
										}
										echo "<td class='{$class}' >".nl2br($this->utils->jPEncoding($v,$encoding))."</td>\n";
									}
								}
							}
							echo "</tr>\n";
							$iCount++;
						}
					}
					?>
					<tr><td colspan="<?echo ($iTotalField+2)?>" align="left">
					<?
					$data = array(
							    'name' => 'btnRegister',
							    'id' => 'btnRegister',
							    'value' => '登録',
							    'type' => 'button',
							    'class' => 'small_button',
							    'onclick'  => 'checkRegister(document.frmTableInfo)'
							);
					echo form_submit($data);
					?>
					</td></tr>
					<tr>
						<td colspan="2"></td>
						<td colspan="<?echo ($iTotalField)?>" align="left">
						<span id="warning1" name="warning1"></span>
						</td>
					</tr>
					<tr>
						<?
						echo "<td>&nbsp;</td>";
						echo "<td align='left'><label id='id_max'></label></td>";
						//save old key
						foreach ($arrOldKey as $key => $value)
						{
							echo form_hidden('pri_old_' . $key, trim($value));
						}
						
						foreach ($oFieldInfo as $field)
						{
							$value = '';
							$maxlen = ceil($field->max_length);
							if ($field->type=='int4')
							{
								$maxlen = 5;
							}
							//added by Thao Tran on 20140126 START
							if ($maxlen>5) 
							{
								$maxlen = 5;
							}
							//added by Thao Tran on 20140126 END
							if (isset($vars[$field->name]))
							{
								$value = trim($vars[$field->name]);
							}
							echo "<td align='left'>";
							$data = array(
							              'name'        => $field->name,
							              'id'          => $field->name,
							              'value'       => $value,
							              'maxlength'   => $maxlen,
							              'size'        => $maxlen
							            );
							echo form_input($data);
							echo "</td>";
						}
						?>
					</tr>
				</table>
				<br>
			</div>
			</td>
			<?
			echo form_close();
			?>
		</tr>
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