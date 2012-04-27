// see license.txt for licensing
	if(!window.kfm_callerType)window.kfm_callerType='standalone';
	var browser=new Browser(),loadedScripts=[],function_urls=[],kfm_cwd_name='',kfm_cwd_id=0,kfm_cwd_subdirs=[],contextmenu=null,selectedFiles=[];
	var kfm_filesCache=[],kfm_tags=[],kfm_lastClicked,kfm_unique_classes=[],kfm_directory_over=0,kfm_kaejax_timeouts=[];
	var kfm_directories=[0,{name:'root',pathname:'/'}],kfm_kaejax_is_loaded=0;
