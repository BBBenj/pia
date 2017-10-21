
threedi_pia_size_val
threedi_pia_background_val
threedi_pia_color_val
threedi_pia_length_val
threedi_pia_font_val
threedi_pia_rou_val
threedi_pia_uiav




class m3_install_configs extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		/**
		 * If does exists go ahead
		 */
		return !phpbb_version_compare($this->config['threedi_pia'], '1.0.0-dev', '>=');
	}

	static public function depends_on()
	{
		return ['\threedi\tpotm\migrations\m2_install_acp_module'];
	}

	public function update_data()
	{

			['config.add', ['threedi_pia_size_val', 128]],
			['config.add', ['threedi_pia_background_val', '0D8ABC']],
			['config.add', ['threedi_pia_color_val', 'ffffff']],
			['config.add', ['threedi_pia_length_val', 3]],
			['config.add', ['threedi_pia_font_val', '0.33']],
			['config.add', ['threedi_pia_rou_val', 1]],
			['config.add', ['threedi_pia_uiav', 'https://ui-avatars.com/api/?name=', true]],

		];
	}
}
