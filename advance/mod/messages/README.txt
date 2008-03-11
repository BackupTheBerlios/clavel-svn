Add the following lines to the .htacess file:

# Messages Module
##################
RewriteRule ^([A-Za-z0-9]+)\/messages/$ mod/messages/index.php?profile_name=$1
RewriteRule ^([A-Za-z0-9]+)\/messages\/msg_offset/([0-9]+)$ mod/messages/index.php?profile_name=$1&msg_offset=$2
RewriteRule ^([A-Za-z0-9]+)\/messages\/sent$ mod/messages/index.php?profile_name=$1&sent=1
RewriteRule ^([A-Za-z0-9]+)\/messages\/sent\/msg_offset/([0-9]+)$ mod/messages/index.php?profile_name=$1&sent=1&msg_offset=$2
RewriteRule ^([A-Za-z0-9]+)\/messages\/compose$ mod/messages/compose.php?profile_name=$1
RewriteRule ^([A-Za-z0-9]+)\/messages\/view\/([0-9]+)\/([0-1])$ mod/messages/view.php?profile_name=$1&message=$2&sent=$3

Configuration Options
=====================

By default the plugin disables the 'Send Message' sidebar link for communities where
you are not member. 

If you want to change this behavior you must change the value of MESSAGES_SIDEBAR_NO_MEMBER_LINK
in the 'messages/lib/messages_config.php' file.

By default the plugin enable send messages for communities. 

If you want to change this behavior you must change the value of MESSAGES_COMMUNITY_MESSAGES in 
the 'messages/lib/messages_config.php' file.