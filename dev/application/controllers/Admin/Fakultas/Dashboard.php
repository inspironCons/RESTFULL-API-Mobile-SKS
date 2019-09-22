<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model(array('Transaction_model','User_model'));
    }

    public function index(){
        $this->app->view('MY_header');
        $this->app->view('pages/fakultas/index');
        $this->app->view('MY_Footer');
    }

    public function action($param){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            if($param == 'Mahasiswa_Aktif'){
                $hitung = $this->User_model->count(array('role'=>'mahasiswa'));
                echo json_encode(array(
                    'status' => 'success',
                    'data'=> $hitung
                ));
            }elseif($param == 'historyMonthly'){
                $post = $this->input->post();
                $hitung = $this->Transaction_model->count(array(
                    'transactionTime >=' => $post['awal'],
                    'transactionTime <=' => $post['akhir'],
                ));
                echo json_encode(array(
                    'status' => 'success',
                    'data'=> $hitung
                ));
            }elseif($param == 'ambil_graph'){
                $data_mahasiswa = $this->User_model->get_by(array('role'=>'mahasiswa'));
            }
        }
    }
}