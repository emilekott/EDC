<?php
if($_SESSION['kfm_parameters']['version']=='7.0'||$_SESSION['kfm_parameters']['version']<'0.7.2')require 'scripts/update.0.7.2.php';
switch($kfm_db_type){
	case 'mysql':{
		require 'scripts/db.mysql.update.0.7.2.php';
		break;
	}
	case 'pgsql':{
		require 'scripts/db.pgsql.update.0.7.2.php';
		break;
	}
	case 'sqlite':{
		require 'scripts/db.sqlite.update.0.7.2.php';
		break;
	}
	default:{
		echo 'error: unknown database specified in scripts/update.0.7.2.php'; # TODO: new string
		exit;
	}
}
$kfmdb->query("delete from ".$kfm_db_prefix."parameters where name='version'");
$kfmdb->query("insert into ".$kfm_db_prefix."parameters set value='0.8',name='version'");
?>
