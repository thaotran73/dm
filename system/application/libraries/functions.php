<?php
/*
 * Created on 2008/09/25
 * Author: Thao Tran
 * Common functions for all screens
 */
require_once(C_LIB_PATH.'getconfig.inc.php');
require_once(C_LIB_PATH.'constant.inc.php');

class Functions	
{ 

	function Functions() 
	{
		$this->dm = &get_instance();
	}
	
	/**
	 * Created on 2008/09/25
	 * load Hierarchy for another screen
	 * @param: $arrHierarchy: array of Hierarchy
	 * @return: a string
	 **/
	function loadHierarchy($arrHierarchy)
	{
		$sReturn = "";
		$i = 0;
		foreach ($arrHierarchy as $key => $value) 
		{
			if ($i == 0)
			{
				if ($value)
				{
					$sReturn .= "<a href=\"" . $value . "\">" . $key . "</a>";
				}
				else
				{
					$sReturn .= $key;
				}
			}
			else
			{
				if ($value)
				{
					$sReturn .= " >> <a href=\"" . $value . "\">" . $key . "</a>";
				}
				else
				{
					$sReturn .= " >> " . $key;
				}
			}
			$i++;
		}
		return $sReturn;
	}
	
	/**
	 * Created on 2008/09/25
	 * Author: Thao Tran
	 * load header information for any screens
	 * **/
	function header($sScreenID, $arrHierarchy)
	{
		?>
		<script language="javascript">
			function doLogout()
			{
				document.frmLogout.action = 'login';
				document.frmLogout.submit();
			}
		</script>
		<?
		$attributes = array('id' => 'frmLogout', 'name' => 'frmLogout');
		$hidden = array('hdLogout' => '1');
		echo form_open("login", $attributes, $hidden);
		?>
		<tr>
			<td class="header" valign="top" width="100%">
			<table border="0" width="100%" id="table6" cellspacing="0" cellpadding="2" height="100%">
				<tr>
					<td width="740">Å@</td>
					<td width="200"><p align="right">&nbsp;</td>
					<td><p align="right"><a href="javascript:doLogout()">Logout</a></td>
				</tr>
				<tr>
					<td width="740"><?echo $this->loadHierarchy($arrHierarchy);?></td>
					<td width="200"><p align="right">&nbsp;</td>
					<td><p align="right"><?echo $sScreenID?></td>
				</tr>
			</table>
			</td>
		</tr>
		<?
		echo form_close();
	}
	
	/**
	 * Created on 2008/09/25
	 * Author: Thao Tran
	 * load footer information for any screens
	 * **/
	function footer()
	{
		?>
		<tr>
			<td class="footer" align="center" width="100%"><?echo BOTTOM_CONTENT?></td>
		</tr>
		<?
	}
	
	function leftMenuTable($arrTable, $pagination, $resultFound=null)
	{?>
		<script language="javascript">
			function formTableInfo(tableName)
			{
				var frm = document.frmTables;
				frm.tableName.value = tableName;
				frm.pageno_detail.value = '';
				frm.action = '<?echo DM31_ROUTE?>';
				frm.submit();
			}
			
			//process function when click on paging control
			function process_page(index)
			{
				var frm = document.frmTables;
				frm.page.value = index;
				frm.submit();
			}
		</script>
		<table border="0" width="100%" id="table7" cellspacing="0" cellpadding="2">
			<tr>
				<td height="10">
				</td>
			</tr>
			<tr>
				<td class="div.pagination"><?echo $pagination?></td>
			</tr>
			<tr>
				<td>
				<table border="0" width="100%" id="table8" cellspacing="0" cellpadding="2">
					<?
					for ($i=0; $i<count($arrTable); $i++)
					{
						$sTableName = $arrTable[$i];
						$checked = false;
						$redirectItemStyle = "style='background-color:transparent'";
						if(isset($resultFound))
						{
							if(in_array($sTableName, $resultFound))
							{
								$redirectItemStyle = "style='background-color:#FFCB44'";
								$checked = true;
							}
						}
					?>
					<tr>
						<td width="26" align=center class="evenitem" <?=$redirectItemStyle?>>
						<?
						$data = array(
								    'name'        => 'chkTable_' . $sTableName,
								    'id'          => 'chkTable_' . $sTableName,
								    'value'		  => $sTableName,
								    'checked'     => $checked
								    );
						echo form_checkbox($data);
						echo form_hidden('hidTable_' . $sTableName, $sTableName);
						?>
						</td>
						<td class="evenitem" <?=$redirectItemStyle?>><a href="javascript:formTableInfo('<?echo $sTableName?>')">
						<?echo $sTableName?></a></td>
						
					</tr>
					<?
					}
					?>
					</table>				
				</td>
			</tr>
			<tr>
				<td class="div.pagination"><?echo $pagination?></td>
			</tr>
		</table>
	<?}
}
?>
