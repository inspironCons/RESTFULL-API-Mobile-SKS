<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends MY_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model(array('Voucher_model'));
        $this->load->library(array('Rand_gen'));
    }

    public function index(){
        $this->app->view('MY_header');
        $this->app->view('pages/voucher/index');
        $this->app->view('MY_Footer');
    }

    public function action($param){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            if($param == 'ambil'){
                $data = $this->Voucher_model->get();
                echo json_encode(array(
                    'status' => 'success',
                    'data'=> $data
                ));
            }else if($param == 'tambah'){
                $post = $this->input->post();
                $data = array(
                    'kode' => $this->rand_gen->generate(16,'numeric'),
                    'balance' => $post['saldo'],
                    'isUsed' => false
                );

                if($post['jumlah']==1){
                    $this->Voucher_model->insert($data);
                    echo json_encode(array(
                        'status' => 'success',
                    ));
                }else{
                    for ($i=1; $i <= $post['jumlah']; $i++) { 
                        $this->Voucher_model->insert($data);
                    }
                    echo json_encode(array(
                        'status' => 'success',
                        'message'=> 'data banyak'
                    ));
                }
                
                

            }
        }
    }
}