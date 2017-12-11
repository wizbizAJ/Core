<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class TimeZone extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	/*
	@Date : 
        - 25-10-2017, Created by: Ajay
        @Description
        - use for set user's time time in session
	*/
	public function setTimezon()
	{       
            $timeZoneData =  array('timeZone' => $this->input->post('timeZone'), 'timeCode' => $this->input->post('timeCode'));
            $this->session->set_userdata($timeZoneData);
	}
}
