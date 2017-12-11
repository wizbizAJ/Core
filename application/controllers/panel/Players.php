<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Players extends Panel_Controller {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);
        $this->load->model('panel/player_model');
         $permission = $this->data['permission'];
        if (!in_array(11, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : User for display player List
     * */
    public function index() {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
            'Players' => ''
        );

        $this->data['pageTitle'] = 'Player Management';
        $this->data['pageTitleSubHeading'] = 'Manage all player';


        $this->template->write('pagetitle', 'Player Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('css', 'panel/players/css', $this->data, true);
        $this->template->write_view('content', 'panel/players/index', $this->data, true);
        $this->template->write_view('js', 'panel/players/js', $this->data, true);
        $this->template->write_view('subview', 'panel/players/subview', $this->data, true);
        $this->template->render();
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : User for player listing page ajax request
     * */
    public function ajaxPlayer() {
        $limit = (int) $this->input->get('iDisplayLength');
        $offset = (int) $this->input->get('iDisplayStart');

        $search_contents['freetext'] = (string) $this->input->get('sSearch');

        for ($i = 0; $i < $this->input->get('iColumns'); $i++) {
            $search_contents['columntext'][] = (string) $this->input->get('sSearch_' . $i);
        }

        if ($this->input->get('iSortCol_0') != '') {
            for ($i = 0; $i < $this->input->get('iSortingCols'); $i++) {
                $sortcol = $this->input->get('iSortCol_' . $i);
                $sort[$sortcol] = $this->input->get('sSortDir_' . $i);
            }
        } else {
            $sort = null;
        }

        $player_count = $this->player_model->getAllPlayers($search_contents);
        $players = $this->player_model->getAllPlayers($search_contents, $sort, $limit, $offset);
       
        $rows = array();

        $iDisplayStart = $this->input->get('iDisplayStart');

        foreach ($players as $key => $player) {


            $editLinkIcon = '<a href="' . base_url() . 'panel/players/update/' . $player->crickPlayerId . '" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>';
            $editLinkText = '<li><a href="' . base_url() . 'panel/players/update/' . $player->crickPlayerId . '"> Edit </a></li>';



            if ($player->isActive == 1) {
                $isActive = '<a href="javascript:void(0)" id="' . $player->id . '" class="btn btn-transparent btn-xs isActive"  data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $isActive = '<a href="javascript:void(0)" id="' . $player->id . '" class="btn btn-transparent btn-xs inActive"  data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }
            if (!empty($player->profileImage) && file_exists('./assets/user_files/player/' . $player->country . '/' . $player->profileImage)) {
                $imgStr = '<img src="' . base_url() . 'assets/user_files/player/' . $player->country . '/' . $player->profileImage . '" class="avatar avatar-lg margin-right-15"  style="width:50px;"/>';
            } else {
                $imgStr = '<img src="' . base_url() . 'assets/images/default-user.png" class="avatar avatar-lg"  style="width:50px;"/>';
            }

            $rows[] = array(                
                '<a target="_blank" href="' . base_url() . 'panel/players/profile/' . $player->crickPlayerId . '">' . $player->crickPlayerId . '</a>',
                $imgStr,
                $player->name,
                $player->majorTeams,
                $player->country,
                $player->playerType,
                $player->credits,               
                $this->setting_model->converTimeZone('d/m/Y H:i:s', $player->updatedAt),
                '<div class="visible-md visible-lg hidden-sm hidden-xs">
                    ' . $editLinkIcon . '                    
                </div>
                <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group dropdown ">
                            <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                    ' . $editLinkText . '                                  
                            </ul>
                    </div>
                </div>',
            );
        }

        $json = array(
            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => $player_count,
            'iTotalDisplayRecords' => $player_count,
            'aaData' => $rows
        );

        echo json_encode($json);
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : User Add Player
     * */
    public function add() {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
            'Players' => base_url() . 'panel/players',
            'Add' => ''
        );
        $this->data['pageTitle'] = 'Player Management';
        $this->data['pageTitleSubHeading'] = 'Manage all player';

        $this->template->write('pagetitle', 'Player Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('css', 'panel/players/add_css', $this->data, true);
        $this->template->write_view('content', 'panel/players/add', $this->data, true);
        $this->template->write_view('js', 'panel/players/add_js', $this->data, true);
        $this->template->write_view('subview', 'panel/players/subview', $this->data, true);
        $this->template->render();
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : User Add Player
     * */
    public function addPlayer($pId) {
       
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
            'Players' => base_url() . 'panel/players',
            'Add' => ''
        );
        $playerInfo = $this->setting_model->getApiData('playerStats', 'pid=' . $pId);
        if (empty($playerInfo['error'])) {
            $playerInfoByDb = $this->player_model->getPlayerByPlayerId($pId);
            if (empty($playerInfoByDb)) {
                $this->player_model->addPlayer($playerInfo, $pId);
                $this->session->set_flashdata('success', 'Player added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Sorry! Player already exit.');
            }
        } else {
            $this->session->set_flashdata('error', 'Sorry! No data available, Please try again after some time.');
        }
        redirect('panel/players');
        
        $this->data['pageTitle'] = 'Player Management';
        $this->data['pageTitleSubHeading'] = 'Manage all player';

        $this->template->write('pagetitle', 'Player Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('css', 'panel/players/add_css', $this->data, true);
        $this->template->write_view('content', 'panel/players/add', $this->data, true);
        $this->template->write_view('js', 'panel/players/add_js', $this->data, true);
        $this->template->write_view('subview', 'panel/players/subview', $this->data, true);
        $this->template->render();
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : Use For Update Profile Player
     * */
    public function update($id) {
        $player = $this->player_model->getPlayerByPlayerByDbId($id);
        if (!empty($player)) {
            $this->data['breadcrumb'] = array(
                '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
                'Players' => base_url() . 'panel/players',
                'Profile' => ''
            );
            $this->data['flag'] = 1;
            $this->data['playerDetails'] = $player;
            $this->data['pageTitle'] = 'Player Management';
            $this->data['pageTitleSubHeading'] = 'Update player information';

            $this->template->write('pagetitle', 'Point Management');
            $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
            $this->template->write_view('css', 'panel/players/profile_css', $this->data, true);
            $this->template->write_view('content', 'panel/players/profile', $this->data, true);
            $this->template->write_view('js', 'panel/players/profile_js', $this->data, true);
            $this->template->write_view('subview', 'panel/players/subview', $this->data, true);
            $this->template->render();
        } else {
            $this->session->set_flashdata('error', 'Player not found.');
            redirect('panel/players');
        }
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : Use For Profile Player
     * */
    public function profile($id) {
        $player = $this->player_model->getPlayerByPlayerByDbId($id);
        if (!empty($player)) {
            $this->data['breadcrumb'] = array(
                '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
                'Players' => base_url() . 'panel/players',
                'Profile' => ''
            );
            $this->data['flag'] = 0;
            $this->data['playerDetails'] = $player;
            $this->data['pageTitle'] = 'Player Management';
            $this->data['pageTitleSubHeading'] = 'Update player information';

            $this->template->write('pagetitle', 'Point Management');
            $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
            $this->template->write_view('css', 'panel/players/profile_css', $this->data, true);
            $this->template->write_view('content', 'panel/players/profile', $this->data, true);
            $this->template->write_view('js', 'panel/players/profile_js', $this->data, true);
            $this->template->write_view('subview', 'panel/players/subview', $this->data, true);
            $this->template->render();
        } else {
            $this->session->set_flashdata('error', 'Player not found.');
            redirect('panel/players');
        }
    }

    /**
     * Date : 04-03-201
     * Created By : Sejal
     * Description : Update Player Info
     * */
    public function updatePlayer($id) {
        $player = $this->player_model->getPlayer(NULL, $id);
        if (!empty($player)) {
            $postData = $this->input->post();
            if (!empty($postData)) {
                $updateData = array(
                    'playerType' => $postData['playerType'],
                    'credits' => $postData['credits'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->player_model->update($updateData, $id);
                $this->session->set_flashdata('success', 'Player updated successfully.');
            }
        } else {
            $this->session->set_flashdata('error', 'Player not found.');
        }
        redirect('panel/players');
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : Change Active
     * */
    public function updateIsActive() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (is_array($postData['id'])) {
                foreach ($postData['id'] as $key => $val) {
                    $updateData = array(
                        'isActive' => $postData['isActive'],
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->player_model->update($updateData, $val);
                }
            } else {
                $updateData = array(
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->player_model->update($updateData, $postData['id']);
            }
        }
    }

    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : Use for Searching Player
     * */
    public function getPlayerDetailByAPI() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $playerId = $postData['playerId'];
            $str1 = "playerStats";
            $str2 = "pid=" . $playerId;
            $playerInfoByDb = $this->player_model->getPlayerByPlayerByDbId($playerId);
            if (empty($playerInfoByDb)) {
                $playerInfo = $this->setting_model->getApiData($str1, $str2);
                $flag = 0;
            } else {
                $playerInfo = $playerInfoByDb;
                $flag = 1;
            }

            $data = array(
                'playerDetails' => $playerInfo,
                'pId' => $playerId,
                'flag' => $flag
            );
            $this->load->view('panel/players/playerDetails', $data);
        }
    }

}
