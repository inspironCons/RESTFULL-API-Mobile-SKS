<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class V1 extends REST_Controller{

    public function __construct(){
        // Construct the parent class
        parent::__construct();
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
            die();
        }
        
        $this->load->model(array('User_model','Transaction_model','UserDetail_model','Voucher_model'));
        $this->load->library(array('Password','Rand_gen','Phpmailer_lib','Telegram'));
        
        date_default_timezone_set('asia/jakarta');
        
    }

     //api untuk login
    public function login_post(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $username = $request->username;
        $pass = $request->password;
        
        if($username && $pass){
            @$data = $this->User_model->get_by(array('username'=> $username));
            @$password = $this->password->is_valid_password($pass,$data[0]->password);
            if($data){
                if($data[0]->active === 't'){
                    if($password > 0){
                        $message = array(
                            'status'    => 'success',
                            'message'   => 'Login berhasil',
                            'response'  => $data[0]
                        );
                        $respones = REST_Controller::HTTP_OK;
                    }else{
                        $message = array(
                            'status'    => 'false',
                            'message'   => 'Password tidak cocok dengan username',
                            'user'      => $data
                        );
                        $respones = REST_Controller::HTTP_BAD_REQUEST;
                    }            
                }else{
                    $message = array(
                        'status'    => 'false',
                        'message'   => 'Akun anda belum diverifikasi, silahkan verifikasi'
                    );
                    $respones = REST_Controller::HTTP_BAD_REQUEST;
                }
            }else{
                $message = array(
                    'status'    => 'false',
                    'message'   => 'belum punya akun ya?, daftar dulu kuy'
                );
                $respones = REST_Controller::HTTP_BAD_REQUEST;
            }
        }
        
        $this->response($message, $respones);
    }
    
    //api untuk membuat (POST) registrasi
    public function registrasi_post(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);
        
        //buat password
        $baseNIM = strlen($post->NIM);
        $basepass= substr($post->NIM,-5,$baseNIM);
        $password = $this->password->encrypt_password($basepass,$post->username);

        $data = array(
            'username'      => $post->username,
            'password'      => $password,
            'email'         => $post->email,
            'createOn'      => date('Y-m-d'),
            'activationCode'=> $this->rand_gen->generate(10,"alpha-numeric"),
            'active'        => false,
        );
        
        if(!empty($data)){
            $return = $this->User_model->insert($data);
        }
        // $return = 1;
        if( $return > 0){
            $dataDetail = array(
                'idUser'    => $return,
                'NIM'       => $post->NIM,
                'namaDepan' => $post->namaDepan,
                'namaBelakang'=>$post->namaBelakang,
                'jurusan'   => $post->jurusan,
                'fakultas'  => $post->fakultas->fakultas,
                'saldo'     => 0,
                'telegramId'=> $post->telegramId
            );
            
            //PHPmail object
            $mail = $this->phpmailer_lib->load();
            
            // SMTP config

            $mail->isSMTP();
            $mail->Host     = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dodon.consult@gmail.com';
            $mail->Password = 'Jerukmanis97';
            $mail->SMTPSecure = 'tsl';
            $mail->Port     = 587;

            //add Recipients
            $mail->setFrom('dodon.consult@gmail.com','Millenial-Wallet');
            $mail->addAddress($post->email); 
            
            // Email subject
            $mail->Subject = 'Verifikasi Akun Anda';
            // Set email format to HTML
            $mail->isHTML(true);
            $url = base_url().'Authemail?kode='.$data['activationCode'];
            // Email body content
            $mailContent = "<h1>Klik link dibawah ini untuk menverivikasi akun anda</h1>
                            <p>url : $url</p><br><br>
                            <p> username: $post->username </p><br>
                            <p> password: $basepass </p>";
            $mail->Body = $mailContent;
            if($mail->send()){
                $this->UserDetail_model->insert($dataDetail);
                $message = array(
                    'status'=> 'success',
                    'message'  => 'silahkan cek email anda untuk verifikasi'
                );
                $respones = REST_Controller::HTTP_CREATED ; // CREATED (201) being the HTTP response code
            }else{
                $message = array(
                    'status'=> 'failed',
                    'message' =>'ooppsss, silahkan coba lagi',
                    'error'  => $mail->ErrorInfo()
                );
                $respones = REST_Controller::HTTP_BAD_REQUEST ; // CREATED (201) being the HTTP response code
            }
        }else{
            $message = array(
                'status'=> 'failed',
                'message'=> 'ooppsss, silahkan coba lagi'
            );
            
            $respones = REST_Controller::HTTP_BAD_REQUEST ; // BAD REQUEST (400) being the HTTP response code
        }
        $this->set_response($message,$respones); 
    }

    // mengambil data user di tabel user (GET)
    public function user_get(){
        
        $id = $this->get('id');

        if($id === null){
            $data = $this->User_model->get();
        }else{
            $data = $this->User_model->get($id);
        }

        if($data){
            $this->response($data, REST_Controller::HTTP_OK);            
        }else{
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => 'id were not found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
        
    }

    //hapus user (pengguna jasa)
    public function user_delete(){
        $id = $this->input->get('id');
        
        if($id === null ) {
            $this->response(array(
                'status' => false,
                'message' => 'Be sure ID has been entered'
            ), REST_Controller::HTTP_BAD_REQUEST);
        }else{
            $return = $this->User_model->delete($id);
            if($return > 0){
                $message = [
                    'id' => $id,
                    'status' => true,
                    'message' => 'Deleted the resource'
                ];
                $response = REST_Controller::HTTP_OK ;
            }else{
                $message = [
                    'id' => $id,
                    'status' => false,
                    'message' => 'ID not Found, make sure an ID'
                ];
                $response = REST_Controller::HTTP_BAD_REQUEST ; 
            }
            
            $this->set_response($message,$response);
        }
    }

    //cek password user
    public function cekPass_post(){ 
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);
        $getid = $this->User_model->get($post->id);
        $cekPass = $this->password->is_valid_password($post->password,$getid->password);

        if($cekPass == 1){
            $id = $post->telegramId;
            $message = "Akun anda sedang dalam proses perubahan Password, Jika itu anda maka abaikan pesan ini. Dan jika bukan, maka tunggu pesan 'PASSWORD BARU' dari kami agar anda dapat masuk kembali";
            if($this->telegram->send($id,$message)){
                $message = array(
                    'status'=> 'success',
                    'message' => 'Silahkan dilanjutkan'
                );
                $response = REST_Controller::HTTP_OK;
            }else{
                $message = array(
                    'status'=> 'failed',
                    'message' => 'ada kesalahan'
                );
                $response = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
            }
            
        }else{
            $message = array(
                'status'=> 'failed',
                'message' => 'Password salah'
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }
        $this->set_response($message,$response);
       
    }

    // ubah password user
    public function newPassword_put(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);

        $data   = array(
            'password' => $this->password->encrypt_password($post->password,$post->username),
        );
            if($update = $this->User_model->update($data,array('id'=>$post->id)) > 0){
                $id = $post->telegramId;
                $pesan = "Password Baru anda : $post->password";
                if($this->telegram->send($id,$pesan)){
                    $message = array(
                        'status'=> 'success',
                        'message' => 'password telah berubah'
                    );
                    $response = REST_Controller::HTTP_OK;
                }else{
                    $message = array(
                        'status'=> 'failed',
                        'message' => 'ada kesalahan'
                    );
                    $response = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
                }
                
            }else{
                $message = array(
                    'status'=> 'failed',
                    'message' => 'Gagal di ubah',
                );
                $response = REST_Controller::HTTP_BAD_REQUEST;
            }

        $this->set_response($message,$response);
    }

    //mengubah detail user di menu akun
    public function userDetail_put(){    
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);

        $data = array(
            'NIM' => $post->NIM,
            'namaDepan' =>$post->namaDepan,
            'namaBelakang' => $post->namaBelakang,
            'jurusan' => $post->jurusan,
            'fakultas' => $post->fakultas,
            'tempatLahir' => $post->tempatLahir,
            'tanggalLahir' => $post->tanggalLahir,
            'kontak' => $post->kontak,
            'alamat' => $post->alamat,
            'kota' => $post->kota,
            'kecamatan' =>$post->kecamatan,
            'provinsi' => $post->provinsi,
            'kodePos' => $post->kodePos,
            'telegramId'=> $post->telegramId,
            'jenisKelamin'=> $post->jenisKelamin
        );

        $update = $this->UserDetail_model->update($data,array('idUser'=>$post->idUser));

        if($update>0){
            $message = array(
                'status'    => 'success',
                'message'   => 'Berhasil Disimpan',
                'response'  => $data
            );
            $response = REST_Controller::HTTP_OK;
        }else{
            $message = array(
                'status'    => 'failed',
                'message'   => 'Opssss, sepertinya ada yang salah. Silahkan Coba Lagi'
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }
        $this->set_response($message,$response);
    }

    //mengambil detail dari user (GET) dari tabel user
    public function userdetail_get(){
        $id = $this->get('id');

        if($id){
            $data = $this->User_model->get_detail($id);
            $message = array(
                'status'=> 'success',
                'response'  => $data
            );
            $response = REST_Controller::HTTP_OK;
        }else{
            $message = array(
                'status'=> 'false',
                'message'=> 'id tidak ditemukan'
            );
            $response = REST_Controller::HTTP_NOT_FOUND;
        }
       
        $this->response($message,  $response);
    }

    //mengambil riwayat transaksi si user dari tabel transaksi
    public function historitransaction_get(){
        $id = $this->get('id');

        $data = $this->Transaction_model->get_by(array('idUser'=>$id,'success'=>true),20);
        
        if($data){
            $message = array(
                'status'    =>'success',
                'response'  => $data
            );
            $response = REST_Controller::HTTP_OK;
        }else{
            $message = array(
                'status'    => 'failed',
                'message'   => 'Belum mempunyai histori bertransaksi'
            );
            $response = REST_Controller::HTTP_NOT_FOUND;
        }
        
        $this->response($message, $response);
    }

    //mengambil detail riwayat transaksi
    public function detailtransaction_get(){
        $id = $this->get('id');

        $data = $this->User_model->get_detailTransaction(array('idTransaction'=> $id));

        if($data){
            $message= array(
                'id' =>$id,
                'response'=>$data[0]
            );
            $response = REST_Controller::HTTP_OK;
        }else{
            $message= array(
                'id' =>$id,
                'message' => 'Maaf Transaksi tidak ditemukan'
            );
            $response = REST_Controller::HTTP_NOT_FOUND;
        }
        

        $this->response($message, $response);

    }

    // membuat suatu trasaksi si user
    public function transaction_post(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);

        $data = array(
            'idUser'            => $post->idUser,
            'transactionType'   => $post->type,
            'amount'            => $post->sks,
            'unitPrice'         => $post->harga,
            'total'             => $post->total,
            'token'             => $this->rand_gen->generate(6,"numeric"),
            'expired'           => time()+ 60*3,
            'success'           => false,
            'transactionFaktur' => $this->Transaction_model->generate_faktur()
        );

        if($this->Transaction_model->insert($data) > 0){
            $token = $data['token'];
            $id = $post->telegramId;
            $message = "Token Anda : $token";
            $tel = $this->telegram->send($id,$message);
            
            $message = array(
                'status'=> 'success',
                'message' => 'Silahkan cek, BOT Telegram kami mengirimkan sesuatu',
                'expired'=> $data['expired']
           );
            $response = REST_Controller::HTTP_CREATED; 
        }else{
            $message = array(
                'status'=> 'failed',
                'message'  => 'opppss, sepertinya ada kesalahan'
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }

        $this->response($data, $response);
    }
    
    //mengupdate transaksi menjadi berhasil jika token yang di masukan benar
    public function authtransaction_put(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);
        
        $ambil_transaksi  = $this->Transaction_model->get_by(array('token'=> $post->token));
        $ambil_saldo      = $this->UserDetail_model->get_by(array('idUser'=>$post->idUser));
        $ionic_data = array(
            'idUser' => $post->idUser,
            ''
        );
        $data   =array(
            'transactionTime' => time(),
            'success'         => true
        );
    
        // $this->Transaction_model->update($data,array(
        //     'idUser' => $this->put('id'),
        //     'token'=>$this->put('token')
        // ));
        if($ambil_transaksi){
            if($this->Transaction_model->update($data,array('token'=>$post->token))){
                
                $balance = $ambil_saldo[0]->saldo - $post->total;
                
                if($this->UserDetail_model->update(array('saldo'=> $balance),array('idUser'=>$post->idUser))){
                    $message = array(
                        'status'    =>'success',
                        'message'   =>'transaksi berhasil'
                    );
                    $response = REST_Controller::HTTP_OK;
                }else{
                    $message = array(
                        'status'    =>'failed',
                        'message'   =>'transaksi gagal',
                    );
                    $response = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
                }
            }else{
                $message = array(
                    'status'    =>'failed',
                    'message'   =>'transaksi gagal',
                );
                $response = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
            } 
        }else{
            $message = array(
                'status'    =>'failed',
                'message'   =>'transaksi gagal',
            );
            $response = REST_Controller::HTTP_NOT_FOUND;
        }
        

        $this->response($message, $response);
    }

    public function redeem_post(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);
        @$get   = $this->Voucher_model->get_by(array('kode'=> $post->voucher,'isUsed'=> 'f'));
        $getuser= $this->User_model->get_detail($post->id);
       
        if($get){
            if(($get[0]->kode === $post->voucher) && ($get[0]->isUsed === 'f')){
                $saldo = $getuser->saldo + $get[0]->balance;
                $update = $this->UserDetail_model->update(array(
                    'saldo'=> $saldo),array('idUser'=> $post->id));
                if($update > 0){
                    $this->Voucher_model->update(array('isUsed'=> true),array('kode'=>$get[0]->kode));
                    $message = array(
                        'status'=> 'success',
                        'message'  => 'kode voucher cocok',
                        'balance'  => $update['saldo']
                    );
                    $response = REST_Controller::HTTP_OK;
                }else{
                    $message = array(
                        'status'=> 'failed',
                        'message'  => 'Oooopss ada kesalahan, silahkan coba lagi',
                    );
                    $response = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
                }
            }else{
                $message = array(
                    'status'=> 'failed',
                    'message'  => 'voucher sudah digunakan'
                );
                $response = REST_Controller::HTTP_OK;
            }
        }else{
            $message = array(
                'status'=> 'failed',
                'message'  => 'voucher tidak ditemukan',
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }
        $this->set_response($message , $response);
        
    }
    
    public function checkUsername_post(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);

        $get  = $this->User_model->get_by(array('username'=>$post->username));

        if($get){
            $message = array(
                'status'    => 'false',
                'message'   => 'opppsss, Username Sudah digunakan'
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }else{
            $message = array(
                'status'    => 'false',
                'message'   => 'username dapat digunakan'
            );
            $response = REST_Controller::HTTP_OK;
        }
        $this->set_response($message , $response);
    }

    public function checkNIM_post(){
        $postdata = file_get_contents("php://input");
        $post = json_decode($postdata);

        $get  = $this->UserDetail_model->get_by(array('NIM'=>$post->NIM));

        if($get){
            $message = array(
                'status'    => 'false',
                'message'   => 'opppsss, NIM Sudah digunakan'
            );
            $response = REST_Controller::HTTP_BAD_REQUEST;
        }else{
            $message = array(
                'status'    => 'false',
                'message'   => 'NIM belum digunakan'
            );
            $response = REST_Controller::HTTP_OK;
        }
        $this->set_response($message , $response);
    }

    
}