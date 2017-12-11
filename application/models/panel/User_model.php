<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function add($postData) {
        $config['upload_path'] = './assets/user_files/profile';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->upload->initialize($config);
        $file_name = NULL;
        if ($this->upload->do_upload('logo')) {
            $fileData = $this->upload->data();
            $file_name = $fileData['file_name'];
        }

        $adminAdd = array(
            'name' => $postData['name'],
            'username' => $postData['username'],
            'email' => $postData['email'],
            'mobile' => $postData['mobile'],
            'password' => md5($postData['password']),
            'roleId' => $postData['role'],
            'profileImage' => $file_name,
            'isActive' => $postData['isActive'],
            'updatedAt' => date('Y-m-d H:i:s'),
            'createdAt' => date('Y-m-d H:i:s')
        );

        $this->db->insert('admin', $adminAdd);
    }

    function update($postData, $slug) {
        if (!empty($postData['password'])) {
            $updatePassword = array(
                'password' => md5($postData['password']),
            );
            $this->db->where('id', $postData['userId']);
            $this->db->update('admin', $updatePassword);
        }

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './assets/user_files/profile';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $this->upload->initialize($config);
            if ($this->upload->do_upload('logo')) {
                $fileData = $this->upload->data();
                $file_name = $fileData['file_name'];

                $updateImage = array(
                    'profileImage' => $file_name,
                );
                $this->db->where('id', $postData['userId']);
                $this->db->update('admin', $updateImage);
            }
        }

        $userUpdate = array(
            'username' => $postData['username'],
            'name' => $postData['name'],
            'mobile' => $postData['mobile'],
            'email' => $postData['email'],
            'roleId' => $postData['role'],
            'isActive' => $postData['isActive'],
            'updatedAt' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $postData['userId']);
        $this->db->update('admin', $userUpdate);
    }

    function getAllUsers($search = array(), $sort = array(), $limit = null, $offset = null) {
        $this->db->select('a.*,r.title as roleTitle');
        $this->db->from('admin a');
        $this->db->join('role r', 'r.id = a.roleId');
        $this->db->where("a.id != 1", NULL, FALSE);

        if (!empty($search['freetext'])) {
            $this->db->where("(a.name like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("a.email like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("r.title like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("DATE_FORMAT(CONVERT_TZ(a.updatedAt,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $search['freetext'] . "%')", NULL, FALSE);
        }

        $fields = array('', 'a.name', 'a.email', 'r.name', 'a.updatedAt', '');
        foreach ($search['columntext'] as $key => $value) {
            if (!empty($value)) {
                if ($fields[$key] == 'a.updatedAt') {
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

    function getUser($id = NULL) {
        $this->db->select('a.*,r.title as roleTitle');
        $this->db->from('admin a');
        $this->db->join('role r', 'r.id = a.roleId');
        if (!empty($id)) {
            $this->db->where('a.id', $id);
        }

        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function getRole($id = NULL) {
        $this->db->select('r.*');
        $this->db->from('role r');
        $this->db->where('r.id != 1', NULL, FALSE);
        if (!empty($id)) {
            $this->db->where('r.id', $id);
        }

        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function checkUserName($username, $id = NULL) {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $username);
        if (!empty($id)) {
            $this->db->where('id != ' . $id, NULL, FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();

        if (empty($res)) {
            return 1;
        } else {
            return 0;
        }
    }

    function checkEmail($email, $id = NULL) {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('email', $email);
        if (!empty($id)) {
            $this->db->where('id != ' . $id, NULL, FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();

        if (empty($res)) {
            return 1;
        } else {
            return 0;
        }
    }

    function inactive($id) {
        $updateData = array(
            'isActive' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('admin', $updateData);
    }

    function active($id) {
        $updateData = array(
            'isActive' => 1
        );
        $this->db->where('id', $id);
        $this->db->update('admin', $updateData);
    }

    function updateStatus($id, $status) {
        $updateData = array(
            'isActive' => $status
        );
        $this->db->where('id', $id);
        $this->db->update('admin', $updateData);
    }

    function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('admin');
    }

}

?>
