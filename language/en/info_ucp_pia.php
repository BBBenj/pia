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
	$lang = [];
}

$lang = array_merge($lang, array(
	'UCP_PIA'				=> 'Settings',
	'UCP_PIA_TITLE'			=> 'phpBB Initial Avatars',
	'UCP_PIA_USER'			=> 'Use default avatar',
	'UCP_PIA_USER_EXPLAIN'	=> 'Remember it goes away automatically once you get an Avatar.',
	'UCP_PIA_SAVED'			=> 'Settings have been saved successfully!',
));
