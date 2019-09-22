<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model('voucher_model');
        $this->load->library('rand_gen');

    }
    public function voucher(){
        $data = array(
            'kode'      => $this->rand_gen->generate(16,'numeric'),
            'balance'   => '20000'
        );

        $this->voucher_model->insert($data);

        echo json_encode(array(
            'status' => 'success',
            'data'  =>$data
        ));
    }
}
