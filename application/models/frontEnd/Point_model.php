<?php

class Point_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('frontEnd/matches_model');
        $this->load->model('frontEnd/player_model');
    }

    function getPointSummery($customerTeamId) {
        $this->db->select('*');
        $this->db->from('customerteam');
        $this->db->where('id', $customerTeamId);
        $query = $this->db->get();
        $customerMatch = $query->result();

        $result = array();
        if (!empty($customerMatch)) {
            $this->db->select('*');
            $this->db->from('customerteamplayer');
            $this->db->where('customerTeamId', $customerMatch[0]->id);
            $query = $this->db->get();
            $customerMatchTeam = $query->result();
            $total = 0;

            $liveMatchInfo = $this->setting_model->getApiData('fantasySummary', 'unique_id=' . $customerMatch[0]->crickMatchId);

            foreach ($customerMatchTeam as $key => $val) {
                $result[$val->crickPlayerId] = $this->getPointSummeryByPlayerId($val->crickPlayerId, $customerMatch[0]->crickMatchId, $liveMatchInfo, $val->isCaptain, $val->isViceCaptain);
                $total = $total + floatval($result[$val->crickPlayerId]['total']);
            }
        }
        $result['total'] = $total;
        return $result;
    }

    function getScoreCard($leagueId) {
        $this->db->select('ct.*,m.title as matchName,m.matchType,m.startDateTime,l.title as leagueTitle');
        $this->db->from('customerjoinleague cj');
        $this->db->join('customerteam ct', 'ct.id=cj.customerTeamId');
        $this->db->join('match m', 'm.crickMatchId=cj.matchId');
        $this->db->join('league l', 'l.id=cj.leagueId', 'left');
        $this->db->where('cj.id', $leagueId);
        $query = $this->db->get();
        $customerMatch = $query->result();
        $result = array();
        $finalResult = array();

        if (!empty($customerMatch)) {

            $this->db->select('p.*,tp.crickPlayerId,tp.customerTeamId,tp.isCaptain,tp.isViceCaptain');
            $this->db->from('player p');
            $this->db->join('customerteamplayer tp', 'tp.crickPlayerId=p.crickPlayerId');
            $this->db->where('tp.customerTeamId', $customerMatch[0]->id);
            $query = $this->db->get();
            $customerMatchTeam = $query->result();

            $total = 0;

            $liveMatchInfo = $this->setting_model->getApiData('fantasySummary', 'unique_id=' . $customerMatch[0]->crickMatchId);

            foreach ($customerMatchTeam as $key => $val) {

                $other = $this->getPointSummeryByPlayerId2($val->crickPlayerId, $customerMatch[0]->crickMatchId, $liveMatchInfo, $val->isCaptain, $val->isViceCaptain);
                $result[$val->crickPlayerId] = array("name" => $val->name, "details" => $other);
                $total = $total + floatval($other['total']);
            }

            $finalResult[] = array(
                "info" => array(
                    "mathName" => $customerMatch[0]->matchName,
                    "matchType" => $customerMatch[0]->matchType,
                    "startDateTime" => $customerMatch[0]->startDateTime,
                    "leagueName" => $customerMatch[0]->leagueTitle,
                    "total" => $total,
                ),
                "otherInfo" => $result
            );
        }

        return $finalResult;
    }

    function getPointSummeryByPlayerId2($playerId, $matchId, $liveMatchInfo, $isCaptain, $isViceCaptain) {
        $summery = array();
        $totalPoint = 0;

        $dbMatchInfo = $this->matches_model->getMatchByMatchId($matchId);     
        $playerInfo = $this->player_model->getPlayerByPlayerId($playerId);

        if (!empty($dbMatchInfo) && empty($liveMatchInfo['error'])) {
            $matchType = $dbMatchInfo[0]->matchType;

            $summery['run'] = 0;
            $summery['boundary4'] = 0;
            $summery['boundary6'] = 0;
            $summery['duck'] = 0;
            $summery['wicket'] = 0;
            $summery['catch'] = 0;

            //Batting Score Point
            if (!empty($liveMatchInfo['data']['batting'])) {
                foreach ($liveMatchInfo['data']['batting'] as $key => $val) {
                    if (!empty($val['scores'])) {
                        foreach ($val['scores'] as $sKey => $sVal) {
                            if ($sVal['pid'] === $playerId) {
                                //For every run scored

                                $run = $sVal['R'];
                                $runPoint = $this->getPointByName('For every run scored', $matchType);
                                if (!empty($runPoint)) {
                                    //$runs = floatval($run) * floatval($runPoint[0]->point);
                                    $summery['run'] = $summery['run']  + (floatval($run)*floatval($runPoint[0]->point));
                                    $boundary4s = floatval($sVal['4s']);
                                    $boundary6s = floatval($sVal['6s']);
                                    $boundaryPoint = $this->getPointByName('Every boundary hit', $matchType);
                                    if (!empty($boundaryPoint)) {
                                        $summery['boundary4'] = $summery['boundary4'] + (floatval($boundary4s) * floatval($boundaryPoint[0]->point));
                                        $summery['boundary6'] = $summery['boundary6'] + (floatval($boundary6s) * floatval($boundaryPoint[0]->point));
                                    }
                                    if ($playerInfo[0]->playerType != 'Bowler') {
                                        if ($run == 0) {
                                            $duckPoint = $this->getPointByName('Dismissal for duck (batsmen, wicket-keeper and all-rounders)', $matchType);
                                            if (!empty($duckPoint)) {
                                                $summery['duck'] = $summery['duck'] + (floatval($duckPoint[0]->point));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            //Bowling Score Point
            if (!empty($liveMatchInfo['data']['bowling'])) {
                foreach ($liveMatchInfo['data']['bowling'] as $key => $val) {
                    foreach ($val['scores'] as $sKey => $sVal) {
                        if ($sVal['pid'] === $playerId) {
                            //For every Wicket
                            $wicket = $sVal['W'];
                            $wicketPoint = $this->getPointByName('Wicket', $matchType);
                            if (!empty($wicketPoint)) {
                                $summery['wicket'] = $summery['wicket'] + (floatval($wicket) * floatval($wicketPoint[0]->point));
                            }
                        }
                    }
                }
            }
            
            //Fielding Score Point
            if (!empty($liveMatchInfo['data']['fielding'])) {
                                
                foreach ($liveMatchInfo['data']['fielding'] as $key => $val) {
                    foreach ($val['scores'] as $sKey => $sVal) {
                        if ($sVal['pid'] === $playerId) {
                            //For every Catch
                            $catch = $sVal['catch'];
                            $catchPoint = $this->getPointByName('Catch', $matchType);
                            
                            if (!empty($catchPoint)) {
                                $summery['catch'] = $summery['catch'] + (floatval($catch) * floatval($catchPoint[0]->point));                                                                
                            }
                        }
                    }
                }
            }
        }

        if($isCaptain)
        {
            $summery['run'] = $summery['run']*2;
            $summery['boundary4'] = $summery['boundary4']*2;
            $summery['boundary6'] = $summery['boundary6']*2;
            $summery['duck'] = $summery['duck']*2;
            $summery['wicket'] = $summery['wicket']*2;
            $summery['catch'] = $summery['catch']*2;
        }
        
        if($isViceCaptain)
        {
            $summery['run'] = $summery['run']*1.5;
            $summery['boundary4'] = $summery['boundary4']*1.5;
            $summery['boundary6'] = $summery['boundary6']*1.5;
            $summery['duck'] = $summery['duck']*1.5;
            $summery['wicket'] = $summery['wicket']*1.5;
            $summery['catch'] = $summery['catch']*1.5;
        }
        
        $summeryNew['batting'] = array(
                    "Runs" => $summery['run'],
                    "4s" => $summery['boundary4'],
                    "6s" => $summery['boundary6'],
                    "duck" => $summery['duck']
        );
        $summeryNew['bowling'] = array("Wkts" => $summery['wicket']);
        $summeryNew['catch'] = $summery['catch'];
        
        $summeryNew['total'] = floatval(@$summery['run']) + floatval(@$summery['boundary4']) + floatval(@$summery['boundary6']) + floatval(@$summery['duck']) + floatval(@$summery['wicket']) + floatval(@$summery['catch']);       
        
        return $summeryNew;
    }

    function getPointSummeryByPlayerId($playerId, $matchId, $liveMatchInfo, $isCaptain, $isViceCaptain) {
        $summery = array();
        $totalPoint = 0;

        $dbMatchInfo = $this->matches_model->getMatchByMatchId($matchId);
        $playerInfo = $this->player_model->getPlayerByPlayerId($playerId);

        if (!empty($dbMatchInfo) && empty($liveMatchInfo['error'])) {
            $matchType = $dbMatchInfo[0]->matchType;

            $summery['run'] = 0;
            $summery['boundary'] = 0;
            $summery['duck'] = 0;
            $summery['wicket'] = 0;
            $summery['catch'] = 0;

            //Batting Score Point
            if (!empty($liveMatchInfo['data']['batting'])) {
                foreach ($liveMatchInfo['data']['batting'] as $key => $val) {
                    if (!empty($val['scores'])) {
                        foreach ($val['scores'] as $sKey => $sVal) {
                            if ($sVal['pid'] === $playerId) {
                                //For every run scored
                                $run = $sVal['R'];
                                $runPoint = $this->getPointByName('For every run scored', $matchType);
                                if (!empty($runPoint)) {
                                    $summery['run'] = $summery['run'] + (floatval($run) * floatval($runPoint[0]->point));
                                }

                                //Every boundary hit
                                $boundary = floatval($sVal['4s']) + floatval($sVal['6s']);
                                $boundaryPoint = $this->getPointByName('Every boundary hit', $matchType);
                                if (!empty($boundaryPoint)) {
                                    $summery['boundary'] = $summery['boundary'] + (floatval($boundary) * floatval($boundaryPoint[0]->point));
                                }

                                //Dismissal for duck (batsmen, wicket-keeper and all-rounders)
                                if ($playerInfo[0]->playerType != 'Bowler') {
                                    if ($run == 0) {
                                        $duckPoint = $this->getPointByName('Dismissal for duck (batsmen, wicket-keeper and all-rounders)', $matchType);
                                        if (!empty($duckPoint)) {
                                            $summery['duck'] = $summery['duck'] + floatval($duckPoint[0]->point);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //Bowling Score Point
            if (!empty($liveMatchInfo['data']['bowling'])) {
                foreach ($liveMatchInfo['data']['bowling'] as $key => $val) {
                    foreach ($val['scores'] as $sKey => $sVal) {
                        if ($sVal['pid'] === $playerId) {
                            //For every Wicket
                            $wicket = $sVal['W'];
                            $wicketPoint = $this->getPointByName('Wicket', $matchType);
                            if (!empty($wicketPoint)) {
                                $summery['wicket'] = $summery['wicket'] + (floatval($wicket) * floatval($wicketPoint[0]->point));
                                $totalPoint = floatval($totalPoint) + floatval($summery['wicket']);
                            }
                        }
                    }
                }
            }

            //Fielding Score Point
            if (!empty($liveMatchInfo['data']['fielding'])) {
                foreach ($liveMatchInfo['data']['fielding'] as $key => $val) {
                    foreach ($val['scores'] as $sKey => $sVal) {
                        if ($sVal['pid'] === $playerId) {
                            //For every Catch
                            $catch = $sVal['catch'];
                            $catchPoint = $this->getPointByName('Catch', $matchType);
                            if (!empty($catchPoint)) {
                                $summery['catch'] = $summery['catch'] + (floatval($catch) * floatval($catchPoint[0]->point));
                            }
                        }
                    }
                }
            }
        }

        if($isCaptain)
        {
            $summery['run'] = $summery['run']*2;
            $summery['boundary'] = $summery['boundary']*2;
            $summery['duck'] = $summery['duck']*2;
            $summery['wicket'] = $summery['wicket']*2;
            $summery['catch'] = $summery['catch']*2;
        }
        
        if($isViceCaptain)
        {
            $summery['run'] = $summery['run']*1.5;
            $summery['boundary'] = $summery['boundary']*1.5;
            $summery['duck'] = $summery['duck']*1.5;
            $summery['wicket'] = $summery['wicket']*1.5;
            $summery['catch'] = $summery['catch']*1.5;
        }
        
        $summery['total'] = floatval(@$summery['run']) + floatval(@$summery['boundary']) + floatval(@$summery['duck']) + floatval(@$summery['wicket']) + floatval(@$summery['catch']);
        return $summery;
    }

    function getPointByName($name, $matchType) {
        $this->db->select('*');
        $this->db->from('points');

        $this->db->where('title', $name);
        $this->db->where('matchType', $matchType);
        $this->db->where('isActive', 1);

        $query = $this->db->get();
        return $query->result();
    }

    function getRank($matchId, $leagueId) {
        $this->db->select('*');
        $this->db->from('customerjoinleague');
        $this->db->where('matchId', $matchId);
        $this->db->where('leagueId', $leagueId);       
        $query = $this->db->get();
        $joinLeague = $query->result();
       
        $pointSummery = array();
        foreach ($joinLeague as $jKey => $jVal) {
            $summery = $this->getPointSummery($jVal->customerTeamId);
            $pointSummery[$jVal->customerId] = $summery['total'];
        }
        
        arsort($pointSummery);

        $rank = array();
        $val = 1;
        foreach ($pointSummery as $pKey => $pVal) {
            $rank[$pKey] = array(
                'rank' => $val,
                'score' => $pVal
            );
            $val++;
        }

        return $rank;
    }
    
    function getRank1($matchId, $leagueId) {
        $this->db->select('*');
        $this->db->from('customerjoinleague');
        $this->db->where('matchId', $matchId);
        $this->db->where('leagueId', $leagueId);       
        $query = $this->db->get();
        $joinLeague = $query->result();
        
        $pointSummery = array();
        $totalArray = array();
        foreach ($joinLeague as $jKey => $jVal) {
            $summery = $this->getPointSummery($jVal->customerTeamId);
            $pointSummery[][$jVal->customerId][$jVal->customerTeamId] = $summery['total'];
            $totalArray[] = $summery['total'];
        }
        
        rsort($totalArray);
        $rank = array();
        foreach ($pointSummery as $pKey => $pVal) {
            foreach ($pVal as $ppKey => $ppVal) {
                foreach ($ppVal as $pppKey => $pppVal) {
                    
                    $val = array_search($pppVal, $totalArray);
                    
                    $rank[$ppKey][$pppKey] = array(
                        'rank' => ($val+1),
                        'score' => $pppVal
                    );
                }
            }           
        }

        return $rank;
    }

    function getRankByTeam($matchId, $leagueId, $customerTeamId) {
        $this->db->select('*');
        $this->db->from('customerjoinleague');
        $this->db->where('matchId', $matchId);
        $this->db->where('leagueId', $leagueId);
        $this->db->where('customerTeamId', $customerTeamId);
        $query = $this->db->get();
        $joinLeague = $query->result();
        
        $pointSummery = array();
        foreach ($joinLeague as $jKey => $jVal) {
            $summery = $this->getPointSummery($jVal->customerTeamId);
            $pointSummery[$jVal->customerId] = $summery['total'];
        }
        
        arsort($pointSummery);

        $rank = array();
        $val = 1;
        foreach ($pointSummery as $pKey => $pVal) {
            $rank[$pKey] = array(
                'rank' => $val,
                'score' => $pVal
            );
            $val++;
        }

        return $rank;
    }
    
}

?>
