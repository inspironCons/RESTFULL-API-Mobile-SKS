<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_lib {
    
    public function __construct(){
        log_message('debug','PHPMAILER is loades');
    }

    public function load()
    {
        $email = new PHPMailer(true);
        return $email;
    }
}