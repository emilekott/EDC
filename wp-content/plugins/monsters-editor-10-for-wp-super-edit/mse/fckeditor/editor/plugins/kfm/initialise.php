<?php
# see license.txt for licensing
if(!isset($kfm_base_path))$kfm_base_path='';
error_reporting(E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
if(isset($_GET['session_id']))session_id($_GET['session_id']);
@session_start();
if(get_magic_quotes_gpc()){
	# taken from http://www.phpfreaks.com/quickcode/Get-rid-of-magic_quotes_gpc/618.php
	function traverse (&$arr){
		if(!is_array($arr))return;
		foreach($arr as $key=>$val){
			if(is_array($arr[$key]))traverse($arr[$key]);
			else $arr[$key]=stripslashes($arr[$key]);
		}
	}
	$gpc=array(&$_GET,&$_POST,&$_COOKIE);
	traverse($gpc);
}
set_include_path(get_include_path().PATH_SEPARATOR.$kfm_base_path.'pear');
if(!file_exists($kfm_base_path.'configuration.php')){
	echo '<em>Missing <code>configuration.php</code>!</em><p>If this is a fresh installation of KFM, then please rename <code>configuration.php.dist</code> to <code>configuration.php</code>, and edit it according to your project\'s needs.</p><p>If this is an upgraded version of KFM, please remove the parts of your old <code>config.php</code> which do not exist in <code>configuration.php.dist</code>, then rename it to <code>configuration.php</code>.</p>';
	exit;
}
require_once($kfm_base_path.'configuration.php');

{ # REMOVE FOR 1.0. check for missing variables in configuration.php
	$m=array();
	if(!isset($kfm_allow_file_delete))$m[]='missing <code>$kfm_allow_file_delete</code> variable';
	if(!isset($kfm_allow_file_edit))$m[]='missing <code>$kfm_allow_file_edit</code> variable';
	if(!isset($kfm_allow_file_move))$m[]='missing <code>$kfm_allow_file_move</code> variable';
	if(count($m)){
		echo '<html><body><p>There are missing variables in your <code>configuration.php</code>. Please check the supplied <code>configuration.php.dist</code> for notes on their usage.</p><ul>';
		foreach($m as $a)echo '<li>'.$a.'</li>';
		echo '</li></ul></body></html>';
		exit;
	}
}


{ # API - for programmers only
	if(file_exists($kfm_base_path.'api/config.php'))include($kfm_base_path.'api/config.php');
	if(file_exists($kfm_base_path.'api/cms_hooks.php'))include_once($kfm_base_path.'api/cms_hooks.php');
	else include_once($kfm_base_path.'api/cms_hooks.php.dist');
}
function kfm_error_log($errno,$errstr,$errfile,$errline){
	if(!defined('WORKPATH')){
		echo '<p><em>error: WORKPATH not yet defined, and an error has occurred.</em><br />'
			.$errno.': ('.$errfile.', '.$errline.') - '.$errstr.'</p>';
	}
	if(!defined('ERROR_LOG_LEVEL')||!defined('WORKPATH'))return;
	$msg=false;
	switch ($errno) {
		case E_USER_ERROR:{
			if(ERROR_LOG_LEVEL>2)$msg='error|'.$errno.'|'.$errfile.'|'.$errline.'|'.$errstr."\n";
			break;
		}
		case E_USER_WARNING:{
			if(ERROR_LOG_LEVEL>1)$msg='warning|'.$errno.'|'.$errfile.'|'.$errline.'|'.$errstr."\n";
			break;
		}
		case E_USER_NOTICE:{
			if(ERROR_LOG_LEVEL)$msg='notice|'.$errno.'|'.$errfile.'|'.$errline.'|'.$errstr."\n";
			break;
		}
		default:{
			if(ERROR_LOG_LEVEL>3)$msg='unknown|'.$errno.'|'.$errfile.'|'.$errline.'|'.$errstr."\n";
			break;
		}
	}
	if($msg&&$handle=fopen(WORKPATH.'errors.log', 'a')){
		@fwrite($handle, date('Y-m-d H:i:s').' '.$msg."\n" );
		@fclose($handle);
	}
}
#set_error_handler('kfm_error_log');
{ # variables
	if(!isset($kfm_show_files_in_groups_of))$kfm_show_files_in_groups_of=10;
	define('KFM_VERSION',rtrim(file_get_contents($kfm_base_path.'version.txt')));
	$rootdir=str_replace('//','/',$_SERVER['DOCUMENT_ROOT'].$kfm_userfiles.'/');
	if(!is_dir($rootdir))mkdir($rootdir,0755);
	if(!isset($_SESSION['kfm']))$_SESSION['kfm']=array('currentdir'=>rtrim($rootdir,' /'),'cwd_id'=>1,'language'=>'','username'=>'','password'=>'');
	# TODO: remove the following block for 1.0
	else{
		if(!isset($_SESSION['kfm']['username'])){
			$_SESSION['kfm']['username']='';
			$_SESSION['kfm']['password']='';
			$_SESSION['kfm']['loggedin']=0;
		}
	}
	define('LSQUIGG','{');
	define('RSQUIGG','}');
	define('KFM_DIR', dirname(__FILE__));
	if(!defined('GET_PARAMS')) define('GET_PARAMS', '');
	$kfm_highlight_extensions=array('php'=>'PHP', 'html'=>'HTML', 'xhtml'=>'HTML',
		'sql'=>'MYSQL', 'js'=>'JAVASCRIPT', 'css'=>'CSS', 'xml'=>'XML');
	if(!isset($kfm_banned_files)||!is_array($kfm_banned_files))$kfm_banned_files = array();
	array_push($kfm_banned_files, 'thumbs.db', '.ds_store'); # lowercase array
	if(!isset($kfm_banned_folders)||!is_array($kfm_banned_folders)) $kfm_banned_folders = array();
	define('IMAGEMAGICK_PATH',isset($kfm_imagemagick_path)?$kfm_imagemagick_path:'/usr/bin/convert');
	$cache_directories=array();
}
{ # check authentication
	if(!isset($kfm_username)||!isset($kfm_password)||($kfm_username==''&&$kfm_password==''))$_SESSION['kfm']['loggedin']=1;
	if(!$_SESSION['kfm']['loggedin'] && (!isset($kfm_api_auth_override)||!$kfm_api_auth_override)){
		$err='';
		if(isset($_POST['username'])&&isset($_POST['password'])){
			if($_POST['username']==$kfm_username && $_POST['password']==$kfm_password){
				$_SESSION['kfm']['username']=$_POST['username'];
				$_SESSION['kfm']['password']=$_POST['password'];
				$_SESSION['kfm']['loggedin']=1;
			}
			else $err='<em>Incorrect Password. Please try again, or check your <code>configuration.php</code>.</em>';
		}
		if(!$_SESSION['kfm']['loggedin']){
			include($kfm_base_path.'login.php');
			exit;
		}
	}
}
{ # work directory
	$workpath = $rootdir.$kfm_workdirectory; // should be more at the top of this document
	$workurl = $kfm_userfiles_output.$kfm_workdirectory;
	$workdir = true;
	if(substr($workpath,-1)!='/') $workpath.='/';
	if(substr($workurl,-1)!='/') $workurl.='/';
	define('WORKPATH', $workpath);
	define('WORKURL', $workurl);
	if(is_dir($workpath)){
		if(!is_writable($workpath)){
			echo 'error: "'.htmlspecialchars($workpath).'" is not writable'; # TODO: new string
			exit;
		}
	}else{
		# Support for creating the directory
		$workpath_tmp = substr($workpath,0,-1);
		if(is_writable(dirname($workpath_tmp)))mkdir($workpath_tmp, 0755);
		else{
			echo 'error: could not create directory "'.htmlspecialchars($workpath_tmp).'"';
			exit;
		}
	}
}
{ # database
	if(!isset($_SESSION['db_defined']))$_SESSION['db_defined']=0;
	$kfm_db_prefix_escaped=str_replace('_','\\\\_',$kfm_db_prefix);
	switch($kfm_db_type){
		case 'mysql': {
			require_once('MDB2.php');
			$dsn='mysql://'.$kfm_db_username.':'.$kfm_db_password.'@'.$kfm_db_host.'/'.$kfm_db_name;
			$kfmdb=&MDB2::factory($dsn);
			if(PEAR::isError($kfmdb))die($kfmdb->getMessage());
			$kfmdb->setFetchMode(MDB2_FETCHMODE_ASSOC);
			if(!$_SESSION['db_defined']){
				$res=&$kfmdb->query("show tables like '".$kfm_db_prefix_escaped."%'");
				if(!$res->numRows())include($kfm_base_path.'scripts/db.mysql.create.php');
				else $_SESSION['db_defined']=1;
			}
			break;
		}
		case 'pgsql': {
			require_once('MDB2.php');
			$dsn='pgsql://'.$kfm_db_username.':'.$kfm_db_password.'@'.$kfm_db_host.'/'.$kfm_db_name;
			$kfmdb=&MDB2::factory($dsn);
			if(PEAR::isError($kfmdb))die($kfmdb->getMessage());
			$kfmdb->setFetchMode(MDB2_FETCHMODE_ASSOC);
			if(!$_SESSION['db_defined']){
				$res=&$kfmdb->query("SELECT tablename from pg_tables where tableowner=current_user AND tablename NOT LIKE E'pg\\\\_%' AND tablename NOT LIKE E'sql\\\\_%' AND tablename LIKE E'".$kfm_db_prefix_escaped."%'");
				if($res->numRows()<1)include($kfm_base_path.'scripts/db.pgsql.create.php');
				else $_SESSION['db_defined']=1;
			}
			break;
		}
		case 'sqlite': {
			require_once('MDB2.php');
			$kfmdb_create = false;
			define('DBNAME',$kfm_db_name);
			if(!file_exists(WORKPATH.DBNAME))$kfmdb_create=true;
			$dsn=array('phptype'=>'sqlite','database'=>WORKPATH.DBNAME,'mode'=>'0644');
			$kfmdb=&MDB2::factory($dsn);
			if(PEAR::isError($kfmdb))die($kfmdb->getMessage());
			$kfmdb->setFetchMode(MDB2_FETCHMODE_ASSOC);
			if($kfmdb_create)include($kfm_base_path.'scripts/db.sqlite.create.php');
			$_SESSION['db_defined']=1;
			break;
		}
		default: {
			echo "unknown database type specified ($kfm_db_type)"; # TODO: new string
			exit;
		}
	}
	if(!$_SESSION['db_defined']){
		echo 'failed to connect to database'; # TODO: new string
		exit;
	}
}
{ # get kfm parameters and check for updates
	if(!isset($_SESSION['kfm_parameters'])){
		$_SESSION['kfm_parameters']=array();
		$q=$kfmdb->query("select * from ".$kfm_db_prefix."parameters");
		$rs=$q->fetchAll();
		foreach($rs as $r)$_SESSION['kfm_parameters'][$r['name']]=$r['value'];
		if($_SESSION['kfm_parameters']['version']!=KFM_VERSION)require($kfm_base_path.'scripts/update.0.8.php');
	}
}
{ # languages
	$kfm_language='';
	{ # find available languages
		if($handle=opendir($kfm_base_path.'lang')){
			$files=array();
			while(false!==($file=readdir($handle)))if(is_file($kfm_base_path.'lang/'.$file))$files[]=$file;
			closedir($handle);
			sort($files);
			$kfm_available_languages=array();
			foreach($files as $f)$kfm_available_languages[]=str_replace('.js','',$f);
		}
		else{
			echo 'error: missing language files';
			exit;
		}
	}
	{ # check for URL parameter "lang"
		if(isset($_GET['lang'])&&$_GET['lang']&&in_array($_GET['lang'],$kfm_available_languages)){
			$kfm_language=$_GET['lang'];
			$_SESSION['kfm']['language']=$kfm_language;
		}
	}
	{ # check session for language selected earlier
		if(
			$kfm_language==''&&
			isset($_SESSION['kfm']['language'])&&
			$_SESSION['kfm']['language']!=''&&
			in_array($_SESSION['kfm']['language'],$kfm_available_languages)
		)$kfm_language=$_SESSION['kfm']['language'];
	}
	{ # check the browser's http headers for preferred languages
		if($kfm_language==''){
			$langs=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach($langs as $lang)if(in_array($lang,$kfm_available_languages)){
				$kfm_language=$lang;
				break;
			}
		}
	}
	{ # check the kfm_preferred_languages
		if($kfm_language=='')foreach($kfm_preferred_languages as $lang)if(in_array($lang,$kfm_available_languages)){
			$kfm_language=$lang;
			break;
		}
	}
	{ # still no language chosen? use the first available one then
		if($kfm_language=='')$kfm_language=$kfm_available_languages[0];
	}
}
{ # make a few corrections to the config where necessary
	foreach($kfm_editable_extensions as $v)if(!in_array($v,$kfm_viewable_extensions))$kfm_viewable_extensions[]=$v;
}
{ # common functions
	function kfm_checkAddr($addr){
		return (
			strpos($addr,'..')===false&&
			strpos($addr,'.')!==0&&
			strlen($addr)>0&&
			$addr[strlen($addr)-1]!=' '&&
			strpos($addr,'/.')===false&&
			!in_array(preg_replace('/.*\./','',$addr),$GLOBALS['kfm_banned_extensions'])
		);
	}
	function get_mimetype($f){
		# windows users, please install this first: http://gnuwin32.sourceforge.net/packages/file.htm
		return trim(shell_exec('file -bi '.escapeshellarg($f)));
	}
}
require_once($kfm_base_path.'framework.php');
{ # directory functions
	function kfm_add_directory_to_db($name,$parent){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _add_directory_to_db($name,$parent);
	}
	function kfm_createDirectory($parent,$name){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _createDirectory($parent,$name);
	}
	function kfm_deleteDirectory($id,$recursive=0){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _deleteDirectory($id,$recursive);
	}
	function kfm_getDirectoryDbInfo($id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _getDirectoryDbInfo($id);
	}
	function kfm_getDirectoryProperties($dir){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _getDirectoryProperties($dir);
	}
	function kfm_getDirectoryParents($pid,$type=1){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _getDirectoryParents($pid,$type);
	}
	function kfm_moveDirectory($from,$to){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _moveDirectory($from,$to);
	}
	function kfm_renameDirectory($dir,$newname){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _renameDirectory($dir,$newname);
	}
	function kfm_loadDirectories($root){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _loadDirectories($root);
	}
	function kfm_rmdir($dir){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/directories.php');
		return _rmdir($dir);
	}
}
{ # file functions
	function kfm_add_file_to_db($filename,$directory_id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _add_file_to_db($filename,$directory_id);
	}
	function kfm_copyFiles($files,$dir_id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _copyFiles($files,$dir_id);
	}
	function kfm_createEmptyFile($filename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _createEmptyFile($filename);
	}
	function kfm_downloadFileFromUrl($url,$filename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _downloadFileFromUrl($url,$filename);
	}
	function kfm_extractZippedFile($id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _extractZippedFile($id);
	}
	function kfm_getFileAsArray($filename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _getFileAsArray($filename);
	}
	function kfm_getFileDetails($filename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _getFileDetails($filename);
	}
	function kfm_getTagName($id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _getTagName($id);
	}
	function kfm_getTextFile($filename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _getTextFile($filename);
	}
	function kfm_getFileUrl($fid,$x=0,$y=0){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _getFileUrl($fid,$x,$y);
	}
	function kfm_moveFiles($files,$dir_id){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _moveFiles($files,$dir_id);
	}
	function kfm_loadFiles($rootid=1){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _loadFiles($rootid);
	}
	function kfm_renameFile($filename,$newfilename){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _renameFile($filename,$newfilename);
	}
	function kfm_renameFiles($files,$template){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _renameFiles($files,$template);
	}
	function kfm_resize_bytes($size){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _resize_bytes($size);
	}
	function kfm_rm($files,$no_dir=0){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _rm($files,$no_dir);
	}
	function kfm_saveTextFile($filename,$text){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _saveTextFile($filename,$text);
	}
	function kfm_search($keywords,$tags){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _search($keywords,$tags);
	}
	function kfm_tagAdd($recipients,$tagList){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _tagAdd($recipients,$tagList);
	}
	function kfm_tagRemove($recipients,$tagList){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _tagRemove($recipients,$tagList);
	}
	function kfm_viewTextFile($fileid){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _viewTextFile($fileid);
	}
	function kfm_zip($name,$files){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/files.php');
		return _zip($name,$files);
	}
}
{ # image functions
	function kfm_changeCaption($filename,$newCaption){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/images.php');
		return _changeCaption($filename,$newCaption);
	}
	function kfm_getThumbnail($fileid,$width,$height){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/images.php');
		return _getThumbnail($fileid,$width,$height);
	}
	function kfm_resizeImage($filename,$width,$height){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/images.php');
		return _resizeImage($filename,$width,$height);
	}
	function kfm_rotateImage($filename,$direction){
		global $kfm_base_path;
		require_once($kfm_base_path.'includes/images.php');
		return _rotateImage($filename,$direction);
	}
}
{ # JSON
	if(!function_exists('json_encode')){ # php-json is not installed
		require_once('JSON.php');
		require_once($kfm_base_path.'includes/json.php');
	}
}
?>
