<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends MY_Model{

    protected $_table_name = 'transaction';
	protected $_primary_key = 'idTransaction';
	protected $_order_by = 'transactionFaktur';
    protected $_order_by_type = 'DESC';


    function getby_riwayat($where, $limit = NULL, $offset = NULL, $single = FALSE, $select = NULL) {
        $this->db->select('{PRE}user.username , {PRE}transaction.*');
        $this->db->join('{PRE}user', '{PRE}transaction.idUser = {PRE}user.id', 'LEFT' );
        return parent::get_by($where,$limit,$offset,$single,$select);
    }

    function generate_faktur(){
		date_default_timezone_set("Asia/Jakarta");
		$data = date('dmy');
		$where= `"transactionFaktur"::text LIKE '$data%`;
		$query= $this->get_by($where);
		$this->db->select_max('transactionFaktur');
		//rumusan nya
		if($query[0]){
			$last 		= (int) $query[0]->transactionFaktur; //mengambil angka terakhir di noFaktur
			$ambilLast	= substr($last, -3);
			$next 		= $ambilLast + 1;
			$hasil 		= str_pad($next,3,"0",STR_PAD_LEFT);
			
			return date('dmy').$hasil;
		}else{
			return date('dmy').'001';
		}
	}
}