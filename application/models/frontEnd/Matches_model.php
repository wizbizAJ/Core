<?php

class Matches_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getMatchForHeader($postData) {
        $this->db->select('*');
        $this->db->from('match');

        if (!empty($postData['type'])) {
            $this->db->where('matchType', $postData['type']);
        }

        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        $this->db->where('startDateTime >', $date);
        if (!empty($postData['endDateTime'])) {
            $this->db->where("DATE_FORMAT(CONVERT_TZ(startDateTime,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') > ", $postData['endDateTime']);
        }
        if (!empty($postData['type'])) {
            $this->db->where('matchType', $postData['type']);
        }
        if (!empty($postData['limit'])) {
            $this->db->limit($postData['limit']);
        }
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        return $query->result();
    }

    function getMatchByMatchId($id = NULL) {
        $this->db->select('*');
        $this->db->from('match');

        if (!empty($id)) {
            $this->db->where('crickMatchId', $id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function getMatchSquads($id) {
        $this->db->select('p.*');
        $this->db->from('matchsquads m');
        $this->db->join('player p', 'm.crickPlayerId = p.crickPlayerId');
        $this->db->where('crickMatchId', $id);
        $this->db->order_by('id', 'RANDOM');
        $query = $this->db->get();
        return $query->result();
    }

    function getMatchSquadsWithCredit($id) {
        $this->db->select('p.*,m.teamName');
        $this->db->from('matchsquads m');
        $this->db->join('player p', 'm.crickPlayerId = p.crickPlayerId AND p.credits > 0');
        $this->db->where('crickMatchId', $id);
        $this->db->order_by('id', 'RANDOM');
        $query = $this->db->get();
        return $query->result();
    }

}

?>
