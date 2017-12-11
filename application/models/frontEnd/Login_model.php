<?php

class Login_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function checkCustomer($email, $password) {
        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('email', $email);
        $this->db->where('password', md5($password));
        $this->db->where('isActive', 1);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function getCustomer($id) {
        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('isActive', 1);
        $this->db->where('id', $id);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    function getCustomerByEmail($email) {
        $this->db->select('*');
        $this->db->from('customer');
        $this->db->where('isActive', 1);
        $this->db->where('email', $email);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

}

?>
