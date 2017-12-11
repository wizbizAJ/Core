<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class League extends Panel_Controller {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);
        $this->load->model('panel/league_model');
        $permission = $this->data['permission'];
        if (!in_array(7, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : User for display league List
     * */
    public function index() {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
            'Management' => '',
            'League' => ''
        );

        $this->data['pageTitle'] = 'League Management';
        $this->data['pageTitleSubHeading'] = 'Manage all league';


        $this->template->write('pagetitle', 'League Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('css', 'panel/league/css', $this->data, true);
        $this->template->write_view('content', 'panel/league/index', $this->data, true);
        $this->template->write_view('js', 'panel/league/js', $this->data, true);
        $this->template->write_view('subview', 'panel/league/subview', $this->data, true);
        $this->template->render();
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : User for league listing page ajax request
     * */
    public function ajaxLeague() {
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

        $league_count = $this->league_model->getAllLeagues($search_contents);
        $leagues = $this->league_model->getAllLeagues($search_contents, $sort, $limit, $offset);

        $rows = array();

        $iDisplayStart = $this->input->get('iDisplayStart');

        foreach ($leagues as $key => $league) {

            $deleteLinkIcon = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs tooltips removeLeague"  data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-times fa fa-white"></i></a>';
            $deleteLinkText = '<li><a href="javascript:void(0)" id="' . $league->id . '" class="removeLeague"> Remove </a></li>';

            $editLinkIcon = '<a href="' . base_url() . 'panel/league/update/' . $league->id . '" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>';
            $editLinkText = '<li><a href="' . base_url() . 'panel/league/update/' . $league->id . '"> Edit </a></li>';

            if ($league->isMultiEntry == 1) {
                $multiEntry = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs activeMultiEntry"  data-toggle="tooltip" data-placement="top" title="Multi Entry League"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $multiEntry = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs inactiveMultiEntry"  data-toggle="tooltip" data-placement="top" title="Not Multi Entry League"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }

            if ($league->isConfirmed == 1) {
                $isConfirmed = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs isConfirmed"  data-toggle="tooltip" data-placement="top" title="Confirmed League"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $isConfirmed = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs isNotConfirmed"  data-toggle="tooltip" data-placement="top" title="Not Confirmed League"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }

            if ($league->isActive == 1) {
                $isActive = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs isActive"  data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $isActive = '<a href="javascript:void(0)" id="' . $league->id . '" class="btn btn-transparent btn-xs inActive"  data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }

            $rows[] = array(
                '<div class="checkbox clip-check check-purple">
                    <input type="checkbox" class="hasAll" id="checkbox_' . $league->id . '" value="' . $league->id . '">
                    <label for="checkbox_' . $league->id . '">&nbsp;</label>
                </div>',
                '<a target="_blank" href="' . base_url() . 'panel/league/update/' . $league->id . '">' . $league->title . '</a>',
                $league->joinFees,
                $league->maxTeam,
                $multiEntry,
                $isConfirmed,
                $isActive,
                $this->setting_model->converTimeZone('d/m/Y H:i:s', $league->updatedAt),
                '<div class="visible-md visible-lg hidden-sm hidden-xs">
                    ' . $editLinkIcon . '
                    ' . $deleteLinkIcon . '
                </div>
                <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group dropdown ">
                            <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                    ' . $editLinkText . '
                                    ' . $deleteLinkText . '
                            </ul>
                    </div>
                </div>',
            );
        }

        $json = array(
            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => $league_count,
            'iTotalDisplayRecords' => $league_count,
            'aaData' => $rows
        );

        echo json_encode($json);
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : User Add League
     * */
    public function add() {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
            'Management' => '',
            'League' => base_url() . 'panel/league',
            'Add' => ''
        );

        $this->data['pageTitle'] = 'League Management';
        $this->data['pageTitleSubHeading'] = 'Manage all league';

        $this->template->write('pagetitle', 'League Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
        $this->template->write_view('css', 'panel/league/add_css', $this->data, true);
        $this->template->write_view('content', 'panel/league/add', $this->data, true);
        $this->template->write_view('js', 'panel/league/add_js', $this->data, true);
        $this->template->write_view('subview', 'panel/league/subview', $this->data, true);
        $this->template->render();
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : Logic for insert league
     * */
    public function insert() {
        $postData = $this->input->post();
        $error = 0;
        if (!empty($postData)) {
            $rank = $postData['rank'];
            $prize = $postData['prize'];
            // Check For Price 
            $priceVal1 = 0;
            $priceVal2 = 0;
            $greater = NULL;
            foreach ($rank as $key => $value) {
                $rankV = explode("-", $value);
                if ($rankV[0] > $rankV[1]) {
                    $greater[] = 1;
                }
                if ($rankV[0] == $rankV[1]) {
                    $priceVal1 = $rankV[0] * $prize[$key];
                } else {
                    $countGap = 0;
                    for ($i = $rankV[0]; $i <= $rankV[1]; $i++) {
                        $countGap = $countGap + 1;
                    }
                    $priceVal2 = $countGap * $prize[$key];
                }
            }
            $finalPrice = floatVal($priceVal1 + $priceVal2);
            $winPrice = floatVal($postData['winPrice']);
            if ($finalPrice != $winPrice) {
                $error = 2;
            }
            // Check For Price
           
            foreach ($rank as $key => $value) {
                if (empty($rank[$key]) || !is_numeric($prize[$key])) {
                    $error = 1;
                }
            }

            if ($error == 0) {
                $insertData = array(
                    'title' => $postData['title'],
                    'joinFees' => $postData['joinFees'],
                    'maxTeam' => $postData['maxTeam'],
                    'winPrice' => $postData['winPrice'],
                    'totalWinners' => $postData['totalWinners'],
                    'isMultiEntry' => $postData['isMultiEntry'],
                    'isConfirmed' => $postData['isConfirmed'],
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s'),
                    'createdAt' => date('Y-m-d H:i:s')
                );
                $insertId = $this->league_model->add($insertData);

                foreach ($rank as $key => $value) {
                    $insertWinner = array(
                        'leagueId' => $insertId,
                        'rank' => $rank[$key],
                        'prize' => $prize[$key]
                    );
                    $insertWinnerId = $this->league_model->addWinner($insertWinner);
                }

                $this->session->set_flashdata('success', 'League added successfully.');
            } else if (isset($greater) && !empty($greater)) {
                if (in_array("1", $greater)) {
                    $error = 2;
                    $this->session->set_flashdata('error', 'Enter Valid Rank.');
                }
            } else if ($error == 2) {
                $this->session->set_flashdata('error', 'Winner Price is not match.');
            } else {
                $this->session->set_flashdata('postData', $postData);
                $this->session->set_flashdata('error', 'Please enter rank and prize.');
            }
            echo $error;
        } else {
            $this->session->set_flashdata('error', 'Something went wrong.');
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : User Update League
     * */
    public function update($id) {
        $league = $this->league_model->getLeague(NULL, $id);
        if (!empty($league)) {
            $this->data['breadcrumb'] = array(
                '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url() . 'panel/welcome',
                'Management' => '',
                'League' => base_url() . 'panel/league',
                'Update' => ''
            );

            $leagueWinner = $this->league_model->getLeagueWinner($id);

            $this->data['league'] = $league[0];
            $this->data['leagueWinner'] = $leagueWinner;

            $this->data['pageTitle'] = 'League Management';
            $this->data['pageTitleSubHeading'] = 'Manage all league';

            $this->template->write('pagetitle', 'Point Management');
            $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data, true);
            $this->template->write_view('css', 'panel/league/update_css', $this->data, true);
            $this->template->write_view('content', 'panel/league/update', $this->data, true);
            $this->template->write_view('js', 'panel/league/update_js', $this->data, true);
            $this->template->write_view('subview', 'panel/league/subview', $this->data, true);
            $this->template->render();
        } else {
            $this->session->set_flashdata('error', 'League not found.');
            redirect('panel/league');
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : remove League
     * */
    public function remove() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (is_array($postData['id'])) {
                foreach ($postData['id'] as $key => $val) {
                    $this->league_model->remove($val);
                    $this->league_model->removeWinnersByLeague($val);
                }
            } else {
                $this->league_model->remove($postData['id']);
                $this->league_model->removeWinnersByLeague($postData['id']);
            }
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : Change multyEntry
     * */
    public function updateMultyEntry() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (is_array($postData['id'])) {
                foreach ($postData['id'] as $key => $val) {
                    $updateData = array(
                        'isMultiEntry' => $postData['isMultiEntry'],
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->league_model->update($updateData, $val);
                }
            } else {
                $updateData = array(
                    'isMultiEntry' => $postData['isMultiEntry'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->league_model->update($updateData, $postData['id']);
            }
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
     * Description : Change Confirmed League
     * */
    public function updateConfirmed() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (is_array($postData['id'])) {
                foreach ($postData['id'] as $key => $val) {
                    $updateData = array(
                        'isConfirmed' => $postData['isConfirmed'],
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->league_model->update($updateData, $val);
                }
            } else {
                $updateData = array(
                    'isConfirmed' => $postData['isConfirmed'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->league_model->update($updateData, $postData['id']);
            }
        }
    }

    /**
     * Date : 25-11-2016
     * Created By : Ajay
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
                    $this->league_model->update($updateData, $val);
                }
            } else {
                $updateData = array(
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->league_model->update($updateData, $postData['id']);
            }
        }
    }

}
