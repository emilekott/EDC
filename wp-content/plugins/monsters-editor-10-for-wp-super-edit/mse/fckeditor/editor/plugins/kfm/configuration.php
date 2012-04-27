<?php
# see license.txt for licensing

# user access details. all users may use get.php without logging in, but
#   if the following details are filled in, then login will be required
#   for the main KFM application
# for more details, see http://kfm.verens.com/security
$kfm_username='';
$kfm_password='';

# what type of database to use
$kfm_db_type='mysql'; # values allowed: sqlite, mysql, pgsql

# the following options should only be filled if you are not using sqlite as the database
$kfm_db_prefix='kfm_';
$kfm_db_host='localhost';
$kfm_db_name='';
$kfm_db_username='';
$kfm_db_password='';

# where are the files located on the hard-drive, relative to the website's root directory?
# In the default example, the user-files are at http://kfm.verens.com/sandbox/UserFiles/
# Note that this is the actual file-system location of the files.
# This value must end in '/'.
$kfm_userfiles='';

# what should be added to the server's root URL to find the URL of the user files?
# Note that this is usually the same as $kfm_userfiles, but could be different in the case
#   that the server uses mod_rewrite or personal web-sites, etc
# Use the value 'get.php' if you want to use the KFM file handler script to manager file downloads.
# If you are not using get.php, this value must end in '/'.
# Examples:
#   $kfm_userfiles_output='http://thisdomain.com/files/';
#   $kfm_userfiles_output='/files/';
#   $kfm_userfiles_output='http://thisdomain.com/kfm/get.php';
#   $kfm_userfiles_output='/kfm/get.php';
$kfm_userfiles_output='';

# if you want to hide any panels, add them here as a comma-delimited string
# for example, $kfm_hidden_panels='logs,file_details,file_upload,search,directory_properties';
$kfm_hidden_panels='logs';

# what happens if someone double-clicks a file or presses enter on one?
$kfm_file_handler='fckeditor'; # values allowed: download, fckeditor

# directory in which KFM keeps its database and generated files
$kfm_workdirectory = '.files';

# 1 = users are allowed to delete files
# 0 = users are not allowed to delete files
$kfm_allow_file_delete=1;

# 1 = users are allowed to edit files
# 0 = users are not allowed to edit files
$kfm_allow_file_edit=1;

# 1 = users are allowed to move files
# 0 = users are not allowed to move files
$kfm_allow_file_move=1;

# 1 = users are allowed to upload files
# 0 = user are not allowed upload files
$kfm_allow_file_uploads=1;

# use this array to ban dangerous files from being uploaded.
$kfm_banned_extensions=array('php','cfm','asp','cgi','pl');

# this array tells KFM what extensions indicate files which may be edited online.
$kfm_editable_extensions=array('css','html','js','txt','xhtml','xml');

# this array tells KFM what extensions indicate files which may be viewed online.
# the contents of $kfm_editable_extensions will be added automatically.
$kfm_viewable_extensions=array('sql','php');

# 0 = only errors will be logged
# 1 = everything will be logged
$kfm_log_level=0;

# use this array to show the order in which language files will be checked for
$kfm_preferred_languages=array('en','de','da','es','fr','nl','ga');

# themes are located in ./themes/
# to use a different theme, replace 'default' with the name of the theme's directory.
$kfm_theme='default';

# where is the 'convert' program kept, if you have it installed?
$kfm_imagemagick_path='/usr/bin/convert';

# show files in groups of 'n', where 'n' is a number (helps speed up files display - use low numbers for slow machines)
$kfm_show_files_in_groups_of=10;

# what directory is KFM in? this is very important for CMS's that use KFM's API functions,
#   but probably not important at all to the casual user.
$kfm_base_path='';

# we would like to keep track of installations, to see how many there are, and what versions are in use.
# if you do not want us to have this information, then set the following variable to '1'.
$kfm_dont_send_metrics=1;

define('ERROR_LOG_LEVEL',1); # 0=none, 1=errors, 2=1+warnings, 3=2+notices, 4=3+unknown
require_once($kfm_base_path.'initialise.php');

?>
