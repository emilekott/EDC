// see ../license.txt for licensing
var kfm_kaejax_replaces_regexps=[],kfm_kaejax_replaces_replacements=[];
var kfm_kaejax_replaces={'([89A-F][A-Z0-9])':'%u00$1','22':'"','2C':',','3A':':','5B':'[','5D':']','7B':'{','7D':'}'};
for(var i in kfm_kaejax_replaces){
	kfm_kaejax_replaces_regexps.push(eval('/%'+i+'/g'));
	kfm_kaejax_replaces_replacements.push(kfm_kaejax_replaces[i]);
}
function kfm_sanitise_ajax(d){
	for(var a in window.kfm_kaejax_replaces_regexps)d=d.replace(kfm_kaejax_replaces_regexps[a],kfm_kaejax_replaces_replacements[a]);
	return d;
}
