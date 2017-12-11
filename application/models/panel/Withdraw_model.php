<?php

class Withdraw_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAllWallets($search = array(), $sort = array(), $limit = null, $offset = null) {
        $this->db->select('w.*,c.name as customerName');
        $this->db->from('wallet w');
        $this->db->join('customer c', 'c.id = w.customerId', 'left');
        $this->db->where("w.status!=", '');
        if (!empty($search['freetext'])) {
            $this->db->where("(c.name like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("w.amount like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("w.status like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("DATE_FORMAT(CONVERT_TZ(w.createdAt,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $search['freetext'] . "%')", NULL, FALSE);
        }

        $fields = array('', 'c.name', 'w.amount', 'w.status', 'w.createdAt', '');
        foreach ($search['columntext'] as $key => $value) {
            if (!empty($value)) {
                if ($fields[$key] == 'w.createdAt') {
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

    function updateStatus($id, $status) {
        $updateData = array(
            'status' => $status
        );
        $this->db->where('id', $id);
        $this->db->update('wallet', $updateData);
    }

    function complete($id) {
        $updateData = array(
            'status' => "Paid"
        );
        $this->db->where('id', $id);
        $this->db->update('wallet', $updateData);
    }

    function pending($id) {
        $updateData = array(
            'status' => "Pending"
        );
        $this->db->where('id', $id);
        $this->db->update('wallet', $updateData);
    }

    function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('wallet');
    }

}

?>
