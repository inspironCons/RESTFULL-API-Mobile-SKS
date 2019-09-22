<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App{
	public $side;
    public $template;

    function view($pages,$data=NULL){
        $_this =& get_instance();
        if($data){
            $_this->load->view($this->template.'/'.$pages,$data);
        }else{
            $_this->load->view($this->template.'/'.$pages);            
        }
    }
}