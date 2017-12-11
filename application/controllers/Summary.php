<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);

        if (empty($this->data['customer'])) {
            redirect('welcome');
        }

        $this->load->model('frontEnd/league_model');
        $this->load->model('frontEnd/matches_model');
        $this->load->model('frontEnd/point_model');
        $this->load->model('panel/customer_model');
    }

    /*
      @Date
      - 21-10-2017, Created by: Ajay
      @Description
      - Use for display Match Summery
     */
    public function index($id = null) {
        $customer = $this->data['customer'];
        $match = $this->matches_model->getMatchByMatchId($id);
        $currentLoginUser = $this->session->userdata('crickCustomer');
        
        if (empty($match)) {
            redirect('error/notFound');
        }

        $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', date('Y-m-d H:i:s'));
        $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', $match[0]->startDateTime);
        if (strtotime($matchTime) > strtotime($currentTime)) {
            $this->session->set_flashdata('error', 'Match not closed.');
            redirect('welcome');
        }

        $this->data['match'] = $match[0];
        $this->data['myLeagues'] = $this->league_model->getMyLeagues($customer->id, $match[0]->crickMatchId);

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = $match[0]->title . ' Summary - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('pagetitle', $title);
        $this->template->write('bodyClass', "summaryPage");
        $this->template->write_view('css', 'frontEnd/summary/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/summary/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/summary/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 21-10-2017, Created by: Ajay
      @Description
      - Use for display Team Info
    */
    public function ajaxTeamInfo() {
        $postData = $this->input->post();

        $ids = explode('#', $postData['id']);

        $teamInfo = $this->league_model->getTeamInfoById($ids[0]);
        $league = $this->league_model->getLeagueById($ids[1]);
        $scoreCard = $this->point_model->getScoreCard($ids[2]);

        $data = array(
            'league' => $league,
            'team' => $teamInfo,
            'scoreCard' => $scoreCard
        );
        $this->load->view('frontEnd/summary/ajaxTeamInfo', $data);
    }

    /*
      @Date
      - 22-10-2017, Created by: Ajay
      @Description
      - Use for display Team Info
    */
    public function ajaxTeamInfoMy() {
        $postData = $this->input->post();

        $ids = explode('#', $postData['id']);
        $teamInfo = $this->league_model->getTeamInfoById($ids[0]);
        $league = $this->league_model->getLeagueById($ids[1]);
        $scoreCard = $this->point_model->getScoreCard($ids[2]);

        $data = array(
            'league' => $league,
            'team' => $teamInfo,
            'scoreCard' => $scoreCard
        );
        $this->load->view('frontEnd/summary/ajaxTeamInfo1', $data);
    }

}
