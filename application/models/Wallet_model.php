<?php
class Wallet_model extends CI_Model{
function __construct()
    {
        parent::__construct();       
    }
    public function getTotalAmountByCustomer($customerId)
    {
        $this->db->select("SUM(COALESCE(CASE WHEN actionType = 'Credit' THEN amount END,0)) - SUM(COALESCE(CASE WHEN actionType = 'Debit' THEN amount END,0)) balance");
        $this->db->from('wallet');
        $this->db->where('customerId',$customerId);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    public function getSummeryByCustomer($customerId)
    {
        $this->db->select("w.id,w.status,w.customerId,COALESCE(CASE WHEN actionType = 'Credit' THEN amount END,0) credits, COALESCE(CASE WHEN actionType = 'Debit' THEN amount END,0) debits,w.type,w.comments,w.createdAt,l.title as leagueTitle, m.title as matchTitle");
        $this->db->from('wallet w');
        $this->db->join('customerjoinleague cjl','cjl.id = w.customerJoinLeagueId','left');
        $this->db->join('league l','l.id = cjl.leagueId','left');
        $this->db->join('match m','m.crickMatchId = cjl.matchId','left');
        $this->db->where('w.customerId',$customerId);
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    
    public function getTotalUnutilizedByCustomer($customerId)
    {
        $this->db->select("SUM(COALESCE(CASE WHEN actionType = 'Credit' AND type = 'Deposit' THEN amount END,0)) - SUM(COALESCE(CASE WHEN actionType = 'Debit' AND comments != 'Withdraw' THEN amount END,0)) balance");
        $this->db->from('wallet w');
        $this->db->where('w.customerId',$customerId);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    
    public function getTotalWinByCustomer($customerId)
    {
        $this->db->select("SUM(COALESCE(CASE WHEN actionType = 'Credit' AND type = 'Winning'  THEN amount END,0)) - SUM(COALESCE(CASE WHEN actionType = 'Debit' AND type = 'Withdraw' THEN amount END,0)) balance");
        $this->db->from('wallet w');
        $this->db->where('w.customerId',$customerId);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    
    public function getTotalBalanceByCustomer($customerId)
    {
        $this->db->select("SUM(COALESCE(CASE WHEN actionType = 'Credit' THEN amount END,0)) - SUM(COALESCE(CASE WHEN actionType = 'Debit' THEN amount END,0)) balance");
        $this->db->from('wallet w');
        $this->db->where('w.customerId',$customerId);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }
    public function checkPanCardBankDetails($id = null) {
        $this->db->select('b.*,p.isApproved as pIsApproved,b.isApproved as bIsApproved');
        $this->db->from('bank_account_details b');       
        $this->db->join('pancarddetails p', 'p.customerId=b.customerId');               
        if (!empty($id)) {
            $this->db->where('b.customerId', $id);
        }
        $query = $this->db->get();
        return $query->result();
    }
}
?>
