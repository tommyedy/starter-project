<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Register_Model')); //Load MOdel
    }

    public function index()
    {
        $data['link']   =  base_url('Register');
        $data['title']  =  'Register Page';
        $this->load->view('Login', $data);
    }

    public function do_Register()
    {
        //Get Data From Forms
        $firstname = '';
        $lastname  = '';
        $username  = '';
        $password  = '';

        //Set Data to property Model
        $this->Register_Model->firstname = $firstname;
        $this->Register_Model->lastname  = $lastname;
        $this->Register_Model->username  = $username;
        $this->Register_Model->password  = $password;

        //Check Account
        $check_exist_account  = $this->Register_Model->check_exist_account();
        if ($check_exist_account) {
            //Save Account
            $this->Register_Model->save_account();      
        } else {
            return false;
        }
    }

    public function add_Register()
    {
    }
}
