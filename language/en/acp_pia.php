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
	'PIA_DEFAULT_AVATAR_FIELD'			=>	'Default avatar',
	'PIA_DEFAULT_AVATAR'				=>	'Use Initial avatars',
	'PIA_DEFAULT_AVATAR_EXPLAIN'		=>	'Store in the USERS TABLE the link to the PIA Avatar where the user is avatarless. Users can chose to avoid it via UCP <em>(default is allowed for all)</em>. Selecting NO in the future will only affects new users. PIA Avatars can be removed then if the users selects a custom avatar.',

	// Img size
	'PIA_IMG_SIZE'						=>	'Avatar image size',
	'PIA_IMG_SIZE_EXPLAIN'				=>	'In pixels, between 16 and 256.',

	// Img colors
	'PIA_COLORPICKER_EXPLAIN'			=>	'Input a color in #HexDec value or use the color-picker.',
	'PIA_COLORPICKER_STORED'			=>	'Color #HexDec value and actual color stored in the DB.',
	'PIA_COL_VAL_STORED'				=>	'Now',
	'PIA_BCGK_COL_VAL'					=>	'Hex color for the image background',
	'PIA_TXT_COL_VAL'					=>	'Hex color for the font',

	// Initials lenght
	'PIA_IN_LENGHT_VAL'					=>	'Lenght',
	'PIA_IN_LENGHT_VAL_EXPLAIN'			=>	'How many chars for the generated initials.',

	// Font size
	'PIA_FONT_SIZE_VAL'					=>	'Font size - Fix the value again if submit',
	'PIA_FONT_SIZE_VAL_EXPLAIN'			=>	'In percentage, between 0.1 and 1.',

	// Rounded
	'PIA_IMG_ROUNDED_VAL'				=>	'Shape',
	'PIA_IMG_ROUNDED_VAL_EXPLAIN'		=>	'Should the returned image be a circle?',

	// Uppercase
	'PIA_UPPERCASE_VAL'					=>	'Case',
	'PIA_UPPERCASE_VAL_EXPLAIN'			=>	'Should the name/initials be Uppercased?',

	// Errors
	'ACP_PIA_ERRORS'					=>	'Errors explaination',
	'PIA_REMOTE_CONFIG_INVALID'			=>	'The extension works only if you allow remote avatars in ACP',
));
