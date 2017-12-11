<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scorecard extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);

        if (empty($this->data['customer'])) {
            redirect('welcome');
        }

        $this->load->model('frontEnd/point_model');
        $this->load->model('frontEnd/league_model');
    }

    /*
      @Date
      - 21-11-2017, Created by: Ajay
      @Description
      - Use for display Match Score Card
    */
    public function index($id = null) {
        $customer = $this->data['customer'];
        $joinLeague = $this->league_model->getJoinLeagueById($id);
        if (empty($joinLeague)) {
            redirect('error/notFound');
        }

        if ($joinLeague[0]->customerId != $customer->id) {
            redirect('error/notFound');
        }

        $scoreCard = $this->point_model->getScoreCard($id);

        if (empty($id)) {
            redirect('error/notFound');
        }
        if (empty($scoreCard)) {
            redirect('error/notFound');
        }

        $this->data['scorecard'] = $scoreCard[0];

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = $scoreCard[0]['info']['mathName'] . ' Score Card - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/scorecard/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/scorecard/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/scorecard/js', $this->data, true);
        $this->template->render();
    }

}
