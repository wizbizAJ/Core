<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forgot_password extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('panel/forgotpassword_model');
    }

    /*
      @Date :
      - 21-03-2017, Created by: Sejal
      @Description
      - When user click on forgot password then this action perform
      - When user send forgot password request then first check this email is in system or not if not then gives error
      - if email is in system then get forgot password template from database and replace all the daynamic content and send link to user's email so after then user can change password and this link is used one time. if once user use this link then after this link expired.
      @param:
      - email => contain email address
     */

    public function index() {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $checkMail = $this->forgotpassword_model->checkMail($postData['email']);


            if (!empty($checkMail)) {
                
                  $getEmailContentBySlug = $this->setting_model->getEmailContentBySlug('reset_your_password');                 
                  $subject = $getEmailContentBySlug[0]->subject;
                  $to = array($postData['email']);
                  $cc = array();
                  $bcc = array();
                  $resetPasswordCode = $this->email_model->generate_password();
                  $link = base_url() . 'panel/forgot_password/reset/' . $resetPasswordCode;
                  $message = str_replace("{{UserName}}", $checkMail[0]->userName, $getEmailContentBySlug[0]->message);
                  $message = str_replace("{{link}}", $link, $message);
                  $mailSent = $this->email_model->sendCommonMail($subject, $message, $to, $cc, $bcc);
                  if ($mailSent == 1) { 
                  $this->forgotpassword_model->updateResetCode($postData['email'], $resetPasswordCode);             
                  $this->session->set_flashdata('success', 'Reset password link successfully sent to your email. Please check your email.');
                  redirect('panel/forgot_password');
                  } else {
                  $this->session->set_flashdata('error', 'Oops! Something went wrong.');
                  redirect('panel/forgot_password');
                  } 
            } else {
                $this->session->set_flashdata('error', 'Email "' . $postData['email'] . '" is not register.');
                redirect('panel/forgot_password');
            }
        }

        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['logoTitle'] = $this->setting_model->getSetting('Logo Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');
        $this->data['siteTitle'] = $this->setting_model->getSetting('Site Title');
        
        $this->template->set_template('panelLogin');
        $this->template->write('pagetitle', 'Forgot Password');
        $this->template->write('sitetitle', $this->setting_model->getSetting('Site Title'));
        $this->template->write_view('css', 'panel/forgot_password/css', $this->data, true);
        $this->template->write_view('content', 'panel/forgot_password/index', $this->data, true);
        $this->template->write_view('footer', 'panel/common/admin_footer', $this->data, true);
        $this->template->write_view('js', 'panel/forgot_password/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date :
      - 21-03-2017, Created by: Sejal
      @Description
      - when user click on link which is sent to email then this function is call
      - it is check verification code and it is match then it is display change password page. if not match then redirect to forbidden page
      - user request new password then update password and reset verification code so next time user can not use this link and then after redirect to login page.
      @parma:
      - password => contain new password
      - confirm password => contain password which is enter in password field
     */

    public function reset($code) {
        $postData = $this->input->post();
        if (!empty($postData)) {
            $this->forgotpassword_model->updatePassword($code, $postData['password']);
            $this->session->set_flashdata('success', 'Your password successfully updated.');
            redirect('panel');
        }

        if (empty($code)) {
            redirect('panel/error/forbidden');
        }

        $checkCode = $this->forgotpassword_model->checkCode($code);

        if (empty($checkCode)) {
            redirect('panel/error/forbidden');
        }

        $this->data['logo'] = $this->setting_model->getSetting('Site Logo');
        $this->data['logoTitle'] = $this->setting_model->getSetting('Logo Title');
        $this->data['reservedRight'] = $this->setting_model->getSetting('Reserved Right');

        $this->data['sitetitle'] = $this->setting_model->getSetting('Site Title');
        $this->template->write('pagetitle', 'Forgot Password');
        $this->template->write('sitetitle', $this->setting_model->getSetting('Site Title'));
        $this->template->write_view('css', 'panel/forgot_password/resetPassword_css', $this->data, true);
        $this->template->write_view('content', 'panel/forgot_password/resetPassword', $this->data, true);
        $this->template->write_view('footer', 'panel/common/admin_footer', $this->data, true);
        $this->template->write_view('js', 'panel/forgot_password/resetPassword_js', $this->data, true);
        $this->template->render();
    }

}
