<?php
class Valid_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }
    
    function checkUserName($userName,$id=NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('userName',$userName);
        if(!empty($id))
        {
            $this->db->where('id != '.$id,NULL,FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();

        if(empty($res))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    function checkEmail($email,$id=NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email',$email);
        if(!empty($id))
        {
            $this->db->where('id != '.$id,NULL,FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();

        if(empty($res))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    function checkMobile($mobile,$id=NULL)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('mobile',$mobile);
        if(!empty($id))
        {
            $this->db->where('id != '.$id,NULL,FALSE);
        }
        $query = $this->db->get();
        $res = $query->result();

        if(empty($res))
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
?>
