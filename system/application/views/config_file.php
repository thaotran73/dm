<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title><?echo $screen_name?></title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
<script language="javascript">
	/*
	* send data info to server
	*/
	function checkSubmit()
	{
		var frm = document.frmConfigFile;
		frm.act.value = 'submit';
		frm.action = '<?echo $route?>';
		frm.submit();
	}
	
	/*
	* go to previuos page
	*/
	function gotoBack()
	{
		top.location.href = '<?echo DM10_ROUTE?>';
	}
	
	/*
	* init information page in the first time
	*/
	function formLoad()
	{
		var obj = document.getElementById("result");
		obj.innerHTML  = '';
		<?if ($result)
		{
			echo "obj.innerHTML  = \"<table><tr><td style='color:red'>".$result."<\/td><\/tr><\/table>\";";
		}
		?>
		
	}
</script>
</head>

<body onload="formLoad();">

<div align="center">
	<table border="1" width="90%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="2" bordercolordark="#FFFFFF">
		<!--Header START-->
		<?
			$arrHierarchy = array(DM10_NAME => DM10_ROUTE, $screen_name => "");
			$this->functions->header($screen_id, $arrHierarchy);
		?>
		<!--Header END-->
		<!--Content START-->
		<tr>
			<td valign="top" bgcolor="#FFFFFF" class="project">
			<div align="center">
				<?$attributes = array('id' => 'frmConfigFile', 'name' => 'frmConfigFile');
				$arrHidden = array('act' => '');
				echo form_open($route, $attributes, $arrHidden)?>
				<table border="0" width="98%" id="table2" cellspacing="0" cellpadding="4">
					<tr>
						<td colspan="2">　</td>
					</tr>
					<tr>
						<td colspan="2"><h1><?echo $screen_name?></h1></td>
					</tr>
					<tr>
						<td width="99%" colspan="2" height="15">
						</td>
					</tr>
					<tr>
						<td width="2%">
						　</td>
						<td width="98%">
						<?
						if ($screen_id==DM41_ID)
						{
							$data = array(
										    'name' 		=> 'btnSubmit',
										    'id' 		=> 'btnSubmit',
										    'value' 	=> '登　録',
										    'type' 		=> 'button',
										    'class' 	=> 'button',
										    'onclick' 	=> 'checkSubmit()'
										);
							echo form_submit($data) . "&nbsp;&nbsp;";
						}
						?>
						<?
						$data = array(
									    'name' 		=> 'btnGoBack',
									    'id' 		=> 'btnGoBack',
									    'value' 	=> 'キャンセル',
									    'type'		=> 'button',
									    'class' 	=> 'button',
									    'onclick' 	=> 'gotoBack()'
									);
						echo form_submit($data);
						?>
						</td>
					</tr>
					<tr>
						<td width="2%"></td>
						<td width="98%" colspan="2" height="15">
						<span id="result" name="result"></span>
						</td>
					</tr>
					<tr>
						<td width="2%">
						　</td>
						<td width="98%">
						<?
						if ($screen_id==DM42_ID)
						{
							$data = array(
							              'name'	=> 'content',
							              'id'      => 'content',
							              'value'   => $content,
							              'rows'   	=> '37',
							              'cols'    => '100',
							              'class'   => 'input_area',
							              'onpaste' => 'javascript:return false;',
							              'onkeydown' => 'javascript:return false;'
							            );
						}
						else
						{
							$data = array(
							              'name'	=> 'content',
							              'id'      => 'content',
							              'value'   => $content,
							              'rows'   	=> '37',
							              'cols'    => '100',
							              'class'   => 'input_area'
							            );
						}
						echo form_textarea($data);
						?>
						</td>
					</tr>
					<tr>
						<td width="99%" colspan="2">
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
