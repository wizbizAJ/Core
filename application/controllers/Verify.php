<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);
        $this->load->model('frontEnd/register_model');
        $this->load->model('panel/customer_model');
    }

    /*
      @Date
      - 10-11-2017, Created by: Ajay
      @Description
      - Use for validate email
    */
    public function check($token) {
        if (!empty($token)) {
            $customer = $this->register_model->getCustomerByToken($token);
            if (!empty($customer)) {
                $updateData = array(
                    'emailVerifyCode' => NULL,
                    'isActive' => 1,
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->register_model->update($updateData, $customer[0]->id);
                $this->session->set_flashdata('registerSuccess', 'Email verified successfully.');
            } else {
                $this->session->set_flashdata('registerError', 'Invalide token.');
            }
        } else {
            $this->session->set_flashdata('registerError', 'Token missing.');
        }
        redirect('welcome');
    }

    /*
      @Date
      - 10-11-2017, Created by: Sejal
      @Description
      - Use for validate email
    */
    public function checkCodeForEmail($token) {
        if (!empty($token)) {
            $customer = $this->register_model->getCustomerByToken($token);
            if (!empty($customer)) {
                $updateData = array(
                    'emailVerifyCode' => NULL,
                    'verifyEmail' => 1,
                    'isActive' => 1,
                    'updatedAt' => date('Y-m-d H:i:s')
                );
                $this->register_model->update($updateData, $customer[0]->id);
                $getCustomer = $this->customer_model->getCustomer($customer[0]->id);
                $sessionData['crickCustomer'] = $getCustomer[0];
                $this->session->set_userdata($sessionData);
                $this->session->set_flashdata('registerSuccess', 'Email verified successfully.');
            } else {
                $this->session->set_flashdata('registerSuccess', 'Invalide token.');
            }
        } else {
            $this->session->set_flashdata('registerError', 'Token missing.');
        }
        redirect('welcome');
    }

}
