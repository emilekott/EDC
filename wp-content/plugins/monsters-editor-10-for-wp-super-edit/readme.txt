=== Plugin Name ===
Contributors: Laurel
Donate link: http://www.guiguan.net/
Tags: wysiwyg, formatting, post, editor, Fckeditor, WP Super Edit, buttons, admin
Requires at least: 2.1
Tested up to: 2.3
Stable tag: trunk

Monsters Editor (MsE) brings the magic of Fckeditor back to TinyMCE.

== Description ==

![Monsters Editor (MsE) : bring the magic of Fckeditor back to TinyMCE, created by Laurel](http://www.guiguan.net/storage/2007/07/MsE_welcome.gif)

Right, as a plugin for WP Super Edit, *Monsters Editor* (MsE) brings the magic of Fckeditor back to TinyMCE. So if you prefer TinyMCE as its concision, but used to Fckeditor's powerful functions, then MsE is your good choice.

Let's take a look at [here](http://www.guiguan.net/2007/07/monsters-editor-10-for-wp-super-edit/) to see what it can do.

**Features:**

   1. Can customize Fckeditor with fckeditor_config.js
   2. Update to the newest version of Fckeditor by yourself. Just replace the "fckeditor" directory.
   3. Uses [KFM](http://kfm.verens.com/) as file browser.
   4. There is an "Wordpress Read More" button in it.
   5. Open in separate windows, which makes work more flexible.
   6. Based on [WP Super Edit](http://www.funroe.net/2007/07/21/wp-super-edit-11-updated-with-interesting-feature-bug/), so you get fully control about it.

== Installation ==

   1. install *[WP Super Edit](http://wordpress.org/extend/plugins/wp-super-edit/installation/)* (prerequisite)
   2. download *Monsters Editor 1.1 for WP Super Edit*, unzip it.
   3. modify fckeditor_config.js as you wish (optional)
   4. modify configuration.php in mse/fckeditor/editor/plugins/kfm **(important)**
         1. fill the name of your database in $kfm_db_name. Like $kfm_db_name='laurel_wordpress';
         2. fill the username of your database in $kfm_db_username. Like $kfm_db_username='laurel_wdpress';
         3. fill the password of your database in $kfm_db_password. Like $kfm_db_password='dkfjal';
         4. fill the location of your files directory, saying "/wp-content/uploads/", in both $kfm_userfiles and $kfm_userfiles_output. Like $kfm_userfiles='/wp-content/uploads/'; $kfm_userfiles_output='/wp-content/uploads/';
   5. upload the whole "mse" directory to "wp-content/plugins/superedit/tinymce_plugins" directory of your Web site.
   6. Go to the Wordpress administration panel, under the "Plugins", there is a "WP Super Edit", click it! Then click "Configure Editor Plugins". Finally, check "MsE", and click "Update Options".
   7. In "Arrange Editor Buttons", decide which place to put the "MsE" button. (By default, it is disabled, you should enable by yourself)
   8. *remember to use "**Ctrl+F5**" to clean the cache before using it the **first** time*. Enjoy!

== Frequently Asked Questions ==

= Where to get help? =

Drop all your questions here: [http://www.guiguan.net/2007/07/monsters-editor-10-for-wp-super-edit/](http://www.guiguan.net/2007/07/monsters-editor-10-for-wp-super-edit/)

== Screenshots ==

1. I'm Monsters Editor 1.1
2. To open MsE, just click the "MsE" button in TinyMCE.
3. After the MsE was launched, those content in TinyMCE will automatically transport to powerful Fckeditor.
4. Why not right click on an image? Fckeditor has beautiful context menu.
5. After click on "Image Properties", we get a dialog, which shows all the configurations of an image. Now, let's click "Browse Server".
6. What happens? Ho ho, Monsters Editor uses KFM as its file management system which is an Ajax implementation.
7. Finally, when you finished your work or want to back to TinyMCE, click the "Save" button on MsE toolbar.