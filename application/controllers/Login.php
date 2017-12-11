<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);
        $this->load->model('frontEnd/login_model');
    }

    /*
      @Date
      - 20-10-2017, Created by: Ajay
      @Description
      - Use for login
     */

    public function index() {
        $infoArray = array();
        $postData = $this->input->post();
        if (!empty($postData)) {
            $email = $postData['loginEmail'];
            $password = $postData['loginPassword'];

            $checkCustomer = $this->login_model->checkCustomer($email, $password);
            if (!empty($checkCustomer)) {
                $sessionData['crickCustomer'] = $checkCustomer[0];
                $this->session->set_userdata($sessionData);
                $infoArray = array(
                    'status' => 1,
                    'message' => 'Login Successfull.'
                );
            } else {
                $infoArray = array(
                    'status' => 0,
                    'message' => 'Email or password not match'
                );
            }
        } else {
            $infoArray = array(
                'status' => 0,
                'message' => 'Email and password missing'
            );
        }

        echo json_encode($infoArray);
    }

    /*
      @Date
      - 20-10-2017, Created by: Ajay
      @Description
      - Use for Check email is used or not
     */
    public function checkEmail() {
        $email = $this->input->post('registerEmail');
        if (!empty($email)) {
            $checkEmail = $this->register_model->checkEmail($email);
            if ($checkEmail == 1) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            echo "true";
        }
    }

}
