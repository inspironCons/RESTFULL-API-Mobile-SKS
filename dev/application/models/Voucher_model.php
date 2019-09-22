<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends MY_Model{

    protected $_table_name = 'voucher';
	protected $_primary_key = 'id';
	protected $_order_by = 'id';
    protected $_order_by_type = 'ASC';
}