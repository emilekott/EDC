<?php
{ # variables
	$kfm_kaejax_js_has_been_shown=0;
	$kfm_kaejax_export_list=array();
	$kfm_kaejax_is_loaded=strstr($_SERVER['REQUEST_URI'],'kfm_kaejax_is_loaded');
}	
function kfm_kaejax_handle_client_request(){
	if(!isset($_POST['kaejax']))return;
	$obj=json_decode(str_replace('%2B','+',$_POST['kaejax']));
	$fs=$obj->c;
	if(!is_array($fs)){ # something wrong
		echo "error: unknown data sent from client.\n\n";
		print_r($_POST['kaejax']);
		exit;
	}
	$res=array();
	foreach($fs as $f)$res[]=call_user_func_array($f->f,$f->v);
	echo json_encode($res);
	exit;
}
function kfm_kaejax_esc($val){
	return str_replace(array("\\","\r","\n",'"'),array("\\\\","\\r","\\n",'\\"'),$val);
}
function kfm_kaejax_get_one_stub($func_name){
	$a='function x_'.$func_name.'()'.LSQUIGG.'kfm_kaejax_do_call("'.$func_name.'",arguments);'.RSQUIGG.'function_urls.'.$func_name."='".$_SERVER['REQUEST_URI']."';";
	if(!$GLOBALS['kfm_kaejax_is_loaded'])$a.='kfm_kaejax_is_loaded=1;';
	$GLOBALS['kfm_kaejax_is_loaded']=1;
	return $a;
}
function kfm_kaejax_export(){
	global $kfm_kaejax_export_list;
	$n=func_num_args();
	for($i=0;$i<$n;$i++)$kfm_kaejax_export_list[]=func_get_arg($i);
}
function kfm_kaejax_get_javascript(){
	$html='';
	if(!$GLOBALS['kfm_kaejax_js_has_been_shown']&&!$GLOBALS['kfm_kaejax_is_loaded'])$GLOBALS['kfm_kaejax_js_has_been_shown']=1;
	foreach($GLOBALS['kfm_kaejax_export_list'] as $func)$html.=kfm_kaejax_get_one_stub($func);
	return $html;
}
?>
