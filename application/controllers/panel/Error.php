<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Error extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	
    /**
     * Date : 20-09-2016
     * Created By : Ajay
     * Description : User for display 403 page
    **/
    public function forbidden()
    {       
        $data['siteTitle'] = $this->setting_model->getSetting('Site Title');
        $data['page'] = 'Forbidden';
        
        $this->load->view('errors/403',$data);
    }
    
    /**
     * Date : 20-09-2016
     * Created By : Ajay
     * Description : User for display 404 page
    **/
    public function notFound()
    {       
        $data['siteTitle'] = $this->setting_model->getSetting('Site Title');
        $data['page'] = 'Not Found';
        
        $this->load->view('errors/404',$data);
    }
}
