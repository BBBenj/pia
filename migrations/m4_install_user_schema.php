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
class m4_install_user_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{

	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v31x\v3111'];
	}

	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'users'	=>	[
					'pia_avatar'			=> ['VCHAR:255', '', 'after' => 'user_avatar_height'],
					'pia_avatar_type'		=> ['VCHAR:255', '', 'after' => 'pia_avatar'],
					'pia_avatar_width'		=> ['USINT', 0, 'after' => 'pia_avatar_type'],
					'pia_avatar_height'		=> ['USINT', 0, 'after' => 'pia_avatar_width'],
					'pia_avatar_ucp'		=> ['BOOL', 1, 'after' => 'pia_avatar_height'],
				],
			],
		];
	}

	public function revert_schema()
	{
			return [
			'drop_columns'	=>[
				$this->table_prefix . 'users'	=>	[
					'pia_avatar',
					'pia_avatar_type',
					'pia_avatar_width',
					'pia_avatar_height',
					'pia_avatar_ucp',
				],
			],
		];
	}
}
