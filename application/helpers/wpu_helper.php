<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 29-04-19
 * Time: 04:10 PM
 */

function is_logged_in()
{
    $ci = get_instance();

    // si no es un usuario autorizado redirecciona a la pagina de autorizacion
    if (!$ci->session->userdata('email')) {

        redirect('auth');

    } else {

        $role_id = $ci->session->userdata('role_id');

        $menu = $ci->uri->segment(1);

        $queryMenu = $ci->db->get_where('user_menu', array('menu' => $menu))->row_array();

        $menu_id = $queryMenu['id'];

        $userAccess = $ci->db->get_where('user_access_menu',
            array(
                'role_id' => $role_id,
                'menu_id' => $menu_id
            )
        );

        $aux = $userAccess->num_rows();

        if ($aux < 1) {
            //echo "es menor a 1";
            redirect('auth/blocked');

        }

        /*else {
            echo "es mayor a 1";
        }

        var_dump($aux);
        die();
        */
    }
}


function check_access($role_id, $menu_id)
{
    $ci = get_instance();
    $result = $ci->db->get_where(
        'user_access_menu', array(
            'role_id' => $role_id,
            'menu_id' => $menu_id
        )
    );

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}












