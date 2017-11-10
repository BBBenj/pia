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
 * phpBB Initial Avatars service.
 */
class pia
{
	/* @var \phpbb\auth\auth */
	protected $auth;

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\user */
	protected $user;

	/**
	 * @var string - The database table the backup of the avatars of the users are stored in
	 */
	protected $pia_table;

	/**
	 * Constructor
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, $pia_table)
	{
		$this->auth				=	$auth;
		$this->config			=	$config;
		$this->db				=	$db;
		$this->user				=	$user;
		$this->pia_table		=	$pia_table;
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
	 * Delete all PIA user avatars
	 *
	 * @return void
	 */
	public function delete_pia_user_avatars($start = 0)
	{
		$limit = 500;

		$sql = 'SELECT user_id, user_type, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, pia_avatar_ucp
		FROM ' . USERS_TABLE . '
		WHERE user_id <> ' . ANONYMOUS . '
			AND user_avatar ' . $this->db->sql_like_expression($this->db->get_any_char() . 'ui-avatars' . $this->db->get_any_char()) . '
			AND user_avatar_type ' . $this->db->sql_like_expression('avatar.driver.remote' . $this->db->get_any_char()) . '
			AND (user_type <> ' . USER_IGNORE . ')';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		$i = 0;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$i++;

			$delete_backup_row = [
				'user_avatar'			=> '',
				'user_avatar_type'		=> '',
				'user_avatar_width'		=> 0,
				'user_avatar_height'	=> 0,
			];

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $delete_backup_row) . '
				WHERE user_id = ' . (int) $row['user_id'];
			$this->db->sql_query($sql);
		}
		$this->db->sql_freeresult($result);

		if ($i < $limit)
		{
			return;
		}

		return $start + $limit;
	}

	/**
	 * Delete PIA avatar in case (UCP choice)
	 *
	 * @return void
	 */
	public function delete_pia_ucp_avatars($block = 0)
	{
		$group = 500;
		$i = 0;

		$sql = 'SELECT user_id, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, pia_avatar_ucp
		FROM ' . USERS_TABLE . '
		WHERE pia_avatar_ucp = 0
			AND user_avatar ' . $this->db->sql_like_expression($this->db->get_any_char() . 'ui-avatars' . $this->db->get_any_char()) . '
			AND user_avatar_type ' . $this->db->sql_like_expression('avatar.driver.remote' . $this->db->get_any_char());
		$result = $this->db->sql_query_limit($sql, $group, $block);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$i++;

			$toggle_pia = array(
				'user_avatar'			=> '',
				'user_avatar_type'		=> '',
				'user_avatar_width'		=> 0,
				'user_avatar_height'	=> 0
			);
			$sql2 = 'UPDATE ' . USERS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $toggle_pia) . '
			WHERE user_id = ' . (int) $row['user_id'];
			$this->db->sql_query($sql2);
		}
		$this->db->sql_freeresult($result);

		if ($i < $group)
		{
			return;
		}

		return $block + $group;
	}

	/**
	 * Restore all user avatars
	 *
	 * @return void
	 */
	public function restore_user_avatars($block = 0)
	{
		$group = 500;
		$i = 0;

		$sql = 'SELECT pia_user_id, pia_user_avatar, pia_user_avatar_type, pia_user_avatar_width, pia_user_avatar_height
		FROM ' . $this->pia_table;
		$result = $this->db->sql_query_limit($sql, $group, $block);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$i++;

			$restore_pia_rows = array(
				'user_avatar'			=> $row['pia_user_avatar'],
				'user_avatar_type'		=> $row['pia_user_avatar_type'],
				'user_avatar_width'		=> (int) $row['pia_user_avatar_width'],
				'user_avatar_height'	=> (int) $row['pia_user_avatar_height']
			);

			$sql = 'UPDATE ' . USERS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $restore_pia_rows) . '
			WHERE user_id = ' . (int) $row['pia_user_id'] . '
				AND user_avatar ' . $this->db->sql_like_expression($this->db->get_any_char() . 'ui-avatars' . $this->db->get_any_char()) . '
				AND user_avatar_type ' . $this->db->sql_like_expression('avatar.driver.remote' . $this->db->get_any_char());
			$this->db->sql_query($sql);
		}
		$this->db->sql_freeresult($result);

		if ($i < $group)
		{
			return;
		}

		return $block + $group;
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
		$background_val = $this->config['threedi_pia_background_val']; /* @ToDo random */
		$background_val = str_replace('#', '', $background_val);
		$background = ($background_equal . $background_val);

		//Hex color for the font, without the hash (#). Default: 222 (NULL)
		$color_equal = pia_cos::PIA_TXT_COL;
		$color_val = $this->config['threedi_pia_color_val']; /* @ToDo random */
		$color_val = str_replace('#', '', $color_val);
		$color = ($color_equal . $color_val);

		//Length of the generated initials. Default: 2 NULL
		$length_equal = pia_cos::PIA_IMG_LENGHT;
		$length_val = (int) $this->config['threedi_pia_length_val'];
		$length = ($length_equal . $length_val);

		//Font size in percentage of size. Between 0.1 and 1. Default: 0.5 (NULL)
		$font_equal = pia_cos::PIA_FONT_SIZE;
		$font_val = $this->config['threedi_pia_font_val']; // use case: phpbb_to_numeric($input) ?
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

		// Boolean Decide if the API should uppercase the name/initials. Default: true
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
	 * This function replaces some chars from a string (username here)
	 *
	 * @param	string	$text	String
	 * @return	string			Amended version of the input string
	 */
	function pia_amend_username($name)
	{
		$name = str_replace(' ', '+', $name);
		$name = str_replace('_', '+', $name);
		$name = str_replace('@', '+', $name);
		$name = str_replace('.', '+', $name);
		$name = str_replace(',', '+', $name);
		$name = str_replace('[', '', $name);
		$name = str_replace(']', '', $name);
		$name = str_replace('{', '', $name);
		$name = str_replace('}', '', $name);
		$name = str_replace('(', '', $name);
		$name = str_replace(')', '', $name);

		return $name; /* @ToDo improve */
	}

	/**
	 * Executes the main thang. Only users effectively avatarless are involved
	 *
	 * @return array
	 */
	public function pia_main($start = 0)
	{
		list($size, $background, $color, $length, $font_size, $rounded, $uppercase) = $this->ui_configs();

		$limit = 500;
		$i = 0;

		$sql = 'SELECT username, user_id, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, pia_avatar_ucp
		FROM ' . USERS_TABLE . '
		WHERE user_id <> ' . ANONYMOUS . '
			AND (user_type <> ' . USER_IGNORE . ')
			AND user_avatar ' . $this->db->sql_like_expression('') . '
			AND pia_avatar_ucp = 1';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$i++;

			$name = $this->pia_amend_username($row['username']);

			$uiav_url = (string) $this->config['threedi_pia_uiav'];
			$uiav = (string) $uiav_url . "{$name}{$size}{$background}{$color}{$length}{$font_size}{$rounded}{$uppercase}";

			/* Stores the PIA avatar only where the Users doesn't already have one */
			$default_row = array(
				'user_avatar'		=> (string) $uiav,
				'user_avatar_type'	=> 'avatar.driver.remote',
			);

			$sql1 = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $default_row) . '
				WHERE user_id = ' . (int) $row['user_id'];
			$this->db->sql_query($sql1);
		}

		$this->db->sql_freeresult($result);

		if ($i < $limit)
		{
			return;
		}

		return $start + $limit;
	}

	/**
	 * Executes the main thang from ACP
	 * Refresh the PIA avatar only where the Users does already have one
	 *
	 * @return array
	 */
	public function pia_acp_main_reset($start = 0)
	{
		list($size, $background, $color, $length, $font_size, $rounded, $uppercase) = $this->ui_configs();

		$limit = 500;
		$i = 0;

		$sql = 'SELECT username, user_id, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height, pia_avatar_ucp
		FROM ' . USERS_TABLE . '
		WHERE user_id <> ' . ANONYMOUS . '
			AND (user_type <> ' . USER_IGNORE . ')
			AND user_avatar ' . $this->db->sql_like_expression($this->db->get_any_char() . 'ui-avatars' . $this->db->get_any_char()) . '
			AND user_avatar_type ' . $this->db->sql_like_expression('avatar.driver.remote' . $this->db->get_any_char()) . '
			AND pia_avatar_ucp = 1';
		$result = $this->db->sql_query_limit($sql, $limit, $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$i++;

			$name = $this->pia_amend_username($row['username']);

			$uiav_url = (string) $this->config['threedi_pia_uiav'];
			$uiav = (string) $uiav_url . "{$name}{$size}{$background}{$color}{$length}{$font_size}{$rounded}{$uppercase}";

			$default_row = array(
				'user_avatar'		=> (string) $uiav,
				'user_avatar_type'	=> 'avatar.driver.remote',
			);

			$sql1 = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $default_row) . '
				WHERE user_id = ' . (int) $row['user_id'];
			$this->db->sql_query($sql1);
		}

		$this->db->sql_freeresult($result);

		if ($i < $limit)
		{
			return;
		}

		return $start + $limit;
	}

}
