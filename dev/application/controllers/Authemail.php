<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authemail extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('user_model');


    }

    public function index(){
        $kode   = $this->input->get('kode',TRUE);
        $get    = $this->user_model->get_by(array('activationCode'=>$kode));

        $data =array('active' => true);
        
        if($get){
            if($get[0]->active === 'f' && $get[0]->activationCode == $kode){
                $result = $this->user_model->update($data,array(
                    'id'        => $get[0]->id));
                $message = array(
                    'status'=> 'success',
                    'message'=> 'Akun anda berhasil di aktivasi',
                    'data' => $get
                );
            }else{
                $message = array(
                    'status'=> 'failed',
                    'message'=> 'Akun anda sudah diaktifasi',
                );
            }    
        }else{
            $message = array(
                'status'=> 'failed',
                'message'=> 'Opppss, Akunmu belum dibuat? Silahkan buat dulu yaaa'
            );
        }
    

        $this->load->view('index',$message);
    }
}