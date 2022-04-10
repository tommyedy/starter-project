<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $data_session = checkSession();
        $this->load->model(array('Main_Model','Auth_Model')); //Load MOdel
    }

    public function privilege_menu(){        
        $this->Auth_Model->id_user_group = $this->data_session['id_user_group'];
        $menu_privilege = $this->Auth_Model->get_privilege_menu(0);
        $data['menu_privilege'] = $menu_privilege;
    }
}
