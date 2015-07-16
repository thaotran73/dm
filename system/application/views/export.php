<!--
 Created on 2008/10/01
 Export to csv file
-->
<?
$oResult	= (isset($result)) ? $result : null;
$oData		= (isset($ses_Data)) ? $ses_Data : null;
$back 		= (isset($prepage)) ? $prepage : DM20_ROUTE;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<title>マスターデータ管理</title>
<link href="<?=base_url().C_CSS_FILE_PATH;?>" rel="stylesheet" type="text/css" />
<script language="JavaScript"></script>
</head>

<body topmargin="0">

<div align="center">
	<table border="1" width="90%" id="table1" bordercolorlight="#609BCD" cellspacing="0" cellpadding="2" bordercolordark="#FFFFFF">
		<!--Header START-->
		<?
			$arrHierarchy = array(DM10_NAME => DM10_ROUTE, DM20_NAME => "");
			$this->functions->header(DM20_ID, $arrHierarchy);
		?>
		<!--Header END-->
		<!--Content START-->
	  <tr valign="top">
	    <td width="100%" bgcolor="#FFFFFF" class="project">
		    <table width="100%">
		    <?
		    $attributes = array('id' => 'frmDownload', 'name' => 'frmDownload');
			$arrHidden = array('download' => '', 'filename' => $oResult->msg);
			echo form_open("export", $attributes, $arrHidden);
		    ?>
				  <tr>
				    <td align="center">
					<?
					if ($oResult->msg){
						?>
						<br/><a href="javascript:frmDownload.submit()"><?=C_LINK_DOWNLOAD?></a>
						<?
					}else{
						echo C_NODATA_DOWNLOAD;
					}
					?>
				    </td>
				  </tr>
			  <?echo form_close();
			  ?>
			  <tr><td height="20px">&nbsp;</td></tr>
			  <?
			    $attributes = array('id' => 'frmBack', 'name' => 'frmBack');
				echo form_open($back, $attributes);
			    ?>
				<?	
					$arrData = $oData;
					foreach ($arrData as $key => $value) 
					{
						if ($key != 'export' && $key != 'act') 
						{
							$arrData = array($key => $value);
							echo form_hidden($arrData) . "\n";
						}
					}
				?>
				  <tr>
				  	<td align="center">
				  	<?
				  	$data = array(
							    'name' => 'btnBack',
							    'id' => 'btnBack',
							    'value' => '戻 る',
							    'type' => 'submit',
							    'class' => 'button'
							);
					echo form_submit($data);
					?>
				  	</td>
				  </tr>
			  <?echo form_close();?>
			</table>
		</td>
	  </tr>
	  <!--Content END-->
	  <!--Footer START-->
	  <?echo $this->functions->footer();?>
	  <!--Footer END-->
	</table>
</body>
</html>
<?exit;?>