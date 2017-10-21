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

class m5_install_ucp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'ucp'
				AND module_langname = 'UCP_PIA_TITLE'";
		$result = $this->db->sql_query($sql);
		$module_id = $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		return $module_id !== false;
	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v31x\v3111'];
	}

	public function update_data()
	{
		return [
			['module.add', [
				'ucp',
				0,
				'UCP_PIA_TITLE',
			]],
			['module.add', [
				'ucp',
				'UCP_PIA_TITLE',
				[
					'module_basename'	=> '\threedi\pia\ucp\main_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
