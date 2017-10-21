<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\acp;

/**
 * phpBB Initial Avatars ACP module info.
 */
class pia_info
{
	public function module()
	{
		return array(
			'filename'	=> '\threedi\pia\acp\pia_module',
			'title'		=> 'ACP_PIA_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_PIA_SETTINGS',
					'auth'	=> 'ext_threedi/pia && acl_a_pia_admin',
					'cat'	=> array('ACP_PIA_TITLE')
				),
			),
		);
	}
}
