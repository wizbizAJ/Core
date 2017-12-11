<?php

class Role_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function add($postData) {
        $roleAdd = array(
            'title' => $postData['title'],
            'permissionId' => implode(',', $postData['permission']),
            'isActive' => 1,
            'updatedAt' => date('Y-m-d H:i:s'),
            'createdAt' => date('Y-m-d H:i:s')
        );

        $this->db->insert('role', $roleAdd);
    }

    function update($postData, $id) {
        $roleUpdate = array(
            'title' => $postData['title'],
            'permissionId' => implode(',', $postData['permission']),
            'updatedAt' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        $this->db->update('role', $roleUpdate);
    }

    function getAllRoles($search = array(), $sort = array(), $limit = null, $offset = null) {

        $this->db->select('r.*');
        $this->db->from('role r');
        $this->db->where("r.id != 1", NULL, FALSE);

        if (!empty($search['freetext'])) {
            $this->db->where("(r.title like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("DATE_FORMAT(CONVERT_TZ(r.updatedAt,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $search['freetext'] . "%')", NULL, FALSE);
        }

        $fields = array('', 'r.title', 'r.updatedAt', '');
        foreach ($search['columntext'] as $key => $value) {
            if (!empty($value)) {
                if ($fields[$key] == 'r.updatedAt') {
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

    function getRole($id = NULL) {
        $this->db->select('r.*');
        $this->db->from('role r');

        if (!empty($id)) {
            $this->db->where('r.id', $id);
        }

        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function getAllPermission() {
        $this->db->select('*');
        $this->db->from('permissions');
        $this->db->order_by('permissionGroup', 'ASC');
        $this->db->order_by('title', 'ASC');
        $query = $this->db->get();
        $res = $query->result();

        $finalArray = array();
        foreach ($res as $key => $value) {
            $finalArray[$value->permissionGroup][] = $value;
        }

        return $finalArray;
    }

    function checkRole($name, $id = NULL) {
        $this->db->select('r.*');
        $this->db->from('role r');
        $this->db->where('r.title', $name);
        if (!empty($id)) {
            $this->db->where('r.id != ' . $id, NULL, FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function inactive($id) {
        $updateData = array(
            'isActive' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('role', $updateData);
    }

    function active($id) {
        $updateData = array(
            'isActive' => 1
        );
        $this->db->where('id', $id);
        $this->db->update('role', $updateData);
    }

    function updateStatus($id, $status) {
        $updateData = array(
            'isActive' => $status
        );
        $this->db->where('id', $id);
        $this->db->update('role', $updateData);
    }

    function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('role');
    }

}

?>
