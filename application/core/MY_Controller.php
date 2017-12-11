<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

}

class Panel_Controller extends MY_Controller {

    function __construct($data) {
        parent::__construct();
        $this->data = $data;

        $panelLogin = $this->session->userdata('panel');
        if (empty($panelLogin)) {
            redirect('panel');
        }

        $this->data['mainMenu'] = $this->router->class;
        $this->data['subMenu'] = $this->router->method;

        $this->data['panel'] = $panelLogin;

        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['logoTitle'] = $this->setting_model->getSetting('Logo Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');
        $this->data['showItemsPerPage'] = $this->setting_model->getSetting('Show Items Per Page');

        $this->data['dateFormate'] = $this->config->item('dateFormate');
        $this->data['dateTimeFormate'] = $this->config->item('dateTimeFormate');
        if (!empty($panelLogin->permissionId)) {
            $this->data['permission'] = explode(',', $panelLogin->permissionId);
        }
        if (!empty($panelLogin->permissionGroup)) {
            $this->data['permissionGroup'] = explode(",", $panelLogin->permissionGroup);
        }
        $this->template->set_template('panel');

        $this->template->write('sitetitle', $this->setting_model->getSetting('Site Title'));

        $this->template->write_view('header', 'panel/common/header', $this->data, true);
        $this->template->write_view('horizontal_menu', 'panel/common/menu', $this->data, true);
        $this->template->write_view('footer', 'panel/common/footer', $this->data, true);
    }

}

class FrontendController extends CI_Controller {

    function __construct($data) {
        parent::__construct();

        $this->data = array();

        $this->load->model('frontEnd/matches_model');
        $last = $this->uri->total_segments();
        $matchIdSegment = $this->uri->segment($last);
        $pageSegment = $this->uri->segment($last - 1);
        $page = $pageSegment;
        $match_id = $matchIdSegment;
        $match = $this->matches_model->getMatchByMatchId($match_id);
        if (!empty($match)) {
            $this->data['hightLightMatch_id'] = $match[0]->crickMatchId;
        } else {
            $this->data['hightLightMatch_id'] = '';
        }
        if (!empty($match) && $page == "leagues") {
            $this->data['match_id'] = $match_id;
        }

        // for date & time
        $this->data['dateFormate'] = $this->config->item('dateFormate');
        $this->data['dateTimeFormate'] = $this->config->item('dateTimeFormate');
        // for date & time


        $customerLogin = $this->session->userdata('crickCustomer');

        if (!empty($customerLogin)) {
            $this->data['customer'] = $customerLogin;

            $this->data['walletBalance'] = $this->wallet_model->getTotalAmountByCustomer($customerLogin->id);
        }

        if (empty($customerLogin)) {

            $this->data['facebookLoginUrl'] = $this->facebook->login_url();
            $this->data['googleLoginUrl'] = $this->googleplus->loginURL();
        }


        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['sitetitle'] = $this->setting_model->getSetting('Site Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');

        $this->data['controllger'] = $this->router->class;
        $this->data['method'] = $this->router->method;

        $this->template->set_template('frontEnd');
        $this->template->write('sitetitle', $this->setting_model->getSetting('Site Title'));
        $this->template->write_view('header', 'frontEnd/common/header', $this->data, true);
        $this->template->write_view('footer', 'frontEnd/common/footer', $this->data, true);
    }

}

?>