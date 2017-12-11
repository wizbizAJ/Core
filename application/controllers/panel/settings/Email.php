<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends Panel_Controller {
    
    function __construct() {
        $this->data=array();
        parent::__construct($this->data);
        $this->load->model('panel/selection_criteria_model');
         $permission = $this->data['permission'];
        if (!in_array(2, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 23-11-2016
     * Created By : Ajay
     * Description : User for Change Selection criteria
    **/
    
    public function index()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $updateFromEmail = $this->setting_model->updateSetting('From Email', $postData['from_email']);
            $updateFromName = $this->setting_model->updateSetting('From Name', $postData['from_name']);
            
            if (!empty($postData['smtp'])) {
                $updateSmtp = $this->setting_model->updateSetting('SMTP', $postData['smtp']);
                $updateHost = $this->setting_model->updateSetting('SMTP Host', $postData['smtp_host']);
                $updatePort = $this->setting_model->updateSetting('SMTP Port', $postData['smtp_port']);
                $updateusername = $this->setting_model->updateSetting('SMTP Username', $postData['username']);
                $updatePassword = $this->setting_model->updateSetting('SMTP Password', gzdeflate($postData['password']));
            } else {
                $updateSmtp = $this->setting_model->updateSetting('SMTP', 0);
            }

            $this->session->set_flashdata('success', 'Email setting updated successfully.');
            redirect('panel/settings/email');
        }
        
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'Settings' => '',
            'Email Setting' => ''
        );
        
        $this->data['fromEmail'] = $this->setting_model->getSetting('From Email');
        $this->data['fromName'] = $this->setting_model->getSetting('From Name');
        $this->data['smtp'] = $this->setting_model->getSetting('SMTP');
        $this->data['smtpHost'] = $this->setting_model->getSetting('SMTP Host');
        $this->data['smtpPort'] = $this->setting_model->getSetting('SMTP Port');
        $this->data['smtpUsername'] = $this->setting_model->getSetting('SMTP Username');
        $this->data['smtpPassword'] = gzinflate($this->setting_model->getSetting('SMTP Password'));
        
        $this->data['pageTitle'] = 'Email Settings';
        
        $this->template->write('pagetitle', $this->data['pageTitle']);        
        $this->template->write_view('css', 'panel/settings/email/css', $this->data, true);
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('content', 'panel/settings/email/index', $this->data, true);
        $this->template->write_view('js', 'panel/settings/email/js', $this->data, true);
        $this->template->render();
    }
    
}
