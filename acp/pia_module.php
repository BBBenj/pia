<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\acp;

/**
 * phpBB Initial Avatars ACP module.
 */
class pia_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	public function main($id, $mode)
	{
		global $config, $request, $template, $user, $phpbb_container, $phpbb_log;

		$pia_lite = $phpbb_container->get('threedi.pia.pia');

		$user->add_lang_ext('threedi/pia', 'acp_pia');
		$this->tpl_name = 'pia_body';
		$this->page_title = $user->lang('ACP_PIA_TITLE');
		add_form_key('threedi/pia');

		/* Do this now and forget */
		$errors = array();

		if ($request->is_set_post('submit'))
		{
			if (!check_form_key('threedi/pia'))
			{
				trigger_error('FORM_INVALID', E_USER_WARNING);
			}

			if (!$config['allow_avatar_remote'])
			{
				$errors[] = $user->lang('PIA_REMOTE_CONFIG_INVALID');
				/* Log the error. */
				$phpbb_log->add('critical', $user->data['user_id'], $user->ip, 'PIA_LOG_REMOTE_CONFIG_INVALID');
			}

			/* No errors? Great, let's go. */
			if (!count($errors))
			{
				$config->set('threedi_pia_default_avatar', $request->variable('threedi_pia_default_avatar', (int) $config['threedi_pia_default_avatar']));

				/* Configs */
				$config->set('threedi_pia_size_val', $request->variable('threedi_pia_size_val', (int) $config['threedi_pia_size_val']));
				$config->set('threedi_pia_background_val', trim($request->variable('threedi_pia_background_val', $config['threedi_pia_background_val'])));
				$config->set('threedi_pia_color_val', trim($request->variable('threedi_pia_color_val', $config['threedi_pia_color_val'])));
				$config->set('threedi_pia_length_val', $request->variable('threedi_pia_length_val', (int) $config['threedi_pia_length_val']));
				$config->set('threedi_pia_font_val', $request->variable('threedi_pia_font_val', (double) $config['threedi_pia_font_val']));
				$config->set('threedi_pia_rou_val', $request->variable('threedi_pia_rou_val', (bool) $config['threedi_pia_rou_val']));
				$config->set('threedi_pia_upp_cas_val', $request->variable('threedi_pia_upp_cas_val', (bool) $config['threedi_pia_upp_cas_val']));

				/* Resets avatars for the changes to make effect - Are cached server-side */
				if ( $pia_lite->is_authed() && $config['threedi_pia_default_avatar'] && $config['allow_avatar'] && $config['allow_avatar_remote'])
				{
					$pia_lite->pia_acp_main_reset();
				}

				/* Log the action and return */
				$phpbb_log->add('admin', $user->data['user_id'], $user->ip, 'PIA_LOG_CONFIG_SAVED');
				trigger_error($user->lang('ACP_PIA_SETTING_SAVED') . adm_back_link($this->u_action));
			}
		}

		$template->assign_vars([
			'S_ERRORS'				=> ($errors) ? true : false,
			'ERRORS_MSG'			=> ($errors) ? implode('<br /><br />', $errors) : '',

			'U_ACTION'				=> $this->u_action,
			'PIA_DEFAULT_AVATAR'	=> (int) $config['threedi_pia_default_avatar'], // 0 = never, 1 = default, 2 = always
			/* Configs */
			'PIA_IMG_SIZE_VAL'		=> (int) $config['threedi_pia_size_val'],
			'PIA_BCGK_COL_VAL'		=> $config['threedi_pia_background_val'],
			'PIA_TXT_COL_VAL'		=> $config['threedi_pia_color_val'],
			'PIA_IN_LENGTH_VAL'		=> (int) $config['threedi_pia_length_val'],
			'PIA_FONT_SIZE_VAL'		=> (double) $config['threedi_pia_font_val'],
			'PIA_IMG_ROUNDED_VAL'	=> $config['threedi_pia_rou_val'] ? true : false,
			'PIA_UPPERCASE_VAL'		=> $config['threedi_pia_upp_cas_val'] ? true : false,
		]);
	}
}
