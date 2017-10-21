<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\ucp;

/**
 * phpBB Initial Avatars UCP module info.
 */
class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\threedi\pia\ucp\main_module',
			'title'		=> 'UCP_PIA_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'UCP_PIA',
					'auth'	=> 'ext_threedi/pia && acl_u_allow_pia_ucp',
					'cat'	=> array('UCP_PIA_TITLE')
				),
			),
		);
	}
}
