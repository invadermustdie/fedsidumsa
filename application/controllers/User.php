<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 23-04-19
 * Time: 04:13 PM
 */
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $data['title'] = 'Mi Perfil';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();
        //echo "Bienvenido al sistema : " .$data['user']['name'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $data['title'] = 'Editar Perfil';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();

        $this->form_validation->set_rules('name', 'Nombre completo', 'required|trim');

        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/footer');

        } else {

            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // verficar si existe una imagen para subir
            $upload_image = $_FILES['image']['name'];


            if ($upload_image) {

                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {

                    $old_image = $data['user']['image'];

                    if ($old_image != 'default.jpg') {

                        //var_dump($old_image);

                        //die();

                        unlink(FCPATH . 'assest/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');

                    //var_dump($new_image);
                    //die();

                    $this->db->set('image', $new_image);

                } else {

                    //var_dump("Aqui es por falso");
                    //die();

                    echo $this->upload->display_errors();
                }

            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('usuario');

            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">
                Su perfil ha sido actualizado!!!  
                </div>');
            redirect('user');

        }
    }

    public function changePassword()
    {
        $data['title'] = 'Change Password';

        $data['user'] = $this->db->get_where('usuario', array(
            'email' => $this->session->userdata('email')
        ))->row_array();
        //echo "Bienvenido al sistema : " .$data['user']['name'];

        $this->form_validation->set_rules('current_password', 'Password Actual', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'Nuevo Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirmar Password', 'required|trim|min_length[3]|matches[new_password1]');


        if ($this->form_validation->run() == false) {

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/footer');

        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            if (!password_verify($current_password, $data['user']['password'])) {

                $this->session->set_flashdata('message',
                    '<div class="alert alert-warning" role="alert">
                la contraseña actual es incorrecta!!!  
                </div>');
                redirect('user/changepassword');

            } else {

                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-warning" role="alert">
                La nueva contraseña no puede ser la misma que la contraseña actual  
                </div>');
                    redirect('user/changepassword');

                } else {

                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('usuario');

                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">
                Su contraseña ha cambiado!!!  
                </div>');
                    redirect('user/changepassword');
                }
            }
        }
    }
}




















