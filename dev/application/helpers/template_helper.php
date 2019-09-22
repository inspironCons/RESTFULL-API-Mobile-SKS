<?php
defined('BASEPATH') OR exit('No direct script access allowed');
    
	function get_template_directory($path, $dir_file){

		$replace_path = str_replace('\\', '/', $path);
		$get_digit_doc_root = strlen("C:/xampp/htdocs/Mellanial-admin");
		$full_path = substr($replace_path, $get_digit_doc_root);

		return "http://localhost/Mellanial-admin/".$full_path.'/'.$dir_file;
	}

	function set_url($sub){
		$_this =& get_instance();
		if($_this->app->side == 'admin'){
			return site_url('admin/'.$sub);
		}
		else{
			return site_url($sub);
		}	
		
	}

	// function is_active_menu($page,$class){
	// 	$_this =& get_instance();
	// 	if($page == $_this->uri->segment(2)){
	// 		return $class;
	// 	}
	// }

	function is_active_page_print($page,$segment,$class){
		$_this =& get_instance();
		if($page == $_this->uri->segment($segment)){
			return $class;
		}
	}

	function title(){
		$_this =& get_instance();

		$array_page = array(
			'Dashboard' => 'Dashboard',
			'Voucher'	=> 'Voucher',
			'Histori'	=> 'Histori Transaksi'
		);

		$title = NULL;
		if($_this->app->side == 'admin' && (array_key_exists($_this->uri->segment(2), $array_page))){
			return $array_page[$_this->uri->segment(2)].' : : Mellanial Wallet' ;
		}elseif($_this->app->side == 'admin' && (array_key_exists($_this->uri->segment(3), $array_page))){
			return $array_page[$_this->uri->segment(3)].' : : Mellanial Wallet' ;
		}
	}

	function rupiah($angka){
	
		$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
		return $hasil_rupiah;
	 
	}