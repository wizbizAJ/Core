<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends FrontendController {

    function __construct() {
        $this->data = array();
        parent::__construct($this->data);

        if (empty($this->data['customer'])) {
            redirect('welcome');
        }

        $this->load->model('frontEnd/league_model');
        $this->load->model('frontEnd/team_model');
    }

    /*
      @Date
      - 15-11-2017, Created by: Ajay
      @Description
      - Use for display wallet
    */
    public function index() {
        $customer = $this->data['customer'];

        $this->data['totalUnutilized'] = $this->wallet_model->getTotalUnutilizedByCustomer($customer->id);
        $this->data['totalWin'] = $this->wallet_model->getTotalWinByCustomer($customer->id);
        $this->data['totalBalance'] = $this->wallet_model->getTotalBalanceByCustomer($customer->id);

        $this->data['wallet'] = $this->wallet_model->getSummeryByCustomer($customer->id);
        $this->data['pancardbankDetails'] = $this->wallet_model->checkPanCardBankDetails($customer->id);
        $this->data['customer'] = $customer;

        // Meta Details
        $this->template->write('keywords', 'crickSkill');
        $this->template->write('description', 'crickSkill');
        $this->template->write('metatag', 'crickSkill');
        // Meta Details

        $title = 'Wallet - ' . $this->data['sitetitle'];
        $this->data['pagetitle'] = $title;
        $this->template->write('pagetitle', $title);
        $this->template->write_view('css', 'frontEnd/wallet/css', $this->data, true);
        $this->template->write_view('content', 'frontEnd/wallet/index', $this->data, true);
        $this->template->write_view('js', 'frontEnd/wallet/js', $this->data, true);
        $this->template->render();
    }

    /*
      @Date
      - 16-11-2017, Created by: Ajay
      @Description
      - Use for add Cash
     */
    public function addCash() {
        $customer = $this->data['customer'];
        $postData = $this->input->post();

        $result = array('success' => 0);

        if (!empty($postData['cashAmount'])) {
            $insertData = array(
                'customerId' => $customer->id,
                'amount' => $postData['cashAmount'],
                'actionType' => 'Credit',
                'type' => 'Deposit',
                'createdAt' => date('Y-m-d H:i:s')
            );
            $this->db->insert('wallet', $insertData);
            $result = array('success' => 1);
        }

        echo json_encode($result);
    }

    /*
      @Date
      - 17-11-2017, Created by: Sejal
      @Description
      - Use for add For withdraw amount
    */
    public function addWithdraw($customerId) {

        $postData = $this->input->post();
        $result = array('success' => 0);

        if (!empty($postData['cashAmount'])) {
            $insertData = array(
                'customerId' => $customerId,
                'amount' => $postData['cashAmount'],
                'actionType' => 'Debit',
                'comments' => 'Withdraw',
                'type' => 'Withdraw',
                'status' => 'Pending',
                'createdAt' => date('Y-m-d H:i:s')
            );
            $this->db->insert('wallet', $insertData);
            $result = array('success' => 1);
        }

        echo json_encode($result);
    }

}
