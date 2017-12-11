<?php
class Team_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function getCustomerTeam($customerId,$matchId,$dbId=NULL) {
        $this->db->select('*');
        $this->db->from('customerteam');
        $this->db->where('crickMatchId', $matchId);
        $this->db->where('customerId', $customerId);
        
        if(!empty($dbId))
        {
            $this->db->where('id', $dbId);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function getUnselectedCustomerTeam($customerId,$matchId,$leagueId) {
        $this->db->select('c.*');
        $this->db->from('customerteam c');
        $this->db->where('c.crickMatchId', $matchId);
        $this->db->where('c.customerId', $customerId);
        $this->db->where('c.id NOT IN (select customerTeamId from customerjoinleague where matchId='.$matchId.' AND customerId='.$customerId.' AND leagueId='.$leagueId.')', NULL, FALSE);       
        $query = $this->db->get();
        return $query->result();
    }
    
    function getCustomerPreviousTeam($customerId,$matchId,$dbId) {
        $this->db->select('*');
        $this->db->from('customerteam');
        $this->db->where('crickMatchId', $matchId);
        $this->db->where('customerId', $customerId);
        
        if(!empty($dbId))
        {
            $this->db->where('id <', $dbId);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function getCustomerTeamById($teamId) {
        $this->db->select('ctp.id as cId, ctp.customerTeamId, p.*,m.teamName');
        $this->db->from('customerteamplayer ctp');
        $this->db->join('player p','p.crickPlayerId = ctp.crickPlayerId');
        $this->db->join('customerteam ct','ct.id = ctp.customerTeamId');
        $this->db->join('matchsquads m','m.crickMatchId = ct.crickMatchId AND m.crickPlayerId = ctp.crickPlayerId');        
        $this->db->where('customerTeamId', $teamId);
        $query = $this->db->get();
        return $query->result();
    }
    
}

?>
