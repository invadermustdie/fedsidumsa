<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 24-04-19
 * Time: 04:03 PM
 */
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Escritorio';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();
        //echo "Bienvenido al sistema : " .$data['user']['name'];

        // accesos de administrador
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');

    }

    public function role()
    {
        $data['title'] = 'Role';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();

        $data['role'] = $this->db->get('user_rol')->result_array();

        // accesos de administrador
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role', $data);
        $this->load->view('templates/footer');

    }

    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Access';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();

        $data['role'] = $this->db->get_where('user_rol', array('id_rol' => $role_id))->row_array();

        $this->db->where('id != ', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        // accesos de administrador
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');

    }

    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $rol_id = $this->input->post('roleId');

        $data = array(
            'role_id' => $rol_id,
            'menu_id' => $menu_id
        );

        $result = $this->db->get_where('user_access_menu',$data);

        if ($result->num_rows() < 1){
            $this->db->insert('user_access_menu', $data);
        }else{
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">
                Accesso cambiado!!! 
                </div>');

    }
}



















