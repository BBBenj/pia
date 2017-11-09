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

class m1_install_perms extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v32x\v321',];
	}

	public function update_data()
	{
		return [
			['permission.add', ['u_allow_pia_view']],
			['permission.permission_set', ['REGISTERED', 'u_allow_pia_view', 'group']],
			['permission.add', ['u_allow_pia_ucp']],
			['permission.permission_set', ['REGISTERED', 'u_allow_pia_ucp', 'group']],
			['permission.add', ['a_pia_admin']],
			['permission.permission_set', ['ADMINISTRATORS', 'a_pia_admin', 'group']],
		];
	}
}
