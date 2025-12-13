<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all expenses
    public function get_all($limit = NULL, $offset = 0)
    {
        $query = $this->db->select('*')
            ->from('expenses')
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC');

        if ($limit) {
            $query->limit($limit, $offset);
        }

        return $query->get()->result_array();
    }

    // Get expense by ID
    public function get_by_id($id)
    {
        return $this->db->select('*')
            ->from('expenses')
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    // Create new expense
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('expenses', $data);
    }

    // Update expense
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('expenses', $data, ['id' => $id]);
    }

    // Delete expense (soft delete)
    public function delete($id)
    {
        return $this->db->update('expenses', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    // Get total expenses
    public function get_total_expenses($start_date = NULL, $end_date = NULL)
    {
        $this->db->select_sum('amount');
        $this->db->from('expenses');
        $this->db->where('deleted_at', NULL);

        if ($start_date) {
            $this->db->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('DATE(created_at) <=', $end_date);
        }

        $result = $this->db->get()->row_array();
        return $result['amount'] ?? 0;
    }

    // Get expenses by category
    public function get_by_category($category_id, $start_date = NULL, $end_date = NULL)
    {
        $query = $this->db->select('*')
            ->from('expenses')
            ->where('category_id', $category_id)
            ->where('deleted_at', NULL);

        if ($start_date) {
            $query->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $query->where('DATE(created_at) <=', $end_date);
        }

        return $query->get()->result_array();
    }
}
