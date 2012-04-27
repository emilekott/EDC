<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>{$lang_mse_title}</title>
	<script language="javascript" type="text/javascript" src="/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="jscripts/functions.js"></script>
    <script language="javascript" type="text/javascript" src="fckeditor/fckeditor.js"></script>
    <script language="javascript" type="text/javascript">
		function loadFckeditor(){
			var sBasePath = document.location.pathname.substring(0,document.location.pathname.lastIndexOf('/')+1) ;
			var oFCKeditor = new FCKeditor( 'htmlSource' ) ;
			oFCKeditor.Height = "100%" ;
		    oFCKeditor.BasePath = sBasePath + "fckeditor/" ;
			oFCKeditor.Config["CustomConfigurationsPath"] = sBasePath + "fckeditor_config.js"  ;
			oFCKeditor.ReplaceTextarea() ;
		}
	</script>
	<base target="_self" />
</head>
<body onLoad="tinyMCEPopup.executeOnLoad('onLoadInit();');document.body.style.display='';loadFckeditor();" style="margin: 0px; display: none;">
	<form name="source" onSubmit="saveContent();" action="#">
		<textarea name="htmlSource" id="htmlSource" rows="15" cols="100" style="padding: 0px; margin: 0px; width: 100%; height: 100%;" dir="ltr" wrap="off"></textarea>

		<div class="mceActionPanel">
			<div style="float: left">
				<input type="button" name="cancel" value="{$lang_mse_cancel}" onClick="tinyMCEPopup.close();" id="cancel" />
			</div>

			<div style="float: right">
				<input type="submit" name="insert" value="{$lang_mse_update}" onClick="saveContent();" id="insert" />
			</div>
		</div>
	</form>
</body>
</html>
