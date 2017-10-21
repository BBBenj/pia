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
	'ACL_A_PIA_ADMIN'		=> 'Allow administering phpBB Initial Avatars',
	'ACL_U_ALLOW_PIA_VIEW'	=> 'Allow to use phpBB Initial Avatars',
	'ACL_U_ALLOW_PIA_UCP'	=> 'Allow the UCP of phpBB Initial Avatars',
));
