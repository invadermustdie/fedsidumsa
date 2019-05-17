<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 29-04-19
 * Time: 09:41 AM
 */

class Menu_model extends CI_Model
{
    public function getSubMenu(){
        $query="
            select usm.*, um.menu
            from user_sub_menu as usm
            join user_menu as um
            on usm.menu_id=um.id;
        ";
        return $this->db->query($query)->result_array();
    }

}