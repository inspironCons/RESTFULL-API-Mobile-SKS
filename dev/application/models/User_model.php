<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model{

    protected $_table_name = 'user';
	protected $_primary_key = 'id';
	protected $_order_by = 'id';
    protected $_order_by_type = 'ASC';
    
    function get_detail($id=NULL) {
        $this->db->select('{PRE}user.username,
                           {PRE}user.password,
                           {PRE}user.email,
                           {PRE}userDetail.*');
        $this->db->join('{PRE}userDetail', ' {PRE}user.id = {PRE}userDetail.idUser', 'INNER' );
        return parent::get($id);
    }

    function update_detail($data,$where = array()){
        $this->db->select('{PRE}user.password,
                           {PRE}user.email,
                           {PRE}userDetail.*');
        $this->db->join('{PRE}userDetail', ' {PRE}user.id = {PRE}userDetail.idUser', 'INNER' );
        return parent::update($data,$where);    
    }

    function get_detailTransaction($where){
        $this->db->select('{PRE}userDetail.*,
                           {PRE}transaction.*');
        $this->db->join('{PRE}userDetail'   ,'{PRE}user.id = {PRE}userDetail.idUser', 'INNER' );
        $this->db->join('{PRE}transaction'  ,'{PRE}user.id = {PRE}transaction.idUser', 'INNER' );
        return parent::get_by($where);
    }
}