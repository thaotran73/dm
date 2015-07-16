<!--
created on 2008/10/01
Author: Thao Tran
-->
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<link rel="stylesheet" href="<?=base_url().C_CSS_FILE_PATH;?>"/>
<title>Log表示</title>
<script language="javascript" src="<?=base_url().C_JS_PATH?>common.js"></script>
</head>
<body topmargin="0">
<table width="100%">
	<tr valign="top">
	    <td width="100%" bgcolor="#FFFFFF" class="project">
	    	<table border="0" width="98%" id="table2" cellspacing="0" cellpadding="2">
				<tr>
					<td>
						<?echo str_replace("\n", "<br>", $err);?>
					</td>
				</tr>
			</table>
	    </td>
	</tr>
</table>
</body>
</html>