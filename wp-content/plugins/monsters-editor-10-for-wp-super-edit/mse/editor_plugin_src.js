/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.00 $
 * $Date: 2007/07/20 1:43 $
 *
 * @author Laurel
 * @version 1.0
 * @copyright Copyright ?2007, Gui Guan's BLOG, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('mse');

// Plucin static class
var TinyMCE_MsEPlugin = {
	getInfo : function() {
		return {
			longname : 'Monsters Editor',
			author : 'Laurel',
			authorurl : 'http://www.guiguan.net',
			infourl : 'http://www.guiguan.net',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},
	
	/**
	 * Returns the HTML contents of the emotions control.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "mse":
				return tinyMCE.getButtonHTML(cn, 'lang_mse_desc', '{$pluginurl}/images/MsE.gif', 'MsE');
		}

		return "";
	},

	/**
	 * Executes the MsE command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "MsE":
				var template = new Array();

				template['file'] = this.baseURL + '/MsE.php'; // Relative to theme
				template['width'] = 685;
				template['height'] = 360;

				tinyMCE.openWindow(template, {editor_id : editor_id, resizable : "yes", scrollbars : "no", inline : "no"});

				return true;
		}

		// Pass to next handler in chain
		return false;
	}
};

// Register plugin
tinyMCE.addPlugin('mse', TinyMCE_MsEPlugin);
