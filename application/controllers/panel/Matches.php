<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Matches extends Panel_Controller {
    
    function __construct() {
        $this->data=array();
        parent::__construct($this->data);
        $this->load->model('panel/matches_model');
        $this->load->model('panel/player_model');
        $this->load->model('panel/league_model');
        $this->load->model('frontEnd/point_model');
         $permission = $this->data['permission'];
        if (!in_array(10, $permission)) {
            redirect('panel/error/forbidden');
        }
    }
        
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : User for display matche List
    **/
    public function index()
    {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'Matches' => ''
        );
        
        $this->data['pageTitle'] = 'Matches Management';
        $this->data['pageTitleSubHeading'] = 'Manage all matches';
        
        
        $this->template->write('pagetitle', 'Matches Management');        
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/matches/css', $this->data , true);
        $this->template->write_view('content', 'panel/matches/index', $this->data , true);
        $this->template->write_view('js', 'panel/matches/js', $this->data , true);
        $this->template->write_view('subview', 'panel/matches/subview', $this->data , true);
        $this->template->render();
    }
    
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : User for match listing page ajax request
    **/
    public function ajaxMatch()
    {
        $limit = (int) $this->input->get('iDisplayLength');
        $offset = (int) $this->input->get('iDisplayStart');

        $search_contents['freetext'] = (string) $this->input->get('sSearch');

        for($i=0; $i<$this->input->get('iColumns'); $i++)
        {
        	$search_contents['columntext'][] = (string) $this->input->get('sSearch_'.$i);
        }
        
        if ($this->input->get('iSortCol_0') != '') {
            for ($i = 0; $i < $this->input->get('iSortingCols'); $i++) {
                $sortcol = $this->input->get('iSortCol_' . $i);
                $sort[$sortcol] = $this->input->get('sSortDir_' . $i);
            }
        } else
        {
            $sort = null;
        }
        
        $match_count = $this->matches_model->getAllMatches($search_contents);
        $matches = $this->matches_model->getAllMatches($search_contents, $sort, $limit, $offset);
        
        $rows = array();

        $iDisplayStart = $this->input->get('iDisplayStart');

        foreach ($matches as $key => $match) {

            $deleteLinkIcon='<a href="javascript:void(0)" id="'.$match->crickMatchId.'" class="btn btn-transparent btn-xs tooltips removeMatch"  data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-times fa fa-white"></i></a>';
            $deleteLinkText='<li><a href="javascript:void(0)" id="'.$match->crickMatchId.'" class="removeMatch"> Remove </a></li>';
            
            $editLinkIcon='<a href="'.base_url().'panel/matches/update/'.$match->crickMatchId.'" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>';
            $editLinkText='<li><a href="'.base_url().'panel/matches/update/'.$match->crickMatchId.'"> Edit </a></li>';
            
            if($match->isActive==1)
            {
                $isActive = '<a href="javascript:void(0)" id="'.$match->id.'" class="btn btn-transparent btn-xs isActive"  data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle-o text-success"></i></a>';
            }
            else
            {
                $isActive = '<a href="javascript:void(0)" id="'.$match->id.'" class="btn btn-transparent btn-xs inActive"  data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }
            
            $currentTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',date('Y-m-d H:i:s'));
            $matchTime = $this->setting_model->converTimeZone('d/m/Y H:i:s',$match->startDateTime);
            if($matchTime < $currentTime)
            {                        
                if($match->matchClose==1)
                {
                    $isClose = '<a href="javascript:void(0)" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-check-circle-o text-success"></i></a>';
                }
                else
                {
                    $isClose = '<a href="javascript:void(0)" id="'.$match->id.'" class="btn btn-transparent btn-xs closeMatchBtn"  data-toggle="tooltip" data-placement="top" title="No"><i class="fa fa-check-circle-o text-danger"></i></a>';
                }
            }
            else
            {
                $isClose='';
            }            
            
            $rows[] = array(
                '<div class="checkbox clip-check check-purple">
                    <input type="checkbox" class="hasAll" id="checkbox_'.$match->crickMatchId.'" value="'.$match->crickMatchId.'">
                    <label for="checkbox_'.$match->crickMatchId.'">&nbsp;</label>
                </div>',
                '<a target="_blank" href="'.base_url().'panel/matches/profile/'.$match->crickMatchId.'">'.$match->crickMatchId.'</a>',
                $match->title,
                $match->matchType,
                $this->setting_model->converTimeZone('d/m/Y H:i:s',$match->startDateTime),
                $isClose,
                $isActive,
                $this->setting_model->converTimeZone('d/m/Y H:i:s',$match->updatedAt),
                '<div class="visible-md visible-lg hidden-sm hidden-xs">
                    '.$editLinkIcon.'
                    '.$deleteLinkIcon.'
                </div>
                <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group dropdown ">
                            <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                    '.$editLinkText.'
                                    '.$deleteLinkText.'
                            </ul>
                    </div>
                </div>',
            );
        }

        $json = array(
            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => $match_count,
            'iTotalDisplayRecords' => $match_count,
            'aaData' => $rows
        );

        echo json_encode($json);
    }
    
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : User Add Match
    **/
    public function add()
    {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',           
            'Matches' => base_url().'panel/matches',
            'Add' => ''
        );
        
        $this->data['pageTitle'] = 'Match Management';
        $this->data['pageTitleSubHeading'] = 'Manage all match';
        
        $this->data['matches'] = $this->setting_model->getApiData('matches');
        
        $this->template->write('pagetitle', 'Match Management');
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/matches/add_css', $this->data , true);
        $this->template->write_view('content', 'panel/matches/add', $this->data , true);
        $this->template->write_view('js', 'panel/matches/add_js', $this->data , true);
        $this->template->write_view('subview', 'panel/matches/subview', $this->data , true);
        $this->template->render();
    }
    
    /**
     * Date : 03-03-2016
     * Created By : Ajay
     * Description : Logic for display match info
    **/
    public function getMatchInfo()
    {
        try
        {
            $matchId = $this->input->post('id');
            $this->data['matcheSummary'] = $this->setting_model->getApiData('fantasySummary','unique_id='.$matchId);           
            $this->data['matcheInfo'] = $this->setting_model->getApiData('cricketScore','unique_id='.$matchId);
            $this->data['matchId'] = $matchId;
            $this->load->view('panel/matches/summary',$this->data);
        }catch (Exception $e) {
            $this->data['matcheSummary'] = '';
            $this->data['matcheInfo'] = '';
            $this->load->view('panel/matches/summary',$this->data);
        }
    }
    
    /**
     * Date : 03-03-2016
     * Created By : Ajay
     * Description : Logic for add match info in to database
    **/
    public function addMatch()
    {
        try
        {
            $matchId = $this->input->post('id');
            $matchType = $this->input->post('matchType');
            $matcheSummary = $this->setting_model->getApiData('fantasySummary','unique_id='.$matchId);
            $matcheInfo = $this->setting_model->getApiData('cricketScore','unique_id='.$matchId);
            
            if (empty($matcheSummary['error']) && empty($matcheInfo['error']))
            {
                $insertMatch = array(
                    'crickMatchId' => $matchId,
                    'matchType' => $matchType,
                    'title' => $matcheInfo['team-1'] . ' vs ' . $matcheInfo['team-2'],
                    'startDateTime' => $matcheInfo['dateTimeGMT'],
                    'updatedAt' => date('Y-m-d H:i:s'),
                    'createdAt' => date('Y-m-d H:i:s')
                );
                
                $insertedId = $this->matches_model->addMatch($insertMatch);
                $this->session->set_flashdata('success', 'Match added successfully.');

                for($i=0;$i<2;$i++){
                    foreach($matcheSummary['data']['team'][$i]['players'] as $playerKey => $playerVal)
                    {
                        $insertMatchSquads = array(
                            'crickMatchId' => $matchId,
                            'crickPlayerId' => $playerVal['pid'],
                            'teamName' => $matcheSummary['data']['team'][$i]['name'],
                            'updatedAt' => date('Y-m-d H:i:s'),
                            'createdAt' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('matchsquads',$insertMatchSquads);
                    }
                }
                
                for($i=0;$i<2;$i++){
                    foreach($matcheSummary['data']['team'][$i]['players'] as $playerKey => $playerVal)
                    {
                        $dbPlayerInfo = $this->player_model->getPlayerByPlayerId($playerVal['pid']);
                        if(empty($dbPlayerInfo))
                        {
                            $playerInfo = $this->setting_model->getApiData('playerStats','pid='.$playerVal['pid']);
                            if(empty($playerInfo['error']))
                            {
                                $this->player_model->addPlayer($playerInfo,$playerVal['pid']);
                            }
                        }
                    }
                }                
            }
            else
            {
                $this->session->set_flashdata('error', 'Sorry! No data available, Please try again after some time.');
            }
            
        }catch (Exception $e) {
            $this->session->set_flashdata('error', 'Sorry! No data available, Please try again after some time.');
        }
    }
    
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : Change Active
    **/
    public function updateIsActive()
    {
        $postData = $this->input->post();
        if(!empty($postData))
        {
            if(is_array($postData['id']))
            {
                foreach($postData['id'] as $key => $val)
                {
                    $updateData = array(               
                        'isActive' => $postData['isActive'],
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->matches_model->update($updateData,$val);
                }
            }
            else
            {
                $updateData = array(               
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->matches_model->update($updateData,$postData['id']);
            }
        }
    }
    
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : remove match
    **/
    public function remove()
    {
        $postData=$this->input->post();
        if(!empty($postData))
        {
            if(is_array($postData['id']))
            {
                foreach($postData['id'] as $key => $val)
                {
                    $this->matches_model->remove($val);
                }
            }
            else
            {
                $this->matches_model->remove($postData['id']);
            }
        }
    }
    
    /**
     * Date : 04-03-2017
     * Created By : Ajay
     * Description : User Update match
    **/
    public function update($id)
    {
        $match = $this->matches_model->getMatchByMatchId($id);
        if(!empty($match))
        {
            $postData = $this->input->post();
            if(!empty($postData))
            {
                $updateData = array(
                    'sortName1' => $postData['sortName1'],
                    'sortName2' => $postData['sortName2'],
                    'matchType' => $postData['matchType'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->matches_model->update($updateData,$match[0]->id);
                $this->session->set_flashdata('success', 'Match updated successfully.');
                redirect('panel/matches/update/'.$id);
            }
            
            $this->data['breadcrumb'] = array(
                '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
                'Matches' => base_url().'panel/matches',
                'Update' => ''
            );

            $matchSquads = $this->matches_model->getMatchSquads($id);
            
            $this->data['match'] = $match[0];
            $this->data['matchSquads'] = $matchSquads;
            
            $this->data['pageTitle'] = 'Match Management';
            $this->data['pageTitleSubHeading'] = 'Manage all match';

            $this->template->write('pagetitle', 'Match Management');        
            $this->template->write('bodyClass', 'load1');
            $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
            $this->template->write_view('css', 'panel/matches/update_css', $this->data , true);
            $this->template->write_view('content', 'panel/matches/update', $this->data , true);
            $this->template->write_view('js', 'panel/matches/update_js', $this->data , true);
            $this->template->write_view('subview', 'panel/matches/subview', $this->data , true);
            $this->template->render();
        }
        else
        {
            $this->session->set_flashdata('error', 'Match not found.');
            redirect('panel/matches');
        }
    }
    
    /**
     * Date : 06-03-2017
     * Created By : Ajay
     * Description : Profile page for match
    **/
    public function profile($id)
    {
        $match = $this->matches_model->getMatchByMatchId($id);
        if(!empty($match))
        {
            $this->data['breadcrumb'] = array(
                '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
                'Matches' => base_url().'panel/matches',
                'Update' => ''
            );

            $matchSquads = $this->matches_model->getMatchSquads($id);
            
            $this->data['match'] = $match[0];
            $this->data['matchSquads'] = $matchSquads;
            
            $this->data['pageTitle'] = 'Match Management';
            $this->data['pageTitleSubHeading'] = 'Manage all match';

            $this->template->write('pagetitle', 'Match Management');        
            $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
            $this->template->write_view('css', 'panel/matches/profile_css', $this->data , true);
            $this->template->write_view('content', 'panel/matches/profile', $this->data , true);
            $this->template->write_view('js', 'panel/matches/profile_js', $this->data , true);
            $this->template->write_view('subview', 'panel/matches/subview', $this->data , true);
            $this->template->render();
        }
        else
        {
            $this->session->set_flashdata('error', 'Match not found.');
            redirect('panel/matches');
        }
    } 
    
    /**
     * Date : 06-03-2016
     * Created By : Ajay
     * Description : Logic for refetch match info in to database
    **/
    public function reFetchMatch()
    {
        try
        {
            $matchId = $this->input->post('id');

            $matcheSummary = $this->setting_model->getApiData('fantasySummary','unique_id='.$matchId);
            $matcheInfo = $this->setting_model->getApiData('cricketScore','unique_id='.$matchId);
            
            if (empty($matcheSummary['error']) && empty($matcheInfo['error']))
            {
                                
                for($i=0;$i<2;$i++){
                    foreach($matcheSummary['data']['team'][$i]['players'] as $playerKey => $playerVal)
                    {
                        $dbPlayerInfo = $this->matches_model->getPlayerByMatchId($matchId,$playerVal['pid']);
                        if(empty($dbPlayerInfo))
                        {
                            $insertMatchSquads = array(
                                'crickMatchId' => $matchId,
                                'crickPlayerId' => $playerVal['pid'],
                                'updatedAt' => date('Y-m-d H:i:s'),
                                'createdAt' => date('Y-m-d H:i:s')
                            );
                            $this->db->insert('matchsquads',$insertMatchSquads);
                        }
                    }
                }
                
                for($i=0;$i<2;$i++){
                    foreach($matcheSummary['data']['team'][$i]['players'] as $playerKey => $playerVal)
                    {
                        $dbPlayerInfo = $this->player_model->getPlayerByPlayerId($playerVal['pid']);
                        if(empty($dbPlayerInfo))
                        {
                            $playerInfo = $this->setting_model->getApiData('playerStats','pid='.$playerVal['pid']);
                            if(empty($playerInfo['error']))
                            {
                                $this->player_model->addPlayer($playerInfo,$playerVal['pid']);
                            }
                        }
                    }
                }
                
                $this->session->set_flashdata('success', 'Refetch match data successfully.');
            }
            else
            {
                $this->session->set_flashdata('error', 'Sorry! No data available, Please try again after some time.');
            }
            
        }catch (Exception $e) {
            $this->session->set_flashdata('error', 'Sorry! No data available, Please try again after some time.');
        }
    }
    
    /**
     * Date : 03-03-2017
     * Created By : Ajay
     * Description : remove match
    **/
    public function close()
    {
        $postData=$this->input->post();
       
        if(!empty($postData))
        {    
            $match = $this->matches_model->getMatch('',$postData['id']);
            
            if(!empty($match))
            {
                $this->db->select('customerjoinleague.*');
                $this->db->from('customerjoinleague');
                $this->db->join('league','customerjoinleague.leagueId = league.id AND league.isActive = 1');
                $this->db->where('matchId',$match[0]->crickMatchId);
                $this->db->group_by('customerjoinleague.leagueId');
                $query = $this->db->get();
                $joinLeague = $query->result();
              
                foreach($joinLeague as $jKey => $jVal)
                {
                    $myRankArray = $this->point_model->getRank1($jVal->matchId,$jVal->leagueId);
                    
                    $leagueWinner = $this->league_model->getLeagueWinner($jVal->leagueId);
                    foreach($leagueWinner as $lKey => $lVal)
                    {
                        $winner = explode('-', $lVal->rank);
                        
                        foreach ($myRankArray as $mrKey => $mrVal)
                        {
                            foreach($mrVal as $dKey => $dVal)
                            {
                                $leagueInfo = $this->league_model->getLeagueById($jVal->leagueId);
                                
                                if(count($winner) == 1)
                                {
                                    if($dVal['rank'] == $winner[0])
                                    {
                                        if(!empty($lVal->prize))
                                        {
                                            $insertData = array(
                                                'customerId' => $mrKey,
                                                'amount' => $lVal->prize,
                                                'actionType' => 'Credit',
                                                'type' => 'Winning',
                                                'comments' => 'Won a "'.$leagueInfo[0]->title.'" league for "'.$match[0]->title.'"',
                                                'caseId' => $match[0]->crickMatchId.$mrKey,
                                                'customerJoinLeagueId' => $lVal->id,
                                                'createdAt' => date('Y-m-d H:i:s')
                                            );
                                            
                                            $this->db->insert('wallet',$insertData);
                                        }
                                    }
                                }else{
                                    if($winner[0] <= $dVal['rank'] && $dVal['rank'] >= $winner[1])
                                    {
                                        if(!empty($lVal->prize))
                                        {
                                            $insertData = array(
                                                'customerId' => $mrKey,
                                                'amount' => $lVal->prize,
                                                'actionType' => 'Credit',
                                                'type' => 'Winning',
                                                'comments' => 'Won a "'.$leagueInfo[0]->title.'" league for "'.$match[0]->title.'"',
                                                'caseId' => $match[0]->crickMatchId.$mrKey,                                                
                                                'customerJoinLeagueId' => $lVal->id,
                                                'createdAt' => date('Y-m-d H:i:s')
                                            );
                                           
                                            $this->db->insert('wallet',$insertData);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }                                
            }
            
            $closeUpdate = array(
                'matchClose' => 1,
                'updatedAt' => date('Y-m-d H:i:s')
            );
            $this->matches_model->update($closeUpdate,$postData['id']);            
        }
    }
    
    /**
     * Date : 03-04-2017
     * Created By : Ajay
     * Description : Update country logo
    **/
    public function update_logo($id)
    {
        $match = $this->matches_model->getMatchByMatchId($id);
        $postData = $this->input->post();
        if(!empty($postData))
        {
            if(!empty($match))
            {                
                if (!file_exists('./assets/user_files/player/' . trim($postData['matchName']))) {
                    mkdir('./assets/user_files/player/' . trim($postData['matchName']));
                }
                        
                $config['upload_path'] = './assets/user_files/player/'.  trim($postData['matchName']);
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_name'] = trim($postData['matchName']).'.png';
                $config['remove_spaces'] = FALSE;
                $config['overwrite'] = TRUE;
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload('profileImage'))
                {
                    $error = $this->upload->display_errors();                    
                    $this->session->set_flashdata('error', $error);
                }
                else
                {
                    $this->session->set_flashdata('Success', 'Country logo successully updated.');
                }

                redirect('panel/matches/update/'.$id);
            }
            else
            {
                $this->session->set_flashdata('error', 'Match not found.');
                redirect('panel/matches');
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Somthing went wrong.');
            redirect('panel/matches');
        }
    } 
    
}
