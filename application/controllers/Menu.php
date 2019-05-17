<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 26-04-19
 * Time: 10:56 AM
 */
class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Configuración Menu';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();
        //echo "Bienvenido al sistema : " .$data['user']['name'];

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('menu', 'Menu', 'required');

        if ($this->form_validation->run() == false) {

            // accesos de administrador
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/index', $data);
            $this->load->view('templates/footer');
        } else {

            $this->db->insert('user_menu', array('menu' => $this->input->post('menu')));
            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">
                Nuevo menu adicionado!!! 
                </div>');
            redirect('menu');
        }
    }

    public function submenu()
    {

        $data['title'] = 'Submenu Administracion';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();

        $this->load->model('Menu_model', 'menu');

        //$data['subMenu']= $this->db->get('user_sub_menu')->result_array();
        $data['subMenu'] = $this->menu->getSubMenu();

        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->form_validation->set_rules('title', 'Titulo', 'required');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required');
        $this->form_validation->set_rules('icon', 'icono', 'required');


        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('menu/submenu', $data);
            $this->load->view('templates/footer');

        } else {
            $data = array(
                'title' => $this->input->post('title'),
                'menu_id' => $this->input->post('menu_id'),
                'url' => $this->input->post('url'),
                'icon' => $this->input->post('icon'),
                'is_active' => $this->input->post('is_active'),
            );
            $this->db->insert('user_sub_menu', $data);

            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">
                Nuevo sub menu adicionado!!! 
                </div>');
            redirect('menu/submenu');
        }
    }
}









