<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Auth_Model')); //Load Model
	}

	public function index()
	{
		$data['link']   =  base_url('Auth/do_Login');
		$data['title']  =  'Login Page';
		$this->load->view('Login', $data);
	}

	public function do_Login()
	{
		$username   = htmlspecialchars($this->input->post('username'));
		$password   = htmlspecialchars($this->input->post('username'));

		$this->Auth_Model->username = $username;
		$this->Auth_Model->password = $password;

		//Check Account
		$checkAccount  = $this->Auth_Model->check_account();
		if ($checkAccount) {
			$checkPassword  = $this->Auth_Model->check_password();
			if ($checkPassword) {
				$account_details  = $this->Auth_Model->get_account_details();
				$this->Auth_Model->id_user_group = $account_details->id_user_group;
				$session_data = array(
					'id_user_group' => $account_details->id_user_group,
					'username' => $account_details->username,
					'full_name' => $account_details->fullname
				);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function do_Logout()
	{
	}
}
