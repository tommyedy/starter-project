<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public $table_name = 'master_user';
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Sauth');
  }


 

  public function index()
  {
    if ($this->sauth->is_login_active()) {
      redirect('Main');
    } else {
      $data['title'] = 'Login Page';
      $data['link']  = 'Auth/do_login';
      $this->load->view('Login', $data);
    }
  }

  public function do_login()
  {
    $username = htmlspecialchars($this->input->post('username'));
    $password = $this->input->post('password');
    $logged_in = $this->sauth->do_Login($username, $password, $this->table_name);
    //echo json_encode($logged_in);
  }

  public function do_logout()
  {
    $this->sauth->do_Logout();
  }
}
