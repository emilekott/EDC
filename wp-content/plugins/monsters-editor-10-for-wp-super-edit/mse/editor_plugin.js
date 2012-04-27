tinyMCE.importPluginLanguagePack('mse'); var TinyMCE_MsEPlugin = { getInfo : function() { return { longname : 'Monsters Editor', author : 'Laurel', authorurl : 'http://www.guiguan.net', infourl : 'http://www.guiguan.net', version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
};}, getControlHTML : function(cn) { switch (cn) { case "mse":
return tinyMCE.getButtonHTML(cn, 'lang_mse_desc', '{$pluginurl}/images/MsE.gif', 'MsE');}
return "";}, execCommand : function(editor_id, element, command, user_interface, value) { switch (command) { case "MsE":
var template = new Array(); template['file'] = this.baseURL + '/MsE.php'; template['width'] = 685; template['height'] = 360; tinyMCE.openWindow(template, {editor_id : editor_id, resizable : "yes", scrollbars : "no", inline : "no"}); return true;}
return false;}
}; tinyMCE.addPlugin('mse', TinyMCE_MsEPlugin); 