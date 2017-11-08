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
 * Adds the needed PIA_TABLE and populates it with the backup of all avatars
 */
class m4_2_install_user_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		/* If does not exists go ahead */
		return $this->db_tools->sql_table_exists($this->table_prefix . 'pia');
	}

	static public function depends_on()
	{
		return ['\threedi\pia\migrations\m4_install_user_schema'];
	}

	public function update_schema()
	{
		return [
			'add_tables'		=> [
				$this->table_prefix . 'pia'	=> [
					'COLUMNS'		=>	[
							'pia_user_id'				=> ['UINT', null, 'auto_increment'],
							'pia_user_avatar'			=> ['VCHAR', ''],
							'pia_user_avatar_type'		=> ['VCHAR:255', ''],
							'pia_user_avatar_width'		=> ['USINT', 0],
							'pia_user_avatar_height'	=> ['USINT', 0],
						],
					'PRIMARY_KEY'	=> 'pia_user_id',
				],
			],
		];
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'backup_user_avatar']]],
		];
	}

	public function backup_user_avatar()
	{
		$sql = 'INSERT INTO ' . $this->table_prefix . 'pia' . " (pia_user_id, pia_user_avatar, pia_user_avatar_type, pia_user_avatar_width, pia_user_avatar_height) SELECT user_id, user_avatar, user_avatar_type, user_avatar_width, user_avatar_height FROM " . USERS_TABLE . ' WHERE user_id <> ' . ANONYMOUS . ' AND (user_type <> ' . USER_IGNORE . ')';
		$this->db->sql_query($sql);
	}

	public function revert_schema()
	{
		return [
			'drop_tables'		=> [
				$this->table_prefix . 'pia',
			],
		];
	}
}
