<?php
/**
 * Created by PhpStorm.
 * User: alejandro
 * Date: 14-05-19
 * Time: 11:09 AM
 */

class Sorteo extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ganador_model');
    }

    public function index()
    {

        $this->load->view('sorteo/sorteo_fedsidumsa');

    }

    public function getAllGanador(){
        $result = $this->ganador_model->getAllGanadores();

        echo json_encode($result);
    }


    public function getNumeroAleatorio(){

        $result = $this->ganador_model->getGanador();
        echo json_encode($result);
    }


    public function registrarGanador(){

        $result = $this->ganador_model->addGanador();

        $msg['success'] = false;
        if ($result){
            $msg['success']=true;
        }
        echo json_encode($msg);
    }

}