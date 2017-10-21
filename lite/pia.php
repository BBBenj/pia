<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\lite;

use threedi\pia\lite\pia_cos;

/**
 * Top Poster Of The Month service.
 */
class pia
{
	/* @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\cache\service */
	protected $cache;

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\controller\helper */
	protected $path_helper;

	/* @var string phpBB root path */
	protected $root_path;

	/* @var string phpEx */
	protected $php_ext;

	/* @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\cache\service $cache, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, \phpbb\path_helper $path_helper, $root_path, $phpExt, \phpbb\template\template $template)
	{
		$this->auth				=	$auth;
		$this->cache			=	$cache;
		$this->config			=	$config;
		$this->db				=	$db;
		$this->user				=	$user;
		$this->path_helper		=	$path_helper;
		$this->root_path		=	$root_path;
		$this->php_ext			=	$phpExt;
		$this->template			=	$template;
	}

	/**
	 * Returns whether the user is authed
	 *
	 * @return bool
	 */
	public function is_authed()
	{
		return (bool) ( $this->auth->acl_get('a_pia_admin') || $this->auth->acl_get('u_allow_pia_view') );
	}

	/**
	 * Resets all user avatars
	 *
	 * @return void
	 */
	public function reset_user_avatars()
	{
		$reset_row = array(
			'user_avatar'		=> '',
			'user_avatar_type'	=> '',
			'user_avatar_width'	=> 0,
			'user_avatar_height'	=> 0
		);

		$sql6 = 'UPDATE ' . USERS_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $reset_row);
		$this->db->sql_query($sql6);
	}

	/**
	 * Resets all user pia_avatars
	 *
	 * @return void
	 */
	public function reset_user_pia_avatars()
	{
		$reset_row = array(
			'pia_avatar'		=> '',
			'pia_avatar_type'	=> '',
			'pia_avatar_width'	=> 0,
			'pia_avatar_height'	=> 0
		);

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $reset_row);
		$this->db->sql_query($sql);
	}

	/**
	 * Returns the stored configs manageables from the ACP
	 *
	 * @return array
	 */
	public function ui_configs()
	{
		//Avatar image size in pixels. Between: 16 and 256. Default: 64 (NULL)
		$size_equal = pia_cos::PIA_IMG_SIZE;
		$size_val = (int) $this->config['threedi_pia_size_val'];
		// $size2x = $size * 2; /*fare x2*/
		$size = ($size_equal . $size_val);

		//Hex color for the image background, without the hash (#). Default: ddd (NULL)
		$background_equal = pia_cos::PIA_BCGK_COL;
		$background_val = $this->config['threedi_pia_background_val']; /* random*/
		$background_val = str_replace('#', '', $background_val);
		$background = ($background_equal . $background_val);

		//Hex color for the font, without the hash (#). Default: 222 (NULL)
		$color_equal = pia_cos::PIA_TXT_COL;
		$color_val = $this->config['threedi_pia_color_val']; /* random*/
		$color_val = str_replace('#', '', $color_val);
		$color = ($color_equal . $color_val);

		//Length of the generated initials. Default: 2 NULL
		$length_equal = pia_cos::PIA_IMG_LENGHT;
		$length_val = (int) $this->config['threedi_pia_length_val'];
		$length = ($length_equal . $length_val);

		//Font size in percentage of size. Between 0.1 and 1. Default: 0.5 (NULL)
		$font_equal = pia_cos::PIA_FONT_SIZE;
		$font_val = $this->config['threedi_pia_font_val']; // phpbb_to_numeric($input)
		$font_size = ($font_equal . $font_val);

		//Boolean specifying if the returned image should be a circle. Default: false (NULL)
		$rou_equal = pia_cos::PIA_IMG_ROUNDED;
		if ((bool) $this->config['threedi_pia_rou_val'])
		{
			$rou_val = pia_cos::PIA_IS_TRUE;
		}
		else
		{
			$rou_val = pia_cos::PIA_IS_FALSE; // square img
		}
		$rounded = ($rou_equal . $rou_val);

		//Boolean Decide if the API should uppercase the name/initials. Default: true
		$upp_cas_equal = pia_cos::PIA_UPPERCASE;
		if ((bool) $this->config['threedi_pia_upp_cas_val'])
		{
			$upp_cas_val = pia_cos::PIA_IS_TRUE;
		}
		else
		{
			$upp_cas_val = pia_cos::PIA_IS_FALSE; //no uppercase
		}
		$uppercase = ($upp_cas_equal . $upp_cas_val);

		return [$size, $background, $color, $length, $font_size, $rounded, $uppercase];
	}

	/**
	 * Returns the stored configs manageables from the ACP
	 *
	 * @return array
	 */
	public function pia_main()
	{
			list($size, $background, $color, $length, $font_size, $rounded, $uppercase) = $this->ui_configs();

			$sql = 'SELECT u.username, u.user_id, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height, u.pia_avatar, u.pia_avatar_ucp
					FROM ' . USERS_TABLE . ' u
					WHERE u.user_id <> ' . ANONYMOUS . '
						AND (u.user_type <> ' . USER_IGNORE . ')
					GROUP BY u.user_id';
			$result = $this->db->sql_query($sql);

			// Loop into the data
			while ($row = $this->db->sql_fetchrow($result))
			{
				//for user "John Doe" use 'John+Doe' etc
				$name = str_replace('[', ' ', $row['username']);
				$name = str_replace(']', ' ', $row['username']);
				$name = str_replace(' ', '+', $row['username']);
				$name = str_replace('_', '+', $row['username']);
				$name = str_replace('tester_', 't+', $row['username']);

				$uiav_url = (string) $this->config['threedi_pia_uiav'];
				$uiav = (string) $uiav_url . "{$name}{$size}{$background}{$color}{$length}{$font_size}{$rounded}{$uppercase}";

//--------------------: make of all of this a couple of functions -----
				$this->db->sql_transaction('begin');

				/* Stores the PIA avatar only where the Users doesn't already have one */
				$default_row = array(
					'user_avatar'		=> (string) $uiav,
					'user_avatar_type'	=> 'avatar.driver.remote',
				);
				$sql1 = 'UPDATE ' . USERS_TABLE . '
					SET ' . $this->db->sql_build_array('UPDATE', $default_row) . '
					WHERE user_id = ' . (int) $row['user_id'] . '
						AND user_avatar ' . $this->db->sql_like_expression('' . $this->db->get_any_char()) . '
						AND pia_avatar_ucp = 1';
				$this->db->sql_query($sql1);

				/* Resets the PIA avatar only where the Users doesn't like it any more via UCP */
				$toggle_pia = array(
					'user_avatar'		=> '',
					'user_avatar_type'	=> '',
					'user_avatar_width'	=> 0,
					'user_avatar_height'	=> 0
				);
				$sql2 = 'UPDATE ' . USERS_TABLE . '
					SET ' . $this->db->sql_build_array('UPDATE', $toggle_pia) . '
					WHERE user_id = ' . (int) $row['user_id'] . '
						AND user_avatar ' . $this->db->sql_like_expression('https://ui-avatars' . $this->db->get_any_char()) . '
						AND pia_avatar_ucp = 0';
				$this->db->sql_query($sql2);

				$this->db->sql_transaction('commit');
//----------------------------------------------------------------------
			}
			$this->db->sql_freeresult($result);
	}
}
