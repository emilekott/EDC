// see license.txt for licensing
function kfm_changeDirectory(id){
	setTimeout('clearTimeout(window.dragTrigger);',1);
	var el=$(id),a,els=getElsWithClass('kfm_directory_open','td');
	if(browser.isIE)while(el&&!el.node_id)el=el.parentNode;
	kfm_cwd_name=el.kfm_directoryname;
	kfm_cwd_id=el.node_id;
	for(var a=0;a<els.length;++a)els[a].delClass('kfm_directory_open');
	el.parentNode.addClass('kfm_directory_open');
	setTimeout('x_kfm_loadFiles(kfm_cwd_id,kfm_refreshFiles);x_kfm_loadDirectories(kfm_cwd_id,kfm_refreshDirectories);',20);
}
function kfm_createDirectory(id){
	var newName=kfm_prompt(kfm_lang.CreateDirMessage(kfm_directories[id].pathname),kfm_lang.NewDirectory);
	if(newName&&newName!=''&&!/\/|^\./.test(newName))x_kfm_createDirectory(id,newName,kfm_refreshDirectories);
}
function kfm_deleteDirectory(id){
	if(kfm_confirm(kfm_lang.DelDirMessage(kfm_directories[id].pathname)))x_kfm_deleteDirectory(id,kfm_deleteDirectoryCheck);
}
function kfm_deleteDirectoryCheck(res){
	if(res.type&&res.type=='error'){
		switch(parseInt(res.msg)){
			case 1: kfm_log('error: '+kfm_lang.IllegalDirectoryName(res.name)); break;
			case 2:{ // not empty
				var ok=kfm_confirm(kfm_lang.RecursiveDeleteWarning(res.name));
				if(ok)x_kfm_deleteDirectory(res.id,1,kfm_deleteDirectoryCheck);
				break;
			}
			case 3: kfm_log('error: '+kfm_lang.RmdirFailed(res.name)); break;
			case 4: kfm_log('error: '+kfm_lang.DirectoryNotInDb); break;
			default: alert(res.msg);
		}
	}
	else kfm_refreshDirectories(res);
}
function kfm_dir_addLink(t,name,parent_addr,is_last,has_node_control,parent){
	var r=t.addRow(),c,pdir=parent_addr+name,name=(name==''?'root':name),name_text=newEl('span','directory_name_'+parent,0,'0');
	var el=X(newEl('div','kfm_directory_icon_'+parent,'kfm_directory_link '+(kfm_cwd_name==pdir?'':'kfm_directory_open'),name_text),{
		kfm_directoryname:pdir,
		node_id:parent,
		contextmenu:function(e){
			var links=[],i,node_id=this.node_id;
			links.push(['kfm_renameDirectory("'+node_id+'")',kfm_lang.RenameDir]);
			links.push(['kfm_createDirectory("'+node_id+'")',kfm_lang.CreateSubDir,'folder_new']);
			if(node_id!=1)links.push(['kfm_deleteDirectory("'+node_id+'")',kfm_lang.DeleteDir,'remove']);
			kfm_createContextMenu(getMouseAt(getEvent(e)),links);
		}
	}).setCss('cursor:'+(Browser.isIE?'hand':'pointer'));
	r.addCell(0,0,(has_node_control?newLink('javascript:kfm_dir_openNode('+parent+')','[+]','kfm_dir_node_'+parent,'kfm_dir_node_closed'):newEl('span','kfm_dir_node_'+parent,0,' ')),'kfm_dir_lines_'+(is_last?'lastchild':'child'));
	r.addCell(1,0,el,'kfm_dir_name');
	addEvent(el,'click',function(){
		kfm_changeDirectory(this.id);
	});
	addEvent(el,'mouseout',function(){
		kfm_directory_over=0;
		this.delClass('hovered');
	});
	addEvent(el,'mouseover',function(){
		if(!kfm_directory_over)kfm_directory_over=parseInt(this.node_id);
	});
	if(parent!=1)addEvent(el,'mousedown',(function(id){
		return function(e){
			if(e.button==2)return;
			addEvent(document,'mouseup',kfm_dir_dragFinish);
			clearTimeout(window.dragTrigger);
			window.dragTrigger=setTimeout(function(){
				kfm_dir_dragStart(id);
			},100);
		};
	})(parent));
	{ // fix name width
		var reqHeight=name_text.offsetHeight;
		name_text.innerHTML='. '+name;
		el=name_text;
		el.style.position='absolute';
		if(reqHeight&&el.offsetHeight>reqHeight){
			el.title=name;
			kfm_shrinkName(name,el,el,'offsetHeight',reqHeight,'');
		}
		else el.innerHTML=name;
		if(!browser.isIE)el.style.position='inherit';
	}
	{ // subdir holder
		r=t.addRow();
		r.addCell(0,0,' ',is_last?0:'kfm_dir_lines_nochild');
		r.addCell(1).id='kfm_directories_subdirs_'+parent;
	}
	return t;
}
function kfm_dir_drag(e){
	if(!window.dragType||window.dragType!=3)return;
	var m=getMouseAt(e);
	clearSelections();
	window.drag_wrapper.setCss('display:block;left:'+(m.x+16)+'px;top:'+m.y+'px');
}
function kfm_dir_dragFinish(e){
	clearTimeout(window.dragTrigger);
	if(!window.dragType||window.dragType!=3)return;
	window.dragType=0;
	removeEvent(document,'mousemove',kfm_dir_drag);
	removeEvent(document,'mouseup',kfm_dir_dragFinish);
	var dir_from=window.drag_wrapper.dir_id;
	delEl(window.drag_wrapper);
	window.drag_wrapper=null;
	dir_to=kfm_directory_over;
	if(dir_to==0||dir_to==dir_from)return;
	x_kfm_moveDirectory(dir_from,dir_to,kfm_refreshDirectories);
	kfm_selectNone();
}
function kfm_dir_dragStart(pid){
	window.dragType=3;
	var w=getWindowSize();
	window.drag_wrapper=X(newEl('div','kfm_drag_wrapper','directory',0,0,'display:none;opacity:.7'),{dir_id:pid});
	window.drag_wrapper.addEl($('kfm_directory_icon_'+pid).kfm_directoryname);
	document.body.addEl(window.drag_wrapper);
	addEvent(document,'mousemove',kfm_dir_drag);
}
function kfm_dir_openNode(dir){
	var node=$('kfm_dir_node_'+dir);
	node.setClass('kfm_dir_node_opened');
	node.href=node.href.replace(/open/,'close');
	$('kfm_directories_subdirs_'+dir).empty().addEl(kfm_lang.Loading);
	x_kfm_loadDirectories(dir,kfm_refreshDirectories);
}
function kfm_dir_closeNode(dir){
	var node=$('kfm_dir_node_'+dir);
	node.setClass('kfm_dir_node_closed');
	node.href=node.href.replace(/close/,'open');
	$('kfm_directories_subdirs_'+dir).empty();
}
function kfm_refreshDirectories(res){
	if(res.toString()===res)return kfm_log(res);
	var d=res.parent;
	if(d==1){ // root node
		var p=$('kfm_directories');
		p.parentNode.replaceChild(kfm_dir_addLink(newEl('table','kfm_directories'),'','',1,0,1),p);
		$('kfm_directory_icon_1').parentNode.addClass('kfm_directory_open');
	}
	var t=newEl('table'),n='kfm_dir_node_'+d,ln=0;
	dirwrapper=$('kfm_directories_subdirs_'+d).empty().addEl(t);
	for(a in res.directories)ln++; // count sub directories
	for(a in res.directories){ // show subdirectories
		kfm_dir_addLink(t,res.directories[a][0],res.reqdir,l=(a==ln-1),res.directories[a][1],res.directories[a][2]);
		kfm_directories[res.directories[a][2]]={name:res.directories[a][0],pathname:res.reqdir+res.directories[a][0]};
	}
	if(d!='')$(n).parentNode.empty().addEl(ln?newLink('javascript:kfm_dir_closeNode("'+res.parent+'")','[-]',n,'kfm_dir_node_open'):newEl('span',n,0,' '));
	kfm_cwd_subdirs[d]=res.directories;
	if(!kfm_cwd_subdirs[d])kfm_dir_openNode(res.parent);
	kfm_setDirectoryProperties(res.properties);
	kfm_selectNone();
	kfm_log(kfm_lang.DirRefreshed);
	kfm_directories[kfm_cwd_id]=res.properties;
	kfm_refreshPanels('kfm_left_column');
}
function kfm_renameDirectory(id){
	var directoryName=kfm_directories[id].name;
	var newName=kfm_prompt(kfm_lang.RenameTheDirectoryToWhat(directoryName),directoryName);
	if(!newName||newName==directoryName)return;
	kfm_directories[id]=null;
	kfm_log(kfm_lang.RenamedDirectoryAs(directoryName,newName));
	x_kfm_renameDirectory(id,newName,kfm_refreshDirectories);
}
function kfm_setDirectoryProperties(properties){
	if(!$('kfm_directory_properties'))return;
	var wrapper=$('kfm_directory_properties').empty();
	wrapper.properties=properties;
	var table=newEl('table'),row,cell,i;
	{ // directory name
		i=properties.allowed_file_extensions.length?properties.allowed_file_extensions.join(', '):kfm_lang.NoRestrictions;
		row=table.addRow();
		row.addCell(0,0,newEl('strong',0,0,kfm_lang.Name));
		row.addCell(1,0,'/'+kfm_cwd_name);
	}
	{ // allowed file extensions
		i=properties.allowed_file_extensions.length?properties.allowed_file_extensions.join(', '):kfm_lang.NoRestrictions;
		row=table.addRow();
		row.addCell(0,0,newEl('strong',0,0,kfm_lang.AllowedFileExtensions));
		row.addCell(1,0,i);
	}
	wrapper.addEl(table);
}
