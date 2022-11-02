<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_master extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // faq
    function get_masterFaqContent()
    {
        $this->db->select('a.*, b.name')
        ->from('m_faq a')
        ->join('tb_user b', 'a.created_by = b.user_id', 'left')
        ->where(['a.is_deleted' => 0])
        ;

        $models = $this->db->get()->result();

        return $models;
    }

    // faq

    public function addMasterFaq()
    {
        $title = $this->input->post('title');

        $data = [
            'title' => $title,
            'order' => $this->db->get_where('m_faq', ['is_deleted' => 0])->num_rows(),
            'created_at' => time(),
            'created_by' => $this->session->userdata('user_id')
        ];

        $this->db->insert('m_faq', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function editMasterFaq()
    {
        $id = $this->input->post('id');
        $title = $this->input->post('title');

        $data = [
            'title' => $title,
            'modified_at' => time(),
            'modified_by' => $this->session->userdata('user_id')
        ];

        $this->db->where('id', $id);
        $this->db->update('m_faq', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function deleteMasterFaq()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('m_faq', ['is_deleted' => 1]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function addFaq()
    {
        $faq = $this->input->post('faq');
        $m_faq_id = $this->input->post('m_faq_id');
        $answer = $this->input->post('answer');

        $data = [
            'faq' => $faq,
            'm_faq_id' => $m_faq_id,
            'content' => $answer,
            'order' => $this->db->get_where('tb_faq', ['m_faq_id' => $m_faq_id, 'is_deleted' => 0])->num_rows(),
            'created_at' => time(),
            'created_by' => $this->session->userdata('user_id')
        ];

        $this->db->insert('tb_faq', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function editFaq()
    {
        $id = $this->input->post('id');
        $faq = $this->input->post('faq');
        $answer = $this->input->post('answer');

        $data = [
            'faq' => $faq,
            'content' => $answer,
            'modified_at' => time(),
            'modified_by' => $this->session->userdata('user_id')
        ];

        $this->db->where('id', $id);
        $this->db->update('tb_faq', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function deleteFaq()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('tb_faq', ['is_deleted' => 1]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function get_paymentsBatch(){
        $this->db->select('*')
        ->from('m_payments_batch')
        ->where(['is_deleted' => 0])
        ;

        $models = $this->db->get()->result();

        return $models;
    }

    public function addPaymentBatch()
    {
        $summit = $this->input->post('summit');
        $description = $this->input->post('desc');
        $amount = $this->input->post('amount');
        $amount_usd = $this->input->post('amount_usd');

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $data = [
            'summit' => $summit,
            'description' => $description,
            'amount' => $amount,
            'amount_usd' => $amount_usd,
            'start_date' => strtotime($start_date),
            'end_date' => strtotime($end_date),
            'created_at' => time(),
            'created_by' => $this->session->userdata('user_id')
        ];

        $this->db->insert('m_payments_batch', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function editPaymentBatch()
    {
        $id = $this->input->post('id');
        $summit = $this->input->post('summit');
        $description = $this->input->post('desc');
        $amount = $this->input->post('amount');
        $amount_usd = $this->input->post('amount_usd');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $data = [
            'summit' => $summit,
            'description' => $description,
            'amount' => $amount,
            'amount_usd' => $amount_usd,
            'start_date' => strtotime($start_date),
            'end_date' => strtotime($end_date),
            'modified_at' => time(),
            'modified_by' => $this->session->userdata('user_id')
        ];

        $this->db->where('id', $id);
        $this->db->update('m_payments_batch', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function deletePaymentBatch()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('m_payments_batch', ['is_deleted' => 1]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function changeParticipanPassword(){
        $user_id = $this->input->post('id');
        $password = $this->input->post('pass');

        $this->db->where(['user_id' => $user_id]);
        $this->db->update('tb_auth', ['password' => password_hash($password, PASSWORD_DEFAULT)]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function getDocuments(){
        $this->db->select('*')
        ->from('m_documents')
        ->where(['is_deleted' => 0])
        ;

        $models = $this->db->get()->result();

        return $models;
    }

    function getParticipansEssay(){
        $this->db->select('a.*')
        ->from('m_essay a')
        ->where(['a.is_deleted' => 0])
        ;

        $models = $this->db->get()->result();

        return $models;
    }

    function getAmbasadorByReferral($referral_code = null){
        return $this->db->get_where('tb_ambassador', ['referral_code' => $referral_code, 'status' => 1, 'is_deleted' => 0])->row();
    }

    function getAllAmbassador(){
        $models = $this->db->get_where('tb_ambassador', ['is_deleted' => 0])->result();

        foreach($models as $key => $val){
            $val->affiliate = $this->countAffiliateAmbassador($val->referral_code);
        }

        return $models;
    }

    function countAffiliateAmbassador($referral_code){
        return $this->db->get_where('tb_participants', ['referral_code' => $referral_code, 'is_deleted' => 0])->num_rows();
    }

    public function addAmbassador($poster = null)
    {
        $fullname           = $this->input->post('fullname');
        $referral_code      = $this->input->post('referral_code');
        $email              = $this->input->post('email');
        $address            = $this->input->post('address');
        $whatsapp_number    = $this->input->post('whatsapp_number');
        $nationality        = $this->input->post('nationality');
        $instagram          = $this->input->post('instagram');
        $tiktok             = $this->input->post('tiktok');
        $institution        = $this->input->post('institution');
        $occupation         = $this->input->post('occupation');

        $data = [
            'fullname' => $fullname,
            'referral_code' => $referral_code,
            'email' => $email,
            'address' => $address,
            'whatsapp_number' => $whatsapp_number,
            'nationality' => $nationality,
            'instagram' => $instagram,
            'tiktok' => $tiktok,
            'institution' => $institution,
            'occupation' => $occupation,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => time()
        ];

        $this->db->insert('tb_ambassador', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function editAmbassador($photo = null)
    {
        $id                 = $this->input->post('id');
        $fullname           = $this->input->post('fullname');
        $referral_code      = $this->input->post('referral_code');
        $email              = $this->input->post('email');
        $address            = $this->input->post('address');
        $whatsapp_number    = $this->input->post('whatsapp_number');
        $nationality        = $this->input->post('nationality');
        $instagram          = $this->input->post('instagram');
        $tiktok             = $this->input->post('tiktok');
        $institution        = $this->input->post('institution');
        $occupation         = $this->input->post('occupation');

        if($photo == null){
            $data = [
                'fullname' => $fullname,
                'referral_code' => $referral_code,
                'email' => $email,
                'address' => $address,
                'whatsapp_number' => $whatsapp_number,
                'nationality' => $nationality,
                'instagram' => $instagram,
                'tiktok' => $tiktok,
                'institution' => $institution,
                'occupation' => $occupation,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => time()
            ];
        }else{
            $data = [
                'fullname' => $fullname,
                'referral_code' => $referral_code,
                'email' => $email,
                'address' => $address,
                'whatsapp_number' => $whatsapp_number,
                'nationality' => $nationality,
                'instagram' => $instagram,
                'tiktok' => $tiktok,
                'institution' => $institution,
                'occupation' => $occupation,
                'modified_by' => $this->session->userdata('user_id'),
                'modified_at' => time()
            ];
        }

        $this->db->where('id', $id);
        $this->db->update('tb_ambassador', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function deleteAmbassador()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('tb_ambassador', ['is_deleted' => 1]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    function get_masterEligilibity(){
        return $this->db->get_where('m_eligilibity_countries', ['is_deleted' => 0])->result();
    }

    public function addEligilibity()
    {
        $nationality            = $this->input->post('nationality');
        $continent              = $this->input->post('continent');
        $type_visa              = $this->input->post('type_visa');
        $issued_from            = $this->input->post('issued_from');

        $data = [
            'nationality' => $nationality,
            'continent' => $continent,
            'type_visa' => $type_visa,
            'issued_from' => $issued_from,
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => time()
        ];

        $this->db->insert('m_eligilibity_countries', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function editEligilibity()
    {
        $id                     = $this->input->post('id');
        $nationality            = $this->input->post('nationality');
        $continent              = $this->input->post('continent');
        $type_visa              = $this->input->post('type_visa');
        $issued_from            = $this->input->post('issued_from');

        $data = [
            'nationality' => $nationality,
            'continent' => $continent,
            'type_visa' => $type_visa,
            'issued_from' => $issued_from,
            'modified_by' => $this->session->userdata('user_id'),
            'modified_at' => time()
        ];


        $this->db->where('id', $id);
        $this->db->update('m_eligilibity_countries', $data);
        return ($this->db->affected_rows() != 1) ? false : true;
    }

    public function deleteEligilibity()
    {
        $id = $this->input->post('id');

        $this->db->where('id', $id);
        $this->db->update('m_eligilibity_countries', ['is_deleted' => 1]);
        return ($this->db->affected_rows() != 1) ? false : true;
    }
}
