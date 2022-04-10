<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_Model extends CI_Model
{
	public $username;
	public $password;
	public $id_user_group;
	//public $is_active = 1;
	protected $table_name = "master_user";

	function check_account()
	{
		$query_check_account = $this->db->select('*')
			->from($this->table_name)
			->where('username', $this->username)
			->where('password', $this->password)
			->where('is_active', TRUE)->count_all_results();
		if ($query_check_account) {
			return true;
		} else {
			return false;
		}
	}

	function check_password()
	{
		$query_check_password = $this->db->select('password')
			->from($this->table_name)
			->where('username', $this->username)
			->where('password', $this->password)
			->where('is_active', TRUE)->get()->row();
		if ($query_check_password->password == $this->password) {
			return true;
		} else {
			return false;
		}
	}

	function get_account_details()
	{
		$this->db->select('id_master_user, id_user_group, username, concat(firstname, " ", lastname) as fullname')
			->from('master_user')
			->join('master_user_group', 'master_user_group.id_master_user_group=master_user.id_user_group')
			->where('username', $this->username)
			->where('is_active', TRUE);
		return $this->db->get()->row();
	}

	function get_privilege_menu($id_parent_menu = 0)
	{
		if ($id_parent_menu == 0) {
			$query_get_menu = $this->db->select('id_master_menu, menu_name, id_parent_menu, 
											slug_menu, icon_menu')
				->from('master_privilege_web')
				->join('master_menu', 'master_privilege_web.id_menu=master_menu.id_master_menu')
				->join('detail_menu', 'detail_menu.id_menu = master_privilege_web.id_menu')
				->join('master_action_web', 'detail_menu.action = master_action_web.id_master_action_web')
				->where('master_privilege_web.is_active', TRUE)
				->where('master_menu.id_parent_menu', 0)
				//	->where('id_user_group', $this->id_user_group)
				//->group_by('menu_name')
				->order_by('master_menu.order', 'ASC')->get();
			// print_r($this->db->last_query());
			// die();

		} else {
			$query_get_menu = $this->db->select('id_master_menu, menu_name, id_parent_menu, 
											slug_menu, icon_menu')
				->from('master_privilege_web')
				->join('master_menu', 'master_privilege_web.id_menu=master_menu.id_master_menu')
				->join('detail_menu', 'detail_menu.id_menu = master_privilege_web.id_menu')
				->join('master_action_web', 'detail_menu.action = master_action_web.id_master_action_web')
				->where('master_privilege_web.is_active', TRUE)
				->where('master_menu.id_parent_menu', $id_parent_menu)
				//	->where('id_user_group', $this->id_user_group)
				//->group_by('menu_name')
				->order_by('master_menu.order', 'ASC')->get();
		}
		foreach ($query_get_menu->result() as $row) {
			//if (($row->is_parent) && ($row->level_menu)) {
			//$submenu = $this->generate_menu($row->id_parent_menu);
			$menu[] = array(
				'menu_name' => $row->menu_name,
				'slug_menu' => $row->slug_menu,
				'icon_menu' => $row->icon_menu,
				//'submenu' => $submenu,
				//'menu_action' => $row->menu_action
			);
			//} 
			//	else {
			//	}
		}

		return $menu;
	}
}
