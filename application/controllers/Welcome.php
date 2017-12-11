<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);
        $this->load->model('frontEnd/matches_model');
    }

    /*
      @Date
      - 25-11-2017, Created by: Ajay
      @Description
      - Use for display Home Page
     */

    public function index() {
        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'Home - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $data['smatch_id'] = $this->data['hightLightMatch_id'];
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/home/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/home/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/home/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 26-11-2017, Created by: Ajay
      @Description
      - Use for display Matches in header
     */

    public function headerMatch() {

        $postData = $this->input->post();
        $customerLogin = $this->session->userdata('crickCustomer');

        if (empty($customerLogin)) {
            //$postData['limit']=3;
        }
        $data['customer'] = $customerLogin;
        $data['smatch_id'] = $postData['smatch_id'];
        $data['match'] = $this->matches_model->getMatchForHeader($postData);

        $this->load->view('frontEnd/common/matchList', $data);
    }

}
