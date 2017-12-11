<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Selection_criteria extends Panel_Controller {
    
    function __construct() {
        $this->data=array();
        parent::__construct($this->data);
        $this->load->model('panel/selection_criteria_model');
         $permission = $this->data['permission'];
        if (!in_array(3, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 23-11-2016
     * Created By : Ajay
     * Description : User for Change Selection criteria
    **/
    public function index()
    {
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'Settings' => '',
            'Selection Criteria' => ''
        );
        
        $selectionCriteria = $this->selection_criteria_model->getSelectionCriteria();
        $this->data['selectionCriteria'] = $selectionCriteria;        
        
        $this->data['pageTitle'] = 'Selection Criteria';
        $this->data['pageTitleSubHeading'] = 'Max & Min Selection';
        
        
        $this->template->write('pagetitle', 'Selection Criteria');        
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/selectionCriteria/css', $this->data , true);
        $this->template->write_view('content', 'panel/selectionCriteria/index', $this->data , true);
        $this->template->write_view('js', 'panel/selectionCriteria/js', $this->data , true);
        $this->template->write_view('subview', 'panel/selectionCriteria/subview', $this->data , true);
        $this->template->render();
    }
    
    /**
     * Date : 23-11-2016
     * Created By : Ajay
     * Description : User Update Points
    **/
    public function update()
    {
        $postData = $this->input->post();
        if(!empty($postData))
        {
            $selectionSwitch=empty($postData['selectionSwitch'])?0:1;
            
            if($selectionSwitch==1)
            {
                for($i=1;$i<=5;$i++)
                {
                    $updateData = array(
                        'minimumSelection' => 0,
                        'maximumSelection' => 0,
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->selection_criteria_model->update($updateData,$i);
                }
                $this->session->set_flashdata('success', 'Selection criteria updated successfully.');
            }
            else
            {
                $totalPlay = $postData['min_5']+$postData['max_5'];
                if($totalPlay == 11)
                {
                    for($i=1;$i<=5;$i++)
                    {
                        $updateData = array(
                            'minimumSelection' => $postData['min_'.$i],
                            'maximumSelection' => $postData['max_'.$i],
                            'updatedAt' => date('Y-m-d H:i:s')
                        );
                        $this->selection_criteria_model->update($updateData,$i);
                    }
                    $this->session->set_flashdata('success', 'Selection criteria updated successfully.');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Sum of the Players from one team field must be 11.');
                }
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Something went wrong.');
        }
    }
    
    /**
     * Date : 23-11-2016
     * Created By : Ajay
     * Description : Update Logic Points
    **/
    public function updatePoint($id)
    {
        $points = $this->point_model->getPoint(NULL,$id);
        if(!empty($points))
        {
            $postData=$this->input->post();
            if(!empty($postData))
            {            
                $updateData = array(
                    'title' => $postData['title'],
                    'pointCategory' => $postData['pointCategory'],
                    'matchType' => $postData['matchType'],
                    'point' => $postData['point'],
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );            
                $this->point_model->update($updateData,$id);
                
                $this->session->set_flashdata('success', 'Point updated successfully.');
            }
            else
            {
                $this->session->set_flashdata('error', 'Something went wrong.');
            }
        }
        else
        {
            $this->session->set_flashdata('error', 'Point not found.');
        }
    }
    
    /**
     * Date : 23-11-2016
     * Created By : Ajay
     * Description : remove Point
    **/
    public function remove()
    {
        $postData=$this->input->post();
        if(!empty($postData))
        {
            $points = $this->point_model->getPoint(NULL,$postData['id']);
            if(!empty($points))
            {
                $this->point_model->remove($postData['id']);
            }
        }
    }
}
