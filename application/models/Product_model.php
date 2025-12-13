<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all products
    public function get_all($limit = NULL, $offset = 0)
    {
        $query = $this->db->select('*')
            ->from('products')
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC');

        if ($limit) {
            $query->limit($limit, $offset);
        }

        return $query->get()->result_array();
    }

    // Get product by ID
    public function get_by_id($id)
    {
        return $this->db->select('*')
            ->from('products')
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    // Create new product
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('products', $data);
    }

    // Update product
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('products', $data, ['id' => $id]);
    }

    // Delete product (soft delete)
    public function delete($id)
    {
        return $this->db->update('products', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    // Get low stock products
    public function get_low_stock($limit = 10)
    {
        return $this->db->select('*')
            ->from('products')
            ->where('deleted_at', NULL)
            ->where('stock <= min_stock_level', NULL, FALSE)
            ->limit($limit)
            ->get()
            ->result_array();
    }

    // Count products with filters
    public function count_products($filters = [])
    {
        $query = $this->db->select('COUNT(*) as count')
            ->from('products')
            ->where('deleted_at', NULL);

        // Apply filters
        if (!empty($filters['search'])) {
            $search_term = '%' . $filters['search'] . '%';
            $query->group_start()
                  ->like('name', $search_term)
                  ->or_like('sku', $search_term)
                  ->or_like('barcode', $search_term)
                  ->group_end();
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $result = $query->get()->row_array();
        return $result['count'];
    }

    // Get filtered and paginated products
    public function get_filtered($filters = [], $limit = 15, $offset = 0, $sort_by = 'created_at', $sort_order = 'DESC')
    {
        $query = $this->db->select('*')
            ->from('products')
            ->where('deleted_at', NULL);

        // Apply filters
        if (!empty($filters['search'])) {
            $search_term = '%' . $filters['search'] . '%';
            $query->group_start()
                  ->like('name', $search_term)
                  ->or_like('sku', $search_term)
                  ->or_like('barcode', $search_term)
                  ->group_end();
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        $allowed_sorts = ['id', 'name', 'cost_price', 'selling_price', 'stock', 'created_at'];
        if (in_array($sort_by, $allowed_sorts)) {
            $query->order_by($sort_by, $sort_order);
        } else {
            $query->order_by('created_at', 'DESC');
        }

        // Apply pagination
        $query->limit($limit, $offset);

        return $query->get()->result_array();
    }
}
