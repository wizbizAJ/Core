<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Myteam extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);

        if (empty($this->data['customer'])) {
            redirect('welcome');
        }

        $this->load->model('frontEnd/league_model');
        $this->load->model('frontEnd/matches_model');
        $this->load->model('frontEnd/player_model');
        $this->load->model('frontEnd/team_model');
        $this->load->model('panel/selection_criteria_model');
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
        if (empty($match)) {
            redirect('error/notFound');
        }

        $this->data['match'] = $match[0];

        $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', date('Y-m-d H:i:s'));
        $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', $match[0]->startDateTime);
        if ($matchTime < $currentTime) {
            $this->session->set_flashdata('error', 'Match closed.');
            redirect('welcome');
        }

        $this->data['matchSquads'] = $this->matches_model->getMatchSquadsWithCredit($id);
        $this->data['selectionCriteria'] = $this->selection_criteria_model->getSelectionCriteria();
        $this->data['customerTeam'] = $this->team_model->getCustomerTeam($customer->id, $id);

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'Leagues - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('bodyClass', "myTeamPage");
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/myteam/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/myteam/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/myteam/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 08-11-2017, Created by: Ajay
      @Description
      - Use for display check credit when customer create team
     */

    public function checkTeam() {
        $postData = $this->input->post();
        $totalCredit = 100;

        $playersIds = explode(',', $postData['playersIds']);

        foreach ($playersIds as $key => $val) {
            if (!empty($val)) {
                $playerInfo = $this->player_model->getPlayerByPlayerId($val);
                if (!empty($playerInfo)) {
                    $totalCredit = $totalCredit - $playerInfo[0]->credits;
                }
            }
        }
        echo $totalCredit;
    }

    /*
      @Date
      - 09-11-2017, Created by: Ajay
      @Description
      - Use for display selected team
     */

    public function loadTeam() {
        $postData = $this->input->post();

        $matchSquads = $this->player_model->getPlayerInfo($postData['playersIds']);

        $data['matchSquads'] = $matchSquads;
        $this->load->view('frontEnd/myteam/loadTeam', $data);
    }

    /*
      @Date
      - 09-11-2017, Created by: Ajay
      @Description
      - Use for display selected team
     */

    public function saveTeam() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();

        $match = $this->matches_model->getMatchByMatchId($postData['matchId']);
        if (!empty($match)) {
            $insertTeam = array(
                'customerId' => $customer->id,
                'crickMatchId' => $postData['matchId'],
                'createdAt' => date('Y-m-d H:i:s')
            );
            $this->db->insert('customerteam', $insertTeam);
            $teamId = $this->db->insert_id();

            $playersIds = explode(',', $postData['playersIds']);
            foreach ($playersIds as $key => $val) {
                $captain = 0;
                $viceCaptain = 0;
                if ($postData['captainId'] == $val) {
                    $captain = 1;
                }
                if ($postData['viceCaptainId'] == $val) {
                    $viceCaptain = 1;
                }

                $insertPlayer = array(
                    'customerTeamId' => $teamId,
                    'crickPlayerId' => $val,
                    'isCaptain' => $captain,
                    'ISViceCaptain' => $viceCaptain
                );
                $this->db->insert('customerteamplayer', $insertPlayer);
            }

            $this->session->set_flashdata('success', 'Team save successfully.');
        } else {
            $this->session->set_flashdata('error', 'Sorry! somthing went wrong, Please try again after some time.');
        }
    }

    /*
      @Date
      - 10-11-2017, Created by: Ajay
      @Description
      - Use for display player profile info
     */

    public function playerInfo() {
        $postData = $this->input->post();
        $data['playerInfo'] = $this->setting_model->getApiData('playerStats', 'pid=' . $postData['playerId']);
        $this->load->view('frontEnd/myteam/playerInfo', $data);
    }

    /*
      @Date
      - 14-11-2017, Created by: Ajay
      @Description
      - Use for update Team
     */

    public function update($dbId, $id) {
        $customer = $this->data['customer'];
        $match = $this->matches_model->getMatchByMatchId($id);
        if (empty($match)) {
            redirect('error/notFound');
        }

        $this->data['match'] = $match[0];

        $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', date('Y-m-d H:i:s'));
        $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s', $match[0]->startDateTime);
        if ($matchTime < $currentTime) {
            $this->session->set_flashdata('error', 'Match closed.');
            redirect('welcome');
        }

        $this->data['matchSquads'] = $this->matches_model->getMatchSquadsWithCredit($id);
        $this->data['selectionCriteria'] = $this->selection_criteria_model->getSelectionCriteria();

        $this->data['customerTeam'] = $this->team_model->getCustomerTeam($customer->id, $id, $dbId);
        $this->data['customerPreviousTeam'] = $this->team_model->getCustomerPreviousTeam($customer->id, $id, $dbId);


        $this->data['myTeam'] = $this->team_model->getCustomerTeamById($dbId);

        $totalCreditUse = 0;
        $playerArray = array();
        foreach ($this->data['myTeam'] as $tKey => $tVal) {
            $totalCreditUse = $totalCreditUse + floatval($tVal->credits);
            $playerArray[] = $tVal->crickPlayerId;
        }
        $this->data['totalCreditUse'] = $totalCreditUse;
        $this->data['playerArray'] = $playerArray;


        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'Leagues - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/myteam/update_css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/myteam/update', $this->data, true);
        $this->template->write_view('js', 'frontEnd/myteam/update_js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 14-11-2017, Created by: Ajay
      @Description
      - Use for update team player
     */
    public function updateTeam() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();
        $teamId = $this->input->post('teamId');
        $captainId = $this->input->post('captainId');
        $viceCaptainId = $this->input->post('viceCaptainId');

        $match = $this->matches_model->getMatchByMatchId($postData['matchId']);
        if (!empty($match)) {
            $myOldTeam = $this->team_model->getCustomerTeamById($teamId);

            $oldTeamAttay = array();
            foreach ($myOldTeam as $oKey => $oVal) {
                $oldTeamAttay[] = $oVal->crickPlayerId;
            }

            $playersIds = explode(',', $postData['playersIds']);
            foreach ($playersIds as $key => $val) {
                if (in_array($val, $oldTeamAttay)) {
                    unset($playersIds[$key]);

                    $pos = array_search($val, $oldTeamAttay);
                    unset($oldTeamAttay[$pos]);
                }
            }

            foreach ($playersIds as $key => $val) {
                $insertPlayer = array(
                    'customerTeamId' => $teamId,
                    'crickPlayerId' => $val
                );
                $this->db->insert('customerteamplayer', $insertPlayer);
            }

            foreach ($oldTeamAttay as $key => $val) {
                $this->db->where('customerTeamId', $teamId);
                $this->db->where('crickPlayerId', $val);
                $this->db->delete('customerteamplayer');
            }

            $updateData = array(
                'isCaptain' => 0,
                'IsViceCaptain' => 0
            );
            $this->db->where('customerTeamId', $teamId);
            $this->db->update('customerteamplayer', $updateData);

            $updateData = array(
                'isCaptain' => 1
            );
            $this->db->where('customerTeamId', $teamId);
            $this->db->where('crickPlayerId', $captainId);
            $this->db->update('customerteamplayer', $updateData);

            $updateData = array(
                'IsViceCaptain' => 1
            );
            $this->db->where('customerTeamId', $teamId);
            $this->db->where('crickPlayerId', $viceCaptainId);
            $this->db->update('customerteamplayer', $updateData);

            $this->session->set_flashdata('success', 'Team update successfully.');
        } else {
            $this->session->set_flashdata('error', 'Sorry! somthing went wrong, Please try again after some time.');
        }
    }

}
