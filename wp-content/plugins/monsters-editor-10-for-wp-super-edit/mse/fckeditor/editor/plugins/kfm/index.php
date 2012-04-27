<?php
# see license.txt for licensing
require_once('initialise.php');
require_once($kfm_base_path.'includes/kaejax.php');

{ # export kaejax stuff
	kfm_kaejax_export(
		'kfm_changeCaption','kfm_copyFiles','kfm_createDirectory','kfm_createEmptyFile','kfm_deleteDirectory','kfm_downloadFileFromUrl',
		'kfm_extractZippedFile','kfm_getFileDetails','kfm_getFileUrl','kfm_getTagName','kfm_getTextFile','kfm_getThumbnail','kfm_loadDirectories',
		'kfm_loadFiles','kfm_moveDirectory','kfm_moveFiles','kfm_renameDirectory','kfm_renameFile','kfm_renameFiles','kfm_resizeImage','kfm_rm',
		'kfm_rotateImage','kfm_saveTextFile','kfm_search','kfm_tagAdd','kfm_tagRemove','kfm_viewTextFile','kfm_zip'
	);
	if(!empty($_POST['kaejax']))kfm_kaejax_handle_client_request();
}

?>
<html>
	<head>
		<style type="text/css"><?php
			$css=file_get_contents('themes/'.$kfm_theme.'/kfm.css');
			$css.=file_get_contents('pear/Text/hilight.css');
			echo preg_replace('/\s+/',' ',$css);
		?></style>
		<title>KFM - Kae's File Manager</title>
		<script type="text/javascript">
<?php
	$js='';
	$js.=file_get_contents('lang/'.$kfm_language.'.js');
	$js.=file_get_contents('j/variables.js');
	$js.=file_get_contents('j/kfm.js');
	$js.=file_get_contents('j/alerts.js');
	$js.=file_get_contents('j/modal.dialog.js');
	$js.=file_get_contents('j/contextmenu.js');
	$js.=file_get_contents('j/directories.js');
	$js.=file_get_contents('j/file.selections.js');
	$js.=file_get_contents('j/file.text-editing.js');
	$js.=file_get_contents('j/images.and.icons.js');
	$js.=file_get_contents('j/panels.js');
	$js.=file_get_contents('j/tags.js');
	$js.=file_get_contents('j/common.js');
	{ # browser-specific functions
		if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))$_SERVER['HTTP_ACCEPT_LANGUAGE']='';
		require_once('Detect.php');
		$browser=new Net_UserAgent_Detect();
		{ # kaejax_replaces
			if($browser->isIE())$js.=file_get_contents('j/kaejax_replaces.ie.js');
			else $js.=file_get_contents('j/kaejax_replaces.js');
		}
		{ # addEvent
			if($browser->isIE())$js.=file_get_contents('j/addEvent.ie.js');
			else if($browser->getBrowserString()=='Konqueror/Safari')$js.=file_get_contents('j/addEvent.konqueror.js');
			else $js.=file_get_contents('j/addEvent.js');
		}
		{ # getWindow
			if($browser->isIE())$js.=file_get_contents('j/getWindow.ie.js');
			else if($browser->getBrowserString()=='Konqueror/Safari')$js.=file_get_contents('j/getWindow.konqueror.js');
			else $js.=file_get_contents('j/getWindow.js');
		}
		{ # getWindowSize
			if($browser->isIE())$js.=file_get_contents('j/getWindowSize.ie.js');
			else $js.=file_get_contents('j/getWindowSize.js');
		}
	}
	$js.=file_get_contents('j/files.js');
	$js.=file_get_contents('swfupload/SWFUpload.js');
	$js=preg_replace('#// .*|[\t]#','',$js); # strip single-line comments and tabs
	$js=preg_replace('#/\*.*?\*/#ims','',$js); # strip multi-line comments
	$js=preg_replace('#;\n}#ims','}',$js);
	$js=preg_replace('#:\n"#ims',':"',$js);
	$jsnew=$js;
	do{
		$redo=0;
		$jsnew=preg_replace('#\n\n#ims',"\n",$jsnew);
		$jsnew=preg_replace('#([{}])\n([{}])#ims','\1\2',$jsnew);
		if($js!=$jsnew)$redo=1;
		$js=$jsnew;
	}while($redo);
	echo $js;
?>
			var session_id="<?php echo session_id(); ?>";
			var starttype="<?php echo isset($_GET['Type'])?$_GET['Type']:''; ?>";
			var fckroot="<?php echo $kfm_userfiles; ?>";
			var fckrootOutput="<?php echo $kfm_userfiles_output; ?>";
			var kfm_file_handler="<?php echo $kfm_file_handler; ?>";
			var kfm_log_level=<?php echo $kfm_log_level; ?>;
			var kfm_theme="<?php echo $kfm_theme; ?>";
			var kfm_hidden_panels="<?php echo $kfm_hidden_panels; ?>".split(',');
			var kfm_show_files_in_groups_of=<?php echo $kfm_show_files_in_groups_of; ?>;
			for(var i=0;i<kfm_hidden_panels.length;++i)kfm_hidden_panels[i]='kfm_'+kfm_hidden_panels[i]+'_panel';
			<?php echo kfm_kaejax_get_javascript(); ?>
			<?php if(isset($_GET['kfm_callerType']))echo 'window.kfm_callerType="'.addslashes($_GET['kfm_callerType']).'";'; ?>
			var editable_extensions=["<?php echo join('","',$kfm_editable_extensions);?>"];
			var viewable_extensions=["<?php echo join('","',$kfm_viewable_extensions);?>"];
			var kfm_vars={
				permissions:{
					del:<?php echo $kfm_allow_file_delete; ?>,
					edit:<?php echo $kfm_allow_file_edit; ?>,
					move:<?php echo $kfm_allow_file_move; ?>
				}
			};
		</script>
	</head>
	<body>
		<noscript>KFM relies on JavaScript. Please either turn on JavaScript in your browser, or <a href="http://www.getfirefox.com/">get Firefox</a> if your browser does not support JavaScript.</noscript>
		<script type="text/javascript">setTimeout('kfm()',10);</script>
		<?php
			if(!$kfm_dont_send_metrics){
				$today=date('Y-m-d');
				if(!isset($_SESSION['kfm_parameters']['last_registration'])||$_SESSION['kfm_parameters']['last_registration']!=$today){
					echo '<img src="http://kfm.verens.com/extras/register.php?version='.urlencode(KFM_VERSION).'&amp;domain_name='.urlencode($_SERVER['SERVER_NAME']).'" />';
					$kfmdb->query("delete from ".$kfm_db_prefix."parameters where name='last_registration'");
					$kfmdb->query("insert into ".$kfm_db_prefix."parameters (name,value) values ('last_registration','".$today."')");
					$_SESSION['kfm_parameters']['last_registration']=$today;
				}
			}
		?>
	</body>
</html>
