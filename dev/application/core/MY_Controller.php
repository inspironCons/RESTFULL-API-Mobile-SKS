<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->helper(array('template_helper'));
        $this->load->library(array('App'));

        $this->app->side = 'admin';
        $this->app->template = 'admin';
    }


}