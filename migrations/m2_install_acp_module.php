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

class m2_install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{

	}

	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v31x\v3111'];
	}

	public function update_data()
	{
		return [
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_PIA_TITLE',
			]],
			['module.add', [
				'acp',
				'ACP_PIA_TITLE',
				[
					'module_basename'	=> '\threedi\pia\acp\pia_module',
					'modes'				=> ['settings'],
				],
			]],
		];
	}
}
