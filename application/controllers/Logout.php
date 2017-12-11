<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends FrontendController {
        function __construct() {
            $this->data=array();
            parent::__construct($this->data);
        }
	
        /*
        @Date 
            - 20-10-2011, Created by: Ajay
        @Description
            - Use for logout
        */
	public function index()
	{
            $this->session->unset_userdata('crickCustomer'); 
            $this->facebook->destroy_session();
            redirect('welcome');
	}
}
