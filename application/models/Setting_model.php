<?php

class Setting_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('upload');
    }

    public function getAllSettings() {
        $this->db->select('*');
        $this->db->from('settings');
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    public function getSetting($str) {
        $this->db->select('*');
        $this->db->from('settings');
        $this->db->where('title', $str);
        $query = $this->db->get();
        $res = $query->result();
        return $res[0]->value;
    }

    public function updateSetting($title, $value) {
        $data = array(
            'value' => $value,
            'updatedAt' => date('Y-m-d H:i:s')
        );
        $this->db->where('title', $title);
        $this->db->update('settings', $data);
    }

    public function updateSiteLogo($postData) {
        $config['upload_path'] = './assets/user_files/logo';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('site_logo')) {
            $error = $this->upload->display_errors();
            $returnData = array(
                'success' => 0,
                'message' => $error,
            );
        } else {
            $fileData = $this->upload->data();
            $file_name = $fileData['file_name'];

            $data = array(
                'value' => $fileData['file_name'],
                'updatedAt' => date('Y-m-d H:i:s')
            );
            $this->db->where('title', 'Site Logo');
            $this->db->update('settings', $data);

            $returnData = array(
                'success' => 1
            );
        }
        return $returnData;
    }

    public function getEmailContent($search = array(), $sort = array(), $limit = null, $offset = null) {
        $this->db->select('*');
        $this->db->from('email_content');

        if (!empty($search['freetext'])) {
            $this->db->where("(subject like '%" . $search['freetext'] . "%'", NULL, FALSE);
            $this->db->or_where("DATE_FORMAT(CONVERT_TZ(updated_at,'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $search['freetext'] . "%')", NULL, FALSE);
        }

        $fields = array('', 'subject', 'updated_at', '');

        foreach ($search['columntext'] as $key => $value) {
            if (!empty($value)) {
                if ($fields[$key] == 'updated_at') {
                    $this->db->where("DATE_FORMAT(CONVERT_TZ(" . $fields[$key] . ",'+00:00','" . $this->session->userdata('timeCode') . "'),'%d/%m/%Y %H:%i:%s') like '%" . $value . "%'", NULL, FALSE);
                } else {
                    $this->db->like($fields[$key], $value);
                }
            }
        }


        if (!empty($sort)) {
            foreach ($sort as $index => $type) {
                if (!empty($fields[$index])) {
                    $this->db->order_by($fields[$index], $type);
                }
            }
        }

        if (empty($limit)) {
            $query = $this->db->get();
            return $query->num_rows();
        } else {
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result();
        }
    }

    public function getEmailContentBySlug($str) {
        $this->db->select('*');
        $this->db->from('email_content');
        $this->db->where('slug', $str);
        $query = $this->db->get();
        $res = $query->result();
        return $res;
    }

    public function updateEmailContent($postData, $slug) {
        $update_data = array(
            'subject' => $postData['subject'],
            'message' => $postData['content'],
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->db->where('slug', $slug);
        $this->db->update('email_content', $update_data);
    }

    public function converTimeZone($format, $date) {
        $date = preg_replace('/' . preg_quote('(') . '.*?' . preg_quote(')') . '/', '', $date);
        if (!empty($this->session->userdata('timeZone'))) {
            $date = new DateTime($date, new DateTimeZone('UTC'));
            $date->setTimezone(new DateTimeZone($this->session->userdata('timeZone')));
            return $date->format($format);
        } else {
            return date($format, strtotime($date));
        }
    }

    public function converTimeZoneUTC($format, $date) {
        $date = preg_replace('/' . preg_quote('(') . '.*?' . preg_quote(')') . '/', '', $date);
        if (!empty($this->session->userdata('timeZone'))) {
            $date = new DateTime($date, new DateTimeZone($this->session->userdata('timeZone')));
            $date->setTimezone(new DateTimeZone('UTC'));
            return $date->format($format);
        } else {
            return date($format, strtotime($date));
        }
    }

    function randomToken($length = 4, $result = '') {
        for ($i = 0; $i < $length; $i++) {

            $case = mt_rand(0, 1);
            switch ($case) {

                case 0:
                    $data = mt_rand(0, 9);
                    break;
                case 1:
                    $alpha = range('a', 'z');
                    $item = mt_rand(0, 25);

                    $data = strtoupper($alpha[$item]);
                    break;
            }

            $result .= $data;
        }

        return $result;
    }

}

?>
