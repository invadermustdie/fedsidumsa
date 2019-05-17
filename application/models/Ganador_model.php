<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 14-05-19
 * Time: 05:14 PM
 */

class Ganador_model extends CI_Model
{
    public function getAllGanadores()
    {

        //$this->db->order_by('fecha_reg','asc');
        //$query = $this->db->get('ganadores');

        $consulta = "
        select b.id_docente, b.nombre, b.ap_pat, b.ap_mat, b.nro_ci, b.exp_ci, b.facultad, a.fecha_reg
          from ganadores as a
          left outer join docente as b on (a.id_docente = b.id_docente)
          order by a.fecha_reg desc;
        ";

        $query = $this->db->query($consulta);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getGanador()
    {
        //$query = $this->db->get('docente');

        $consulta = "
                select id_docente, nro_ci, exp_ci, nombre, ap_pat, ap_mat, facultad
                from docente
                order by random()
                limit 1;
        ";

        $res =$this->db->query($consulta);

        if ($res->num_rows() > 0) {
            return $res->result();
        } else {
            return false;
        }
    }


    public function addGanador(){

        $datos = array(
            'id_docente'=>$this->input->post('idDocente'),
            'fecha_reg'=> date('d/m/y H:i:s'),
            'estado'=>1// si es 1 significa que se entrego el premio
        );

        $this->db->insert('ganadores', $datos);

        if ($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }



    }
}







