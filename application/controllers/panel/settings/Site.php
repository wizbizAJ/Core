<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends Panel_Controller {
    
    function __construct() {
        $this->data=array();
        parent::__construct($this->data);
        $this->load->model('panel/selection_criteria_model');
         $permission = $this->data['permission'];
        if (!in_array(1, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 24-11-2016
     * Created By : Ajay
     * Description : User for update site setting
    **/
    public function index()
    {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (!empty($_FILES['site_logo']['name'])) {
                $updateSiteLogo = $this->setting_model->updateSiteLogo($postData);

                if ($updateSiteLogo['success'] == 1) {
                    $updateSiteTitle = $this->setting_model->updateSetting('Site Title', $postData['title']);
                    $updateLogoTitle = $this->setting_model->updateSetting('Logo Title', $postData['logo_title']);
                    $updateReservedRight = $this->setting_model->updateSetting('Reserved Right', $postData['reserved_right']);
                    $updateShowItemPerPage = $this->setting_model->updateSetting('Show Items Per Page', $postData['item_per_page']);

                    $this->session->set_flashdata('success', 'Site setting updated successfully.');
                } else {
                    $this->session->set_flashdata('error', $updateSiteLogo['message']);
                }
            } else {
                $updateSiteTitle = $this->setting_model->updateSetting('Site Title', $postData['title']);
                $updateLogoTitle = $this->setting_model->updateSetting('Logo Title', $postData['logo_title']);
                $updateReservedRight = $this->setting_model->updateSetting('Reserved Right', $postData['reserved_right']);
                $updateShowItemPerPage = $this->setting_model->updateSetting('Show Items Per Page', $postData['item_per_page']);
               

                $this->session->set_flashdata('success', 'Site setting updated successfully.');
            }
            redirect('panel/settings/site');
        }

        $this->data['breadcrumb'] = array(
            'Home' => base_url() . 'admin/welcome',
            'Settings' => '',
            'Site' => ''
        );

        $this->data['siteTitle'] = $this->setting_model->getSetting('Site Title');
        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['logoTitle'] = $this->setting_model->getSetting('Logo Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');
        $this->data['showItemsPerPage'] = $this->setting_model->getSetting('Show Items Per Page');

        $this->data['pageTitle'] = 'Site Setting';
        $this->template->write('pagetitle', $this->data['pageTitle']);
        $this->template->write('bodyClass', '');
        $this->template->write_view('css', 'panel/settings/site/css', $this->data, true);
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('content', 'panel/settings/site/index', $this->data, true);
        $this->template->write_view('js', 'panel/settings/site/js', $this->data, true);
        $this->template->render();
    }
}
