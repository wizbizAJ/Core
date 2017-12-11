<?php

class Selection_criteria_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function getSelectionCriteria($id=NULL)
    {
        $this->db->select('*');
        $this->db->from('selectioncriteria');
                
        if(!empty($id))
        {
            $this->db->where('id',$id);
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function update($updateData,$id)
    {
        $this->db->where('id',$id);
        $this->db->update('selectioncriteria',$updateData);
    }   
}

?>
