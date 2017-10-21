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
	'DEFAULT_AVATAR'				=>	'PIA avatar',
	// Translators please do not change the following line, no need to translate it!
	'PIA_CREDIT_LINE'	=>	' | <a href="https://github.com/3D-I/pia">phpBB Initial Avatars</a> &copy; 2017 - 3Di',
));
