<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 22-04-19
 * Time: 10:09 AM
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == false) {

            $data['title'] = 'FEDSIDUMSA - Pagina Inicio';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            // pasa la validacion
            // llama a la funcion login
            $this->_login();
        }
    }

    public function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('usuario', array('email' => $email))->row_array();

        //var_dump($user);

        // exite el usuario
        if ($user) {
            //preguntamos si es un usuario activo
            if ($user['is_active'] == 1) {
                // si esta activo
                // verifcamos el password
                if (password_verify($password, $user['password'])) {
                    // que rol tiene en el sistema
                    $data = array(
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    );

                    $this->session->set_userdata($data);
                    if ($user['role_id'] == 1) {
                        // vista en la pagina del administrador
                        redirect('admin');
                    } else {
                        // vista de la pagina de usuario
                        redirect('user');
                    }
                } else {
                    // password equivocado
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-danger" role="alert">
                        Password incorrecto!!! 
                        </div>');
                    redirect('auth');
                }

            } else {
                // si no esta activo
                $this->session->set_flashdata('message',
                    '<div class="alert alert-warning" role="alert">
                Este Email no esta activo. contacte con el administrador. 
                </div>');
                redirect('auth');
            }
        } else {
            //mensaje de no registrado
            $this->session->set_flashdata('message',
                '<div class="alert alert-danger" role="alert">
                email no esta registrado. Por favor registrese. 
                </div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }

        // definimos las reglas de validacion
        $this->form_validation->set_rules('name', 'Nombre Completo', 'required|trim');
        $this->form_validation->set_rules('email', 'Correo Electronico', 'required|trim|valid_email|is_unique[usuario.email]', array(
            'is_unique' => 'Este email ya esta registrado!!!'
        ));
        $this->form_validation->set_rules('password1', 'Contraseña', 'required|trim|min_length[3]|matches[password2]', array(
            'matches' => 'Password no coincide!',
            'min_length' => 'Password demasiado corto!'
        ));
        $this->form_validation->set_rules('password2', 'Contraseña', 'required|trim|min_length[3]|matches[password1]');


        if ($this->form_validation->run() == false) {

            $data['title'] = 'Formulario de Registro de Usuarios';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');

        } else {
            // si los datos pasan la validacion
            //echo "Los datos se guardaron de forma exitos";
            $email = $this->input->post('email');

            $data = array(
                'name' => $this->input->post('name'),
                'email' => $email,
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            );

            // prepramos los tokens
            //$token = base64_encode(openssl_random_pseudo_bytes(32));
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $user_token = array(
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            );

            //echo var_dump($data);
            $this->db->insert('usuario', $data);
            $this->db->insert('usuario_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">
                Felicitaciones!, su cuenta a sido registrada. Por favor active su cuenta ingresando a su correo electronico . 
                </div>');
            redirect('auth');
        }
    }

    public function _sendEmail($token, $type)
    {
        $config = array(
            'protocol' => 'smtp',
            //'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'alegutierrezmarin@gmail.com',
            'smtp_pass' => '6081449ale',
            'smtp_port' => 465,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n"
        );

        $this->load->library('email', $config);

        $this->email->from('alegutierrezmarin@gmail.com', 'SISTEMA FEDSIDUMSA');
        $this->email->to($this->input->post('email'));

        // si se tiene que verificar su cuenta
        if ($type == 'verify') {

            $this->email->subject('Verificación de Cuenta FEDSIDUMSA');
            $this->email->message('Haga click en el link para confirmar su cuenta : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . $token . '">ACTIVAR CUENTA</a>');

        } else if ($type == 'forgot') {

            $this->email->subject('Reset password FEDSIDUMSA');
            $this->email->message('Haga click en el link para resetear su password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . $token . '">RESET CONSTRASEÑA</a>');

        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die();
        }
    }

    public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        $user = $this->db->get_where('usuario', array('email' => $email))->row_array();

        //var_dump($email);
        //var_dump($token);
        //var_dump($user);

        //die();

        if ($user) {

            $user_token = $this->db->get_where('usuario_token', array('token' => $token))->row_array();

            /*
            var_dump($token);

            var_dump($user_token);

            die();
*/

            if ($user_token) {

                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {

                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('usuario');

                    // eliminar su token
                    $this->db->delete('usuario_token', array('email' => $email));

                    // mensaje de activacion de cuenta
                    $this->session->set_flashdata('message',
                        '<div class="alert alert-success" role="alert">
                ' . $email . ' Su cuenta a sido activada, Ingrese su usuario y contraseña. 
                </div>');

                    redirect('auth');

                } else {
                    // eliminar al usuario de las tablas usuario y usuario_token
                    $this->db->delete('usuario', array('email' => $email));
                    $this->db->delete('usuario_token', array('email' => $email));

                    $this->session->set_flashdata('message',
                        '<div class="alert alert-warning" role="alert">
                Activación de cuenta fallida!!! Token expirado. 
                </div>');

                    redirect('auth');
                }

            } else {

                $this->session->set_flashdata('message',
                    '<div class="alert alert-warning" role="alert">
                Activación de cuenta fallida!!! Token erroneo. 
                </div>');

                redirect('auth');
            }

        } else {
            $this->session->set_flashdata('message',
                '<div class="alert alert-danger" role="alert">
                Activación de cuenta fallida!!! Email erroneo o inexistente. 
                </div>');

            redirect('auth');
        }
    }

    // salir del sistema
    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message',
            '<div class="alert alert-success" role="alert">
                Usted ha salido del sistema. 
                </div>');

        redirect('auth');

    }

    public function blocked()
    {
        //echo "accesso bloqueado";
        $this->load->view('auth/blocked');
    }

    public function forgotPassword()
    {

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() == false) {

            $data['title'] = 'Forgot Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/forgot-password');
            $this->load->view('templates/auth_footer');

        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('usuario', array(
                'email' => $email,
                'is_active' => 1
            ))->row_array();
            // si ese usuario existe
            if ($user) {

                // creamos el token
                $token = bin2hex(openssl_random_pseudo_bytes(32));
                $user_token = array(
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                );

                $this->db->insert('usuario_token', $user_token);


                $this->_sendEmail($token, 'forgot');

                $this->session->set_flashdata('message',
                    '<div class="alert alert-success" role="alert">
                Por favor. verifique su email para resetear su password. 
                </div>');
                redirect('auth/forgotpassword');


            } else {

                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                Este email no esta registrado o no es un usuario activo. 
                </div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('usuario', array('email' => $email))->row_array();

        if ($user) {

            $user_token = $this->db->get_where('usuario_token', array('token' => $token))->row_array();

            if ($user_token) {

                $this->session->set_userdata('reset_email', $email);
                $this->changePassword();

            } else {
                $this->session->set_flashdata('message',
                    '<div class="alert alert-danger" role="alert">
                Reseteo de constraseña fallo!!! token incorrecto. 
                </div>');
                redirect('auth/forgotpassword');
            }
        } else {

            $this->session->set_flashdata('message',
                '<div class="alert alert-danger" role="alert">
                Reseteo de constraseña fallo!!! email incorrecto. 
                </div>');
            redirect('auth/forgotpassword');
        }
    }

    public function changePassword(){

        if (!$this->session->userdata('reset_email')){
            redirect('auth');
        }

        $this->form_validation->set_rules('password1','contraseña','trim|required|min_length[3]|matches[password2]');
        $this->form_validation->set_rules('password2','confirmar contraseña','trim|required|min_length[3]|matches[password1]');

        if ($this->form_validation->run()==false){

            $data['title'] = 'Change Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/change-password');
            $this->load->view('templates/auth_footer');

        }else{
            $password = password_hash($this->input->post('password1'),PASSWORD_DEFAULT);

            $email = $this->session->userdata('reset_email');

            // hacemos la actualizacion de datos de usario

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('usuario');

            $this->session->unset_userdata('reset_email');

            $this->session->set_flashdata('message',
                '<div class="alert alert-success" role="alert">
                Su password a cambiado!! por favor ingrese con su nueva constraseña al sistema 
                </div>');
            redirect('auth');

        }
    }
}










