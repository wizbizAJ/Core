<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    
    /**
     * Date : 14-11-2016
     * Created By : Ajay
     * Description : Use for logout
    **/
    public function index()
    {
        $this->session->unset_userdata('panel');
        redirect('panel');
    }
}
