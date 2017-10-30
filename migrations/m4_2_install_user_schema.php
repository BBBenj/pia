<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\migrations;

/*
 * Adds the needed indexes to the USERS_TABLE
 */
class m4_2_install_user_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		/* If doesn't exists go ahead */
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'pia_user_avatar');
	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v32x\v321',];
	}

	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'users'	=>	[
					'pia_user_avatar'		=> ['VCHAR:255', '', 'after' => 'pia_avatar_ucp'],
					'pia_avatar_type'		=> ['VCHAR:255', '', 'after' => 'pia_user_avatar'],
					'pia_avatar_width'		=> ['USINT', 0, 'after' => 'pia_avatar_type'],
					'pia_avatar_height'		=> ['USINT', 0, 'after' => 'pia_avatar_width'],
				],
			],
		];
	}
}
