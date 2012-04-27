// see license.txt for licensing
var kfm_file_bits={
	contextmenu:function(e){
		e=getEvent(e);
		{ // variables
			var name=this.kfm_attributes.name,links=[],i,id=this.kfm_attributes.id;
			var extension=name.replace(/.*\./,'').toLowerCase();
			var writable=this.kfm_attributes.writable;
		}
		{ // add the links
			if(selectedFiles.length>1){
				if(!Browser.isIE)links.push(['kfm_downloadSelectedFiles()','download selected files']); // IE can't handle this...
				links.push(['kfm_deleteSelectedFiles()',kfm_lang.DeleteFile,'remove',!kfm_vars.permissions.del]);
				var imgs=[];
				for(var i=0;i<selectedFiles.length;++i)if($('kfm_file_icon_'+selectedFiles[i]).kfm_attributes.width)imgs.push(selectedFiles[i]);
				if(imgs.length)links.push(['kfm_img_startLightbox(['+imgs.join(',')+'])','view slideshow']);
			}
			else{
				links.push(['kfm_downloadSelectedFiles('+id+')','download file']);
				links.push(['kfm_deleteFile('+id+')',kfm_lang.DeleteFile,'remove',!kfm_vars.permissions.del]);
				links.push(['kfm_renameFile('+id+')',kfm_lang.RenameFile,'edit']);
				if(this.kfm_attributes.width){
					if(writable){
						links.push(['kfm_rotateImage('+id+',270)',kfm_lang.RotateClockwise,'rotate_cw']);
						links.push(['kfm_rotateImage('+id+',90)',kfm_lang.RotateAntiClockwise,'rotate_ccw']);
						links.push(['kfm_resizeImage('+id+')',kfm_lang.ResizeImage,'resize_image']);
					}
					links.push(['kfm_img_startLightbox('+id+')',kfm_lang.ViewImage]);
					links.push(['kfm_returnThumbnail('+id+')',kfm_lang.ReturnThumbnailToOpener]);
					links.push(['kfm_changeCaption('+id+')',kfm_lang.ChangeCaption,'edit']);
				}
				if(kfm_inArray(extension,['zip']))links.push(['kfm_extractZippedFile("'+id+'")',kfm_lang.ExtractZippedFile,'extract_zip']);
				if(kfm_inArray(extension,viewable_extensions)){
					links.push(['x_kfm_viewTextFile('+id+',kfm_viewTextFile)','view','edit']);
					if(writable&&kfm_inArray(extension,editable_extensions))links.push(['x_kfm_getTextFile("'+id+'",kfm_showTextFile)',kfm_lang.EditTextFile,'edit',!kfm_vars.permissions.edit]);
				}
			}
			links.push(['kfm_tagAdd('+id+')',kfm_lang.AddTagsToFiles,'add_tags']);
			links.push(['kfm_tagRemove('+id+')',kfm_lang.RemoveTagsFromFiles]);
			kfm_createContextMenu(getMouseAt(getEvent(e)),links);
		}
	},
	mousedown:function(e){
		e=getEvent(e);
		if(e.button==2)return;
		var id=this.kfm_attributes.id;
		addEvent(document,'mouseup',kfm_file_dragFinish);
		clearTimeout(window.dragSelectionTrigger);
		window.dragTrigger=setTimeout('kfm_file_dragStart('+id+')',100);
	},
	padding:0
}
function kfm_buildFileDetailsTable(res){
	if(!res)return kfm_log('error: missing file details?');
	var table=newEl('table'),r;
	if(res.filename){ // filename
		r=table.addRow();
		r.addCell(0,0,newEl('strong',0,0,kfm_lang.Filename));
		r.addCell(1,0,res.filename);
	}
	if(res.filesize){ // filesize
		r=table.addRow();
		r.addCell(0,0,newEl('strong',0,0,kfm_lang.Filesize));
		r.addCell(1,0,res.filesize);
	}
	if(res.tags&&res.tags.length){ // tags
		r=table.addRow();
		r.addCell(0).addEl(newEl('strong',0,0,kfm_lang.Tags));
		var arr=[],c=r.addCell(1);
		for(var i=0;i<res.tags.length;++i){
			c.addEl(kfm_tagDraw(res.tags[i]));
			if(i!=res.tags.length-1)c.addEl(', ');
		}
	}
	if(res.mimetype){ // mimetype
		r=table.addRow();
		r.addCell(0).addEl(newEl('strong',0,0,kfm_lang.Mimetype));
		r.addCell(1).addEl(res.mimetype);
		switch(res.mimetype.replace(/\/.*/,'')){
			case 'image':{
				if(res.caption){ // caption
					r=table.addRow();
					r.addCell(0,0,newEl('strong',0,0,kfm_lang.Caption));
					r.addCell(1).innerHTML=(res.caption).replace(/\n/g,'<br \/>');
				}
				break;
			}
		}
	}
	return table;
}
function kfm_deleteFile(id){
	if(!kfm_vars.permissions.del)return kfm_alert(kfm_lang.PermissionDeniedCannotDeleteFile);
	var filename=$('kfm_file_icon_'+id).kfm_attributes.name;
	if(kfm_confirm(kfm_lang.DelFileMessage(filename))){
		kfm_filesCache[filename]=null;
		x_kfm_rm(id,kfm_refreshFiles);
	}
}
function kfm_deleteSelectedFiles(){
	var names=[],m='';
	if(selectedFiles.length>10){
		for(var i=0;i<9;++i)names.push($('kfm_file_icon_'+selectedFiles[i]).kfm_attributes.name);
		m='\n'+kfm_lang.AndNMore(selectedFiles.length-9);
	}
	else for(var i=0;i<selectedFiles.length;++i)names.push($('kfm_file_icon_'+selectedFiles[i]).kfm_attributes.name);
	if(kfm_confirm(kfm_lang.DelMultipleFilesMessage+names.join('\n')+m)){
		for(var i in selectedFiles)kfm_filesCache[selectedFiles[i]]=null;
		x_kfm_rm(selectedFiles,kfm_refreshFiles);
	}
}
function kfm_downloadFileFromUrl(){
	var url=$('kfm_url').value;
	if(url.substring(0,4)!='http'){
		kfm_log(kfm_lang.UrlNotValidLog);
		return;
	}
	var filename=url.replace(kfm_regexps.all_up_to_last_slash,''),msg='';
	do{
		var not_ok=0,o;
		filename=kfm_prompt(kfm_lang.FileSavedAsMessage+msg,filename);
		if(!filename)return;
		if(kfm_isFileInCWD(filename)){
			o=kfm_confirm(kfm_lang.AskIfOverwrite(filename));
			if(!o)not_ok=1;
		}
		if(filename.indexOf('/')>-1){
			msg=kfm_lang.NoForwardslash;
			not_ok=1;
		}
	}while(not_ok);
	x_kfm_downloadFileFromUrl(url,filename,kfm_refreshFiles);
	$('kfm_url').value='';
}
function kfm_downloadSelectedFiles(id){
	var wrapper=$('kfm_download_wrapper');
	if(!wrapper){
		wrapper=newEl('div','kfm_download_wrapper',0,0,0,'display:none');
		document.body.addEl(wrapper);
	}
	wrapper.empty();
	if(id)kfm_downloadSelectedFiles_addIframe(wrapper,id);
	else for(var i=0;i<selectedFiles.length;++i)kfm_downloadSelectedFiles_addIframe(wrapper,selectedFiles[i]);
}
function kfm_downloadSelectedFiles_addIframe(wrapper,id){
	var iframe=newEl('iframe');
	iframe.src='get.php?id='+id+'&forcedownload=1';
	wrapper.addEl(iframe);
}
function kfm_extractZippedFile(id){
	x_kfm_extractZippedFile(id,kfm_refreshFiles);
}
function kfm_isFileInCWD(filename){
	var i,files=$('kfm_right_column').fileids;
	for(i in files)if(files[i]==filename)return true;
	return false;
}
function kfm_incrementalFileDisplay(){
	var b=window.kfm_incrementalFileDisplay_vars,a=b.at,fsdata=b.data.files,wrapper=$('kfm_right_column'),fdata=fsdata[a];
	if(wrapper.contentMode!='file_icons')return (window.kfm_incrementalFileDisplay_vars=null);
	var name=fdata.name,ext=name.replace(kfm_regexps.all_up_to_last_dot,''),b,fullfilename=kfm_cwd_name+'/'+name,id=fdata.id,nameEl=newEl('span',0,'filename',name);
	var el=newEl('div','kfm_file_icon_'+id,'kfm_file_icon kfm_icontype_'+ext,0,0,'cursor:'+(Browser.isIE?'hand':'pointer'));
	var writable=fdata.writable;
	{ // add events
		addEvent(el,'click',kfm_toggleSelectedFile);
		addEvent(el,'dblclick',kfm_chooseFile);
		el.contextmenu=kfm_file_bits.contextmenu;
		addEvent(el,'mousedown',kfm_file_bits.mousedown);
		addEvent(el,'mouseover',function(){ // initialise info tooltip
			if(window.kfm_tooltipInit)clearTimeout(window.kfm_tooltipInit);
			if(window.drag_wrapper)return; // don't open if currently dragging files
			window.kfm_tooltipInit=setTimeout('x_kfm_getFileDetails('+id+',kfm_showToolTip)',500);
		});
		addEvent(el,'mouseout',function(){ // remove info tooltip
			if(window.kfm_tooltipInit)clearTimeout(window.kfm_tooltipInit);
			delEl('kfm_tooltip');
		});
	}
	{ // file attributes
		el.kfm_attributes=fdata;
		if(fdata.width){
			var url='get.php?id='+id+'&width=64&height=64';
			var img=newImg(url).setCss('width:1px;height:1px');
			img.onload=function(){
				var p=this.parentNode;
				p.setCss('backgroundImage:url("'+url+'")');
				delEl(this);
			}
			el.addEl(img);
		}
		wrapper.files[a]=el;
	}
	wrapper.addEl(el);
	var reqWidth=el.offsetWidth;
	el.appendChild(nameEl);
	el.style.width='auto';
	if(el.offsetWidth>reqWidth){
		var extension='';
		if(name.indexOf('.')>-1){
			extension=name.replace(kfm_regexps.get_filename_extension,'$1');
			name=name.replace(kfm_regexps.remove_filename_extension,'')+'.';
		}
		var nameEl=el.getElementsByTagName('span')[0];
		nameEl.delClass('filename');
		kfm_shrinkName(name,el,nameEl,'offsetWidth',reqWidth,extension);
	}
	el.style.width=reqWidth-kfm_file_bits.padding;
	if(el.offsetWidth!=reqWidth){ // padding is causing an error in width
		kfm_file_bits.padding=el.offsetWidth-reqWidth;
		el.style.width=reqWidth-kfm_file_bits.padding;
	}
	window.kfm_incrementalFileDisplay_vars.at=a+1;
	if(a+1<fsdata.length)window.kfm_incrementalFileDisplay_loader=setTimeout('kfm_incrementalFileDisplay()',((a+1)%kfm_show_files_in_groups_of?0:1));
}
function kfm_refreshFiles(res){
	if(window.kfm_incrementalFileDisplay_loader){
		clearTimeout(window.kfm_incrementalFileDisplay_loader);
		window.kfm_incrementalFileDisplay_vars=null;
	}
	kfm_selectNone();
	if(!res)return;
	if(res.toString()===res)return kfm_log(res);
	window.kfm_incrementalFileDisplay_vars={at:0,data:res};
	var a,b,fileids=[],wrapper=$('kfm_right_column').empty();
	wrapper.contentMode='file_icons';
	wrapper.addEl(newEl('div',0,'kfm_panel_header',kfm_lang.CurrentWorkingDir(res.reqdir)));
	for(var a=0;a<res.files.length;++a)fileids[a]=res.files[a].id;
	wrapper.fileids=fileids;
	wrapper.files=[];
	document.title=res.reqdir;
	kfm_lastClicked=null;
	kfm_log(kfm_lang.FilesRefreshed);
	if(res.uploads_allowed)kfm_addPanel('kfm_left_column','kfm_file_upload_panel');
	else kfm_removePanel('kfm_left_column','kfm_file_upload_panel');
	kfm_refreshPanels('kfm_left_column');
	if(!res.files.length)wrapper.addEl(newEl('span',0,'kfm_empty',kfm_lang.DirEmpty(res.reqdir)));
	else kfm_incrementalFileDisplay();
}
function kfm_renameFile(id){
	var filename=$('kfm_file_icon_'+id).kfm_attributes.name;
	var newName=kfm_prompt(kfm_lang.RenameFileToWhat(filename),filename);
	if(!newName||newName==filename)return;
	kfm_filesCache[id]=null;
	kfm_log(kfm_lang.RenamedFile(filename,newName));
	x_kfm_renameFile(id,newName,kfm_refreshFiles);
}
function kfm_renameFiles(){
	var nameTemplate='',ok=false;
	do{
		nameTemplate=kfm_prompt(kfm_lang.HowWouldYouLikeToRenameTheseFiles,nameTemplate);
		var asterisks=nameTemplate.replace(/[^*]/g,'').length;
		if(!nameTemplate)return;
		if(!/\*/.test(nameTemplate))alert(kfm_lang.YouMustPlaceTheWildcard);
		else if(/\*[^*]+\*/.test(nameTemplate))alert(kfm_lang.IfYouUseMultipleWildcards);
		else if(asterisks<(''+selectedFiles.length).length)alert(kfm_lang.YouNeedMoreThan(asterisks,selectedFiles.length));
		else ok=true;
	}while(!ok);
	for(var i=0;i<selectedFiles.length;++i)kfm_filesCache[selectedFiles[i]]=null;
	x_kfm_renameFiles(selectedFiles,nameTemplate,kfm_refreshFiles);
}
function kfm_runSearch(){
	kfm_run_delayed('search','var keywords=$("kfm_search_keywords").value,tags=$("kfm_search_tags").value;if(keywords==""&&tags=="")x_kfm_loadFiles(kfm_cwd_id,kfm_refreshFiles);else x_kfm_search(keywords,tags,kfm_refreshFiles)');
}
function kfm_showFileDetails(res){
	var fd=$('kfm_file_details_panel'),el=$('kfm_left_column');
	if(!fd){
		kfm_addPanel('kfm_left_column','kfm_file_details_panel');
		kfm_refreshPanels(el);
	}
	var body=getElsWithClass('kfm_panel_body','DIV',$('kfm_file_details_panel'))[0].empty();
	if(!res){
		body.innerHTML=kfm_lang.NoFilesSelected;
		return;
	}
	var table=kfm_buildFileDetailsTable(res);
	body.addEl(table);
}
function kfm_showToolTip(res){
	if(!res)return;
	var table=kfm_buildFileDetailsTable(res),icon=$('kfm_file_icon_'+res.id);
	if(!icon||contextmenu)return;
	table.id='kfm_tooltip';
	table.setCss('position:absolute;left:0;top:0;visibility:hidden');
	document.body.addEl(table);
	var l=getOffset(icon,'Left'),t=getOffset(icon,'Top'),w=icon.offsetWidth,h=icon.offsetHeight,ws=getWindowSize();
	l=(l+(w/2)>ws.x/2)?l-table.offsetWidth:l+w;
	table.setCss('top:'+t+'px;left:'+l+'px;visibility:visible;opacity:.9');
}
function kfm_zip(){
	var name='zipped.zip',ok=false;
	do{
		name=kfm_prompt(kfm_lang.WhatFilenameDoYouWantToUse,name);
		if(!name)return;
		if(/\.zip$/.test(name))ok=true;
		else alert(kfm_lang.TheFilenameShouldEndWithN('.zip'));
	}while(!ok);
	x_kfm_zip(name,selectedFiles,kfm_refreshFiles);
}
