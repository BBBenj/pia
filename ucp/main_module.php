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
 * phpBB Initial Avatars UCP module.
 */
class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $request, $template, $user;

		$this->tpl_name = 'ucp_pia_body';
		$this->page_title = $user->lang('UCP_PIA_TITLE');
		add_form_key('threedi/pia');

		$data = array(
			'pia_avatar_ucp' => $request->variable('pia_avatar_ucp', $user->data['pia_avatar_ucp']),
		);

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('threedi/pia'))
			{
				trigger_error($user->lang('FORM_INVALID'));
			}

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $db->sql_build_array('UPDATE', $data) . '
				WHERE user_id = ' . $user->data['user_id'];
			$db->sql_query($sql);

			meta_refresh(3, $this->u_action);
			$message = $user->lang('UCP_PIA_SAVED') . '<br /><br />' . $user->lang('RETURN_UCP', '<a href="' . $this->u_action . '">', '</a>');
			trigger_error($message);
		}

		$template->assign_vars(array(
			'S_USER_PIA'	=> (bool) $data['pia_avatar_ucp'],
			'S_UCP_ACTION'	=> $this->u_action,
		));
	}
}
