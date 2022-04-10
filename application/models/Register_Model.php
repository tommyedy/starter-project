<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register_Model extends CI_Model
{

    public $firstname;
    public $lastname;
    public $username;
    public $password;
    public $created_by;

    public $data;
    public $dataValue;
    
    protected $table_name = "master_user";

    //Check if Have Same Account to register
    function check_exist_account()
    {
        $query_check_account = $this->db->select('*')
            ->from($this->table_name)
            ->where($this->data, $this->dataValue)
            ->where('is_active', TRUE)->count_all_results();
        if ($query_check_account) {
            return true;
        } else {
            return false;
        }
    }

    function save_account()
    {
        //Account Data
        if (empty($this->created_by)) {
            $this->created_by = 'SYSTEM';
        }

        $account_data = array(
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'username'  => $this->username,
            'password'  => $this->password,
            'created_on' => date("Y-m-d H:i:s"),
            'created_by' => $this->created_by
        );

        $this->db->insert($this->table_name, $account_data);
    }
}
