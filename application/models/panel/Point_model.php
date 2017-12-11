<?php

class Point_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAllPoints($search = array(), $sort = array(), $limit = null, $offset = null) {
        $this->db->select('*');
        $this->db->from('points');

        if (!empty($search['freetext'])) {
            $this->db->where("(title like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("matchType like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("point like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("DATE_FORMAT(CONVERT_TZ(updatedAt,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $search['freetext'] . "%')", NULL, FALSE);
        }

        $fields = array('', 'title', 'matchType', 'point', 'isActive', 'updatedAt', '');
        foreach ($search['columntext'] as $key => $value) {
            if (!empty($value) || $value === '0') {
                if ($fields[$key] == 'updatedAt') {
                    $this->db->where("DATE_FORMAT(CONVERT_TZ(" . $fields[$key] . ",'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $value . "%'", NULL, FALSE);
                } else {
                    $this->db->like($fields[$key], $value);
                }
            }
        }

        if (!empty($sort)) {
            foreach ($sort as $index => $type) {
                if (!empty($fields[$index])) {
                    $this->db->order_by($fields[$index], $type);
                }
            }
        }

        if (empty($limit)) {
            $query = $this->db->get();
            return $query->num_rows();
        } else {
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result();
        }
    }

    function getPoint($isActive = NULL, $id = NULL) {
        $this->db->select('*');
        $this->db->from('points');

        if (!empty($isActive)) {
            $this->db->where('isActive', $isActive);
        }

        if (!empty($id)) {
            $this->db->where('id', $id);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function add($insertData) {
        $this->db->insert('points', $insertData);
        $pointId = $this->db->insert_id();
        return $pointId;
    }

    function update($updateData, $id) {
        $this->db->where('id', $id);
        $this->db->update('points', $updateData);
    }

    function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('points');
    }

}

?>
