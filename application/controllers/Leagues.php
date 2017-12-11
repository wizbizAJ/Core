<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leagues extends FrontendController {

    function __construct() {
        $this->data = array();

        parent::__construct($this->data);
        if (empty($this->data['customer'])) {
            redirect('welcome');
        }
        $this->load->model('frontEnd/league_model');
        $this->load->model('frontEnd/matches_model');
        $this->load->model('frontEnd/team_model');
        $this->load->model('panel/customer_model');
        $this->load->model('frontEnd/point_model');
    }

    /*
      @Date
      - 03-11-2017, Created by: Ajay
      @Description
      - Use for display Leagues
     */

    public function index($id = null) {
        $customer = $this->data['customer'];
        $match = $this->matches_model->getMatchByMatchId($id);
        $currentLoginUser = $this->session->userdata('crickCustomer');
        if (!empty($currentLoginUser)) {
            $teamInfo = $this->league_model->getTeamInfo($currentLoginUser->id, $id);
        }
        if (empty($match)) {
            redirect('error/notFound');
        }
        $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', date('Y-m-d H:i:s'));
        $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', $match[0]->startDateTime);
        if ($matchTime < $currentTime) {
            $this->session->set_flashdata('error', 'Match closed.');
            redirect('welcome');
        }
        $data['activematch_id'] = $this->data['hightLightMatch_id'];
        $this->data['match'] = $match[0];
        $this->data['teamInfo'] = $teamInfo;
        $this->data['myLeagues'] = $this->league_model->getMyLeagues($customer->id, $match[0]->crickMatchId);

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'Leagues - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('bodyClass', "leaguesPage");
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/leagues/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/leagues/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/leagues/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 03-11-2017, Created by: Ajay
      @Description
      - Use for display Leagues
     */
    public function ajaxList() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();

        $data['leagues'] = $this->league_model->getActiveLeagues($postData);
        $data['matchId'] = $postData['matchId'];
        $data['customer'] = $this->data['customer'];


        $this->load->view('frontEnd/leagues/ajaxList', $data);
    }

    /*
      @Date
      - 14-11-2017, Created by: Ajay
      @Description
      - Use for attemp join league
     */

    public function joinLeague() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();

        $id = explode('#', $postData['id']);
        $matchId = $id[0];
        $leagueId = $id[1];

        $data['id'] = $postData['id'];
        $data['matchId'] = $matchId;
        $data['leagueId'] = $leagueId;

        /* Start :: Check if team is exist or not */
        $data['customerTeam'] = $this->team_model->getUnselectedCustomerTeam($customer->id, $matchId, $leagueId);
        /* End :: Check if team is exist or not */
        
        $teamInfo = $this->league_model->getTeamInfo($customer->id, $matchId);
        $teams = NULL;
        $joinIds = NULL;
        if (!empty($data['customerTeam'])) {
            foreach ($data['customerTeam'] as $key => $value) {
                $joinIds[] = $value->id;
            }
        }
        if (!empty($teamInfo)) {
            foreach ($teamInfo as $key => $val) {
                $teams[] = $val['team'];
            }
        }
        $data['teamInfoDetail'] = $teams;
        $data['joinLeagueIds'] = $joinIds;
        $serchData['leagueId'] = $leagueId;
        $serchData['matchId'] = $matchId;
        $data['leagueInfo'] = $this->league_model->getActiveLeagues($serchData);

        $this->load->view('frontEnd/leagues/joinLeagues', $data);
    }

    /*
      @Date
      - 14-11-2017, Created by: Ajay
      @Description
      - Use for attemp join league
     */

    public function saveLeague() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();

        $id = explode('#', $postData['id']);
        $matchId = $id[0];
        $leagueId = $id[1];

        $serchData['leagueId'] = $leagueId;
        $serchData['matchId'] = $matchId;
        $leagueInfo = $this->league_model->getActiveLeagues($serchData);

        $customerTeam = $this->team_model->getCustomerTeam($customer->id, $matchId);

        if (!empty($customerTeam) && ($leagueInfo[0]['league']->maxTeam > count($leagueInfo[0]['teamsJoin']))) {

            if (!empty($leagueInfo)) {
                $insertData = array(
                    'matchId' => $matchId,
                    'leagueId' => $leagueId,
                    'customerId' => $customer->id,
                    'customerTeamId' => $postData['customerTeamId'],
                    'createdAt' => date('Y-m-d H:i:s')
                );
                $joinLeagueId = $this->league_model->joinLeague($insertData);

                if ($leagueInfo[0]['league']->joinFees != 0) {
                    $insertData = array(
                        'customerId' => $customer->id,
                        'amount' => $leagueInfo[0]['league']->joinFees,
                        'actionType' => 'Debit',
                        'comments' => 'Paid for ' . $leagueInfo[0]['league']->title,
                        'customerJoinLeagueId' => $joinLeagueId,
                        'createdAt' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('wallet', $insertData);
                }
            }
        }
    }

    /*
      @Date
      - 15-11-2017, Created by: Ajay
      @Description
      - Use for change team in my league
     */

    public function saveMyLeague() {
        $postData = $this->input->post();
        $id = $postData['id'];
        $teamId = $postData['teamId'];


        $leagueInfo = $this->league_model->getActiveLeagues($serchData);

        $updateInfo = array(
            'customerTeamId' => $teamId
        );

        $this->db->where('id', $id);
        $this->db->update('customerjoinleague', $updateInfo);
    }

    /*
      @Date
      - 22-11-2017, Created by: Ajay
      @Description
      - Use for display all leagues
     */

    public function myLeagues() {
        $customer = $this->data['customer'];

        $myLeagues = $this->customer_model->getMyLeagues($customer->id);
        $this->data['myLeagues'] = $myLeagues;

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'My Leagues - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/leagues/myLeagues_css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/leagues/myLeagues', $this->data, true);
        $this->template->write_view('js', 'frontEnd/leagues/myLeagues_js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 03-12-2017, Created by: Ajay
      @Description
      - Use for display Team Info
     */

    public function ajaxTeamInfo() {
        $currentLoginUser = $this->session->userdata('crickCustomer');
        $postData = $this->input->post();
        $ids = explode('#', $postData['id']);

        if (!empty($currentLoginUser)) {
            $teamInfo = $this->league_model->getTeamInfoByTeamId($ids[0], $currentLoginUser->id, $ids[1]);
            $allTeam = $this->league_model->getTeamInfo($currentLoginUser->id, $teamInfo[0]['team']->crickMatchId);
        }

        $data = array(
            'team' => $teamInfo,
            'countTeam' => count($allTeam) + 1
        );
        $this->load->view('frontEnd/leagues/ajaxTeamInfo', $data);
    }

}
