<?php
/**
 *
 * phpBB Initial Avatars. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, 3Di, 3di.space
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace threedi\pia\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * phpBB Initial Avatars Event listener.
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/* @var \threedi\pia\lite\pia */
	protected $pia_lite;

	/**
	 * Constructor
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\user $user, \threedi\pia\lite\pia $pia_lite)
	{
		$this->config		=	$config;
		$this->user			=	$user;
		$this->pia_lite		=	$pia_lite; // helper class
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'			=>	'pia_load_language_on_setup',
			'core.user_setup_after'		=>	'pia_store_avatars_on_setup',
			'core.permissions'			=>	'pia_permissions',
		);
	}

	/**
	 * Main language file inclusion
	 *
	 * @event core.user_setup
	 */
	public function pia_load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'threedi/pia',
			'lang_set' => 'pia_global',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Permission's language file is automatically loaded
	 *
	 * @event core.permissions
	 */
	public function pia_permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions += [
			'u_allow_pia_ucp' => [
				'lang'	=> 'ACL_U_ALLOW_PIA_UCP',
				'cat'	=> 'misc',
			],
			'u_allow_pia_view' => [
				'lang'	=> 'ACL_U_ALLOW_PIA_VIEW',
				'cat'	=> 'misc',
			],
			'a_pia_admin' => [
				'lang'	=> 'ACL_A_PIA_ADMIN',
				'cat'	=> 'misc',
			],
		];
		$event['permissions'] = $permissions;
	}

	/**
	* @event core.user_setup_after
	*/
	public function pia_store_avatars_on_setup()
	{
// --- Emergency tool ---
		/*
		$this->pia_lite->restore_user_avatars();
		return;
		*/
// --- Emergency tool ---
		/**
		 * IF the user has PIA's permissions set...
		 * IF the ACP Default Avatar bit is ON...
		 * IF the Board allows avatars...
		 * IF the Board allows remote avatars...
		 * etc.. ...
		 * ... let's go ahead.
		*/
		if ( $this->pia_lite->is_authed() && $this->config['threedi_pia_default_avatar'] && $this->config['allow_avatar'] && $this->config['allow_avatar_remote'])
		{
			if ( (empty($this->user->data['avatar'])) && $this->user->data['pia_avatar_ucp'] == 1 && $this->user->data['user_avatar_type'] == '' )
			{
				$this->pia_lite->pia_main();
			}

			if ($this->user->data['user_avatar_type'] === 'avatar.driver.remote' && ($this->user->data['pia_avatar_ucp'] == 0))
			{
				$this->pia_lite->delete_pia_ucp_avatars();
			}
		}
	}
}
