<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($type = null)
    {
        $query = $this->db->where('deleted_at', NULL)
                          ->where('status', 'active');

        if ($type !== null) {
            // Filter by type: 'product' (ingredient), 'meal', or leave null for all
            if ($type === 'product') {
                $query = $query->where_in('type', ['ingredient', 'both']);
            } elseif ($type === 'meal') {
                $query = $query->where_in('type', ['meal', 'both']);
            }
        }

        $query = $query->get('categories');
        return $query->result_array();
    }

    public function get_by_id($id)
    {
        $query = $this->db->where('id', $id)
                          ->where('deleted_at', NULL)
                          ->get('categories');
        return $query->row_array();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('categories', $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)
                        ->update('categories', $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)
                        ->update('categories', ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
