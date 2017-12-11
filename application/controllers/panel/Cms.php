<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Panel_Controller {
    
    function __construct() {
        $this->data=array();
        
        parent::__construct($this->data);
        $this->load->model('panel/cms_model'); 
         $permission = $this->data['permission'];
        if (!in_array(8, $permission)) {
            redirect('panel/error/forbidden');
        }
    }

    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for CMS listing page
    **/
    public function index()
    {          
         
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'CMS' => ''
        );

        $this->data['pageTitle'] = 'CMS Management';
        
        $this->template->write('pagetitle', $this->data['pageTitle']);
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/cms/css', $this->data , true);
        $this->template->write_view('content', 'panel/cms/index', $this->data , true);       
        $this->template->write_view('js', 'panel/cms/js', $this->data , true);
        $this->template->render();
    }
    /**
     * Date : 04-03-2017
     * Created By : Sejal
     * Description : User for cms listing page ajax request
     * */
    public function ajaxCms() {
        $limit = (int) $this->input->get('iDisplayLength');
        $offset = (int) $this->input->get('iDisplayStart');

        $search_contents['freetext'] = (string) $this->input->get('sSearch');

        for ($i = 0; $i < $this->input->get('iColumns'); $i++) {
            $search_contents['columntext'][] = (string) $this->input->get('sSearch_' . $i);
        }

        if ($this->input->get('iSortCol_0') != '') {
            for ($i = 0; $i < $this->input->get('iSortingCols'); $i++) {
                $sortcol = $this->input->get('iSortCol_' . $i);
                $sort[$sortcol] = $this->input->get('sSortDir_' . $i);
            }
        } else {
            $sort = null;
        }

        $cms_count = $this->cms_model->getAllCms($search_contents);
        $cms = $this->cms_model->getAllCms($search_contents, $sort, $limit, $offset);

        $rows = array();

        $iDisplayStart = $this->input->get('iDisplayStart');

        foreach ($cms as $key => $cms) {

            $editLinkIcon = '<a href="' . base_url() . 'panel/cms/update/' . $cms->id . '" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>';
            $editLinkText = '<li><a href="' . base_url() . 'panel/cms/update/' . $cms->id . '"> Edit </a></li>';

            if ($cms->isActive == 1) {
                $isActive = '<a href="javascript:void(0)" id="' . $cms->id . '" class="btn btn-transparent btn-xs isActive"  data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $isActive = '<a href="javascript:void(0)" id="' . $cms->id . '" class="btn btn-transparent btn-xs inActive"  data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }
           
            $rows[] = array(
               '<div class="checkbox clip-check check-purple">
                    <input type="checkbox" class="hasAll" id="checkbox_' . $content->id . '" value="' . $content->id . '">
                    <label for="checkbox_' . $content->id . '">&nbsp;</label>
                </div>',
                $content->title,
                $isActive,
                $this->setting_model->converTimeZone('d/m/Y H:i:s',$content->updatedAt),
                '<div class="visible-md visible-lg hidden-sm hidden-xs">
                    <a href="'.base_url().'panel/cms/update/'.$content->slug.'" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>                   
                </div>
                <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group dropdown ">
                            <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                    <li>
                                            <a href="'.base_url().'panel/cms/update/'.$content->slug.'"> Edit </a>
                                    </li>                                   
                            </ul>
                    </div>
                </div>'
            );
        }

        $json = array(
            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => $cms_count,
            'iTotalDisplayRecords' => $cms_count,
            'aaData' => $rows
        );

        echo json_encode($json);
    }
    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for CMS listing page ajax request
    **/
    public function ajaxClasses()
    {       
        
        $limit = (int) $this->input->get('iDisplayLength');
        $offset = (int) $this->input->get('iDisplayStart');

        $search_contents['freetext'] = (string) $this->input->get('sSearch');

        for($i=0; $i<$this->input->get('iColumns'); $i++)
        {
        	$search_contents['columntext'][] = (string) $this->input->get('sSearch_'.$i);
        }
        
        if ($this->input->get('iSortCol_0') != '') {
            for ($i = 0; $i < $this->input->get('iSortingCols'); $i++) {
                $sortcol = $this->input->get('iSortCol_' . $i);
                $sort[$sortcol] = $this->input->get('sSortDir_' . $i);
            }
        } else
            $sort = null;

        
        $contents_count = $this->cms_model->getAllCms($search_contents);
        $contents = $this->cms_model->getAllCms($search_contents, $sort, $limit, $offset);

        $rows = array();

        $iDisplayStart = $this->input->get('iDisplayStart');

        foreach ($contents as $key => $content) {

            if ($content->isActive == 1) {
                $isActive = '<a href="javascript:void(0)" id="' . $content->id . '" class="btn btn-transparent btn-xs isActive"  data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-check-circle-o text-success"></i></a>';
            } else {
                $isActive = '<a href="javascript:void(0)" id="' . $content->id . '" class="btn btn-transparent btn-xs inActive"  data-toggle="tooltip" data-placement="top" title="Inactive"><i class="fa fa-check-circle-o text-danger"></i></a>';
            }
            
            $rows[] = array(
                '<div class="checkbox clip-check check-purple">
                    <input type="checkbox" class="hasAll" id="checkbox_' . $content->id . '" value="' . $content->id . '">
                    <label for="checkbox_' . $content->id . '">&nbsp;</label>
                </div>',
                $content->title,
                $isActive,
                $this->setting_model->converTimeZone('d/m/Y H:i:s',$content->updatedAt),
                '<div class="visible-md visible-lg hidden-sm hidden-xs">
                    <a href="'.base_url().'panel/cms/update/'.$content->slug.'" class="btn btn-transparent btn-xs"  data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-pencil"></i></a>                   
                </div>
                <div class="visible-xs visible-sm hidden-md hidden-lg">
                    <div class="btn-group dropdown ">
                            <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                    <li>
                                            <a href="'.base_url().'panel/cms/update/'.$content->slug.'"> Edit </a>
                                    </li>                                   
                            </ul>
                    </div>
                </div>',
            );
        }

        $json = array(
            'sEcho' => intval($this->input->get('sEcho')),
            'iTotalRecords' => $contents_count,
            'iTotalDisplayRecords' => $contents_count,
            'aaData' => $rows
        );

        echo json_encode($json);
    }
    
    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for display add new CMS form
    **/
    public function add()
    {  
        
        $postData = $this->input->post();
        if(!empty($postData))
        {
            $this->cms_model->add($postData);
            $this->session->set_flashdata('success', 'CMS added successfully.');
            redirect('panel/cms');
        }
                
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'CMS' => base_url().'panel/cms',
            'Add' => ''
        );
        
        $this->data['pageTitle'] = 'Add CMS';
        
        $this->template->write('pagetitle', $this->data['pageTitle']);
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/cms/add_css', $this->data , true);
        $this->template->write_view('content', 'panel/cms/add', $this->data , true);       
        $this->template->write_view('js', 'panel/cms/add_js', $this->data , true);
        $this->template->render();
    }
        
    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for update cms form
    **/
    public function update($slug)
    {
        $cms = $this->cms_model->getCms($slug);

        if(empty($cms))
        {
            redirect('panel/error/notFound');
        }

        $postData = $this->input->post();
        if(!empty($postData))
        {
            $postData['cmsId'] = $cms[0]->id;
            $this->cms_model->update($postData,$slug);
            $this->session->set_flashdata('success', 'CMS info updated successfully.');
            redirect('panel/cms/update/'.$slug);
        }
        
        $this->data['cms'] = $cms[0];
                
        $this->data['breadcrumb'] = array(
            '<i class="fa fa-home margin-right-5 text-large text-dark"></i>Home' => base_url().'panel/welcome',
            'CMS' => base_url().'panel/cms',
            'Update' => ''
        );
        
        

        $this->data['pageTitle'] = 'Update CMS';
        
        $this->template->write('pagetitle', $this->data['pageTitle']);
        $this->template->write_view('breadcrumb', 'panel/common/breadcrumb', $this->data , true);
        $this->template->write_view('css', 'panel/cms/update_css', $this->data , true);
        $this->template->write_view('content', 'panel/cms/update', $this->data , true);        
        $this->template->write_view('js', 'panel/cms/update_js', $this->data , true);
        $this->template->render();
    }
    
    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for check slug for CMS
    **/
    public function checkSlug()
    {
        $slug = $this->input->post('slug');
        $slug = $this->cms_model->generateSlug($slug, 0);
        echo $slug;
    }
    
    /**
     * Date : 17-10-2016
     * Created By : Sejal
     * Description : User for check slug for CMS
    **/
    public function checkSlugValidation()
    {
        $slug = $this->input->post('slug');       
        if(!empty($slug))
        {
            $checkSlug = $this->cms_model->checkSlug($slug);
            if($checkSlug == 1)
            {
                echo 'true';
            }
            else
            {
                echo 'false';
            }
        }
        else
        {
            echo "true";
        }
    }
    
    /**
     * Date : 15-03-2017
     * Created By : Sejal
     * Description : Change Active
     * */
    public function updateIsActive() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            if (is_array($postData['id'])) {
                foreach ($postData['id'] as $key => $val) {
                    $updateData = array(
                        'isActive' => $postData['isActive'],
                        'updatedAt' => date('Y-m-d H:i:s')
                    );
                    $this->cms_model->updateIsActive($updateData, $val);
                }
            } else {
                $updateData = array(
                    'isActive' => $postData['isActive'],
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->cms_model->updateIsActive($updateData, $postData['id']);
            }
        }
    }
}
