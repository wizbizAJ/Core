<?php
class Player_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function getPlayerByPlayerId($id = NULL) {
        $this->db->select('*');
        $this->db->from('player');
        if (!empty($id)) {
            $this->db->where('crickPlayerId', $id);
        }

        $query = $this->db->get();
        return $query->result();
    }
    
    function getPlayerInfo($ids)
    {
        $this->db->select('*');
        $this->db->from('player');
        $this->db->where("FIND_IN_SET(crickPlayerId,'".$ids."') !=", 0);
        $query = $this->db->get();
        return $query->result();
    }
}

?>
