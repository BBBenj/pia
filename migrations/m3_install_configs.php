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

class m3_install_configs extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v32x\v321',];
	}

	public function update_data()
	{
		return [
			/* First set a milestone */
			['config.add', ['threedi_pia_default_avatar', 0]],
			['config.add', ['threedi_pia_uiav', 'https://ui-avatars.com/api/?name=']],
			['config.add', ['threedi_pia_size_val', 90]],
			['config.add', ['threedi_pia_background_val', '#0D8ABC']],
			['config.add', ['threedi_pia_color_val', '#ffffff']],
			['config.add', ['threedi_pia_length_val', 4]],
			['config.add', ['threedi_pia_font_val', '0.30']],
			['config.add', ['threedi_pia_rou_val', 1]],
			['config.add', ['threedi_pia_upp_cas_val', 0]],
		];
	}
}
