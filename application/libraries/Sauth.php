<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sauth
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('bcrypt');
    }

    public function is_login_active()
    {
        $username         = $this->CI->session->userdata('username');
        if (isset($username)) {
            return true;
        } else {
            return false;
        }
    }

    public function do_Login($username, $password, $table_name)
    {
        $query_check_account =  $this->CI->db->select('*')
            ->from($table_name)
            ->where('username', $username)
            ->where('is_active', TRUE)->count_all_results();
        if ($query_check_account) {
            $query_check_password = $this->CI->db->select('password')
                ->from($table_name)
                ->where('username', $username)
                ->where('is_active', TRUE)->get()->row();
            $result = $query_check_password->password;
            if ($this->CI->bcrypt->check_password($password, $result)) {
                $query_account_details = $this->CI->db->select('id_master_user, id_user_group, username, concat(firstname, " ", lastname) as fullname')
                    ->from('master_user')
                    ->join('master_user_group', 'master_user_group.id_master_user_group=master_user.id_user_group')
                    ->where('username', $username)
                    ->where('is_active', TRUE)->get();
                $account_details = $query_account_details->row();
                $session_data = array(
                    'id_user_group' => $account_details->id_user_group,
                    'username' => $account_details->username,
                    'full_name' => $account_details->fullname,
                    'menu'     => $this->menu()
                );
                $this->CI->session->set_userdata($session_data);
                return array(
                    'status' => true,
                    'messages' => 'Logged in'
                );
            } else {
                return array(
                    'status' => false,
                    'messages' => 'Invalid Password'
                );
            }
        } else {
            return array(
                'status' => false,
                'messages' => 'Account Not Registered'
            );
        }
    }

    protected function generate_activation_code()
    {
    }

    protected function account_activation()
    {
    }

    protected function generate_menus($id_user_group = NULL, $parent_menu = NULL)
    {
        $menu = '';
        if (is_null($parent_menu)) {
            $query_generate_menu = $this->CI->db->select('id_master_menu, menu_name, slug_menu, icon_menu, id_parent_menu')
                ->from('master_menu')
                ->join('master_privilege_web', 'master_menu.id_master_menu=master_privilege_web.id_menu')
                ->where('id_parent_menu', FALSE)
                ->where('master_menu.is_active', TRUE)
                ->order_by('master_menu.order', 'ASC')->get();
            // echo $this->CI->db->last_query();
            //  die('i');
        } else {
            $query_generate_menu = $this->CI->db->select('id_master_menu, menu_name, slug_menu, icon_menu, id_parent_menu')
                ->from('master_menu')
                ->join('master_privilege_web', 'master_menu.id_master_menu=master_privilege_web.id_menu')
                ->where('id_parent_menu', $parent_menu)
                ->where('master_menu.is_active', TRUE)
                ->order_by('master_menu.order', 'ASC')->get();
            // echo $this->CI->db->last_query();
            //  die('u');
        }
        foreach ($query_generate_menu->result() as $row) {
            if (empty($row->slug_menu)) {
                $menu .=
                    '<li>1</li>';
            } else {
                $menu .=
                    '<li><a href="' . $row->slug_menu . '">ss</a></li>';
            }


            $menu .= '<ul class="dropdown">' . $this->generate_menus($row->id_parent_menu) . ' </ul>';
        }
        echo "<pre>";
        print_r($menu);
        echo "</pre>";
        die('s');
    }

    public function menu()
    {
        $menu = '
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </a>

          
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item has-treeview menu-open">

                        <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>Level 1</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p>
                                    Level 1
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Level 2</p>
                                    </a>
                                </li>
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Level 2
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="#" class="nav-link">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>Level 3</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>';
    }

    public function do_Logout()
    {
        $this->CI->session->sess_destroy();
        return true;
    }
}
