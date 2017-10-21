<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_PIA_TITLE'				=> 'phpBB Initial Avatars',
	'ACP_PIA_SETTINGS'			=> 'Settings',
	'ACP_PIA_SETTING_SAVED'		=> 'phpBB Initial Avatars Settings saved.',
	// error logs
	'PIA_LOG_REMOTE_CONFIG_INVALID'	=> '<strong>PIA</strong> works only if you allow remote avatars in ACP',
));
