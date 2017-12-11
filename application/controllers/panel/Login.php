<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('panel/login_model');
    }
    
    /**
     * Date : 14-11-2016
     * Created By : Ajay
     * Description : User for panel login
    **/
    public function index()
    {
        $this->data=array();
        $panelLogin = $this->session->userdata('panel');
        if(!empty($panelLogin))
        {
            redirect('panel/welcome');
        }
        
        $postData = $this->input->post();
        if(!empty($postData))
        {
            $checkUser = $this->login_model->check_user($postData);
            if(!empty($checkUser))
            {
                $userDetails = $this->login_model->check_login($postData);
                if(!empty($userDetails))
                {
                    $sessionData['panel'] = $userDetails[0];
                    $this->session->set_userdata($sessionData);
                    redirect('panel/welcome');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Username or password not correct.');
                    redirect('panel');
                }
            }
            else
            {
                $this->session->set_flashdata('error', 'Username or password not correct.');
                redirect('panel');
            }            
        }
        
        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['logoTitle'] = $this->setting_model->getSetting('Logo Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');
        $this->data['siteTitle'] = $this->setting_model->getSetting('Site Title');
        
        $this->template->set_template('panelLogin');
        $this->template->write('pagetitle', 'Login');
        $this->template->write('sitetitle', $this->setting_model->getSetting('Site Title'));
        $this->template->write_view('content', 'panel/login/index', $this->data , true);
        $this->template->write_view('js', 'panel/login/js', $this->data , true);
        $this->template->render();
    }
}
