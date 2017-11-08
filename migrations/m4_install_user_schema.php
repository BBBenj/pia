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
 * Creates the PIA's table and backups the User avatars's lot
 */
class m4_install_user_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		/* If does exists go ahead */
		return !$this->db_tools->sql_table_exists($this->table_prefix . 'users');
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
					'pia_avatar_ucp'	=> ['BOOL', 1, 'after' => 'user_avatar_height']
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'	=>[
				$this->table_prefix . 'users'	=>	[
					'pia_avatar_ucp'
				],
			]
		];
	}
}
