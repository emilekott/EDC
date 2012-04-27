function saveContent() {
	tinyMCE.setContent(FCKeditorAPI.GetInstance('htmlSource').GetXHTML());
	tinyMCE.closeWindow(window);
}

function onLoadInit() {
	tinyMCEPopup.resizeToInnerSize();

	// Remove Gecko spellchecking
	if (tinyMCE.isGecko)
		document.body.spellcheck = tinyMCE.getParam("gecko_spellcheck");

	document.getElementById('htmlSource').value = tinyMCE.getContent(tinyMCE.getWindowArg('editor_id'));
}