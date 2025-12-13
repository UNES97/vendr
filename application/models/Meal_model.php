<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meal_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all meals with pagination and filters
     */
    public function get_filtered($filters = [], $limit = 15, $offset = 0, $sort_by = 'created_at', $sort_order = 'DESC')
    {
        $this->db->select('m.*, c.name as category_name');
        $this->db->from('meals m');
        $this->db->join('categories c', 'c.id = m.category_id', 'left');

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->where("(m.name LIKE '%$search%' OR m.sku LIKE '%$search%')");
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $this->db->where('m.category_id', $filters['category_id']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $this->db->where('m.status', $filters['status']);
        }

        // Deleted at filter (soft deletes)
        $this->db->where('m.deleted_at IS NULL');

        // Sorting
        $this->db->order_by($sort_by, $sort_order);

        // Pagination
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Count total meals with filters
     */
    public function count_meals($filters = [])
    {
        $this->db->from('meals');

        // Search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->where("(name LIKE '%$search%' OR sku LIKE '%$search%')");
        }

        // Category filter
        if (!empty($filters['category_id'])) {
            $this->db->where('category_id', $filters['category_id']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $this->db->where('status', $filters['status']);
        }

        // Deleted at filter
        $this->db->where('deleted_at IS NULL');

        return $this->db->count_all_results();
    }

    /**
     * Get meal by ID with recipe details
     */
    public function get_by_id($id)
    {
        $this->db->select('m.*, c.name as category_name');
        $this->db->from('meals m');
        $this->db->join('categories c', 'c.id = m.category_id', 'left');
        $this->db->where('m.id', $id);
        $this->db->where('m.deleted_at IS NULL');

        return $this->db->get()->row_array();
    }

    /**
     * Get all meals
     */
    public function get_all()
    {
        $this->db->where('deleted_at IS NULL');
        $this->db->where('status', 'active');
        $this->db->order_by('name', 'ASC');

        return $this->db->get('meals')->result_array();
    }

    /**
     * Create new meal
     */
    public function create($data)
    {
        return $this->db->insert('meals', $data);
    }

    /**
     * Update meal
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('meals', $data);
    }

    /**
     * Soft delete meal
     */
    public function delete($id)
    {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
