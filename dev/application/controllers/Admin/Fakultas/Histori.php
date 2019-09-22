<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histori extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model(array('User_model'));
    }

    public function index(){
        $this->app->view('MY_header');
        $this->app->view('pages/fakultas/histori');
        $this->app->view('MY_Footer');
    }

    public function action($param){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            if($param == 'ambil'){
                $data = $this->User_model->get_detailTransaction(array('success'=> true));
                echo json_encode(array(
                    'status'=> 'success',
                    'data' => $data
                ));
            }
        }
    }
}