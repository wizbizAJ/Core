<?php

class Profile_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function checkUsername($id, $postData) {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('id != ' . $id, null, false);
        $this->db->where('userName', $postData['userName']);
        $query = $this->db->get();
        $res = $query->result();

        if (empty($res)) {
            return 1;
        } else {
            return 0;
        }
    }

    function checkEmail($id, $postData) {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('id != ' . $id, null, false);
        $this->db->where('email', $postData['email']);
        $query = $this->db->get();
        $res = $query->result();

        if (empty($res)) {
            return 1;
        } else {
            return 0;
        }
    }

    function updateProfileImage($id, $postData) {
        $config['upload_path'] = './assets/user_files/profile';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('profileImage')) {
            $error = $this->upload->display_errors();
            $returnData = array(
                'success' => 0,
                'message' => $error,
            );
        } else {
            $fileData = $this->upload->data();
            $file_name = $fileData['file_name'];

            $data = array(
                'profileImage' => $fileData['file_name'],
                'updatedAt' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $id);
            $this->db->update('admin', $data);

            $returnData = array(
                'success' => 1
            );

            $userDetails = $this->getUser($id);
            $sessionData['panel'] = $userDetails;
            $this->session->set_userdata($sessionData);
        }
        return $returnData;
    }

    function updateProfile($id, $postData) {
        $data = array(
            'userName' => $postData['userName'],
            'email' => $postData['email'],
            'updatedAt' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        $this->db->update('admin', $data);

        $userDetails = $this->getUser($id);
        $sessionData['panel'] = $userDetails;
        $this->session->set_userdata($sessionData);
    }

    function getUser($id) {
        $this->db->select('a.id,a.userName,a.email,a.roleId,a.profileImage,r.title as role,rp.permissionId,GROUP_CONCAT(DISTINCT p.permissionGroup) as permissionGroup,a.createdAt,a.updatedAt,');
        $this->db->from('admin a');
        $this->db->join('role r', 'r.id = a.roleId');
        $this->db->join('role_permission rp', 'rp.roleId = r.id');
        $this->db->join('permissions p', '`FIND_IN_SET`(`p`.`id`,`rp`.`permissionId`) > 0');
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        $res = $query->result();
        return $res[0];
    }

    function updatePassword($id, $postData) {
        $data = array(
            'password' => md5($postData['password']),
            'updatedAt' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        $this->db->update('admin', $data);
    }

}

?>
