<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_movements_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Create a stock movement record
     */
    public function create($data)
    {
        return $this->db->insert('stock_movements', $data);
    }

    /**
     * Get all stock movements with filters
     */
    public function get_filtered($filters = [], $limit = 15, $offset = 0)
    {
        $this->db->select('sm.*, p.name as product_name, p.sku');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');

        // Apply filters
        if (!empty($filters['product_search'])) {
            $this->db->like('p.name', $filters['product_search']);
            $this->db->or_like('p.sku', $filters['product_search']);
        }

        if (!empty($filters['type'])) {
            $this->db->where('sm.type', $filters['type']);
        }

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        // Order and limit
        $this->db->order_by('sm.created_at', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Count stock movements with filters
     */
    public function count_movements($filters = [])
    {
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');

        if (!empty($filters['product_search'])) {
            $this->db->like('p.name', $filters['product_search']);
            $this->db->or_like('p.sku', $filters['product_search']);
        }

        if (!empty($filters['type'])) {
            $this->db->where('sm.type', $filters['type']);
        }

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Get check-in movements (type = 'in')
     */
    public function get_checkins($filters = [], $limit = 15, $offset = 0)
    {
        $filters['type'] = 'in';
        return $this->get_filtered($filters, $limit, $offset);
    }

    /**
     * Count check-in movements
     */
    public function count_checkins($filters = [])
    {
        $filters['type'] = 'in';
        return $this->count_movements($filters);
    }

    /**
     * Get checkout movements (type = 'out')
     */
    public function get_checkouts($filters = [], $limit = 15, $offset = 0)
    {
        $filters['type'] = 'out';
        return $this->get_filtered($filters, $limit, $offset);
    }

    /**
     * Count checkout movements
     */
    public function count_checkouts($filters = [])
    {
        $filters['type'] = 'out';
        return $this->count_movements($filters);
    }

    /**
     * Get total units for check-ins or checkouts
     */
    public function get_total_units($type = 'in', $filters = [])
    {
        $this->db->select('SUM(quantity) as total');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');
        $this->db->where('sm.type', $type);

        if (!empty($filters['product_search'])) {
            $this->db->like('p.name', $filters['product_search']);
            $this->db->or_like('p.sku', $filters['product_search']);
        }

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        $result = $this->db->get()->row_array();
        return $result['total'] ?? 0;
    }

    /**
     * Get latest movement date
     */
    public function get_latest_date($type = 'in', $filters = [])
    {
        $this->db->select('MAX(sm.created_at) as latest');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');
        $this->db->where('sm.type', $type);

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        $result = $this->db->get()->row_array();
        return $result['latest'] ?? null;
    }

    /**
     * Get waste units (checkouts with reason = 'waste')
     */
    public function get_waste_units($filters = [])
    {
        $this->db->select('SUM(quantity) as total');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');
        $this->db->where('sm.type', 'out');
        $this->db->where('sm.reference_type', 'waste');

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        $result = $this->db->get()->row_array();
        return $result['total'] ?? 0;
    }

    /**
     * Get recent movements
     */
    public function get_recent($type = 'in', $limit = 5)
    {
        $this->db->select('sm.*, p.name as product_name, p.sku');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');
        $this->db->where('sm.type', $type);
        $this->db->order_by('sm.created_at', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Count unique grouped transactions
     */
    public function count_grouped_transactions($filters = [])
    {
        $this->db->select('COUNT(DISTINCT sm.transaction_id) as count');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');

        // Apply filters
        if (!empty($filters['product_search'])) {
            $this->db->like('p.name', $filters['product_search']);
            $this->db->or_like('p.sku', $filters['product_search']);
        }

        if (!empty($filters['type'])) {
            $this->db->where('sm.type', $filters['type']);
        }

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        // Only include records with a transaction_id (exclude NULL values)
        $this->db->where('sm.transaction_id IS NOT NULL', null, false);

        $result = $this->db->get()->row_array();
        return $result['count'] ?? 0;
    }

    /**
     * Get grouped check-in transactions by transaction_id
     */
    public function get_grouped_checkins($filters = [], $limit = 15, $offset = 0)
    {
        $filters['type'] = 'in';
        return $this->get_grouped_transactions($filters, $limit, $offset);
    }

    /**
     * Count unique grouped check-in transactions
     */
    public function count_grouped_checkins($filters = [])
    {
        $filters['type'] = 'in';
        return $this->count_grouped_transactions($filters);
    }

    /**
     * Get grouped checkout transactions by transaction_id
     */
    public function get_grouped_checkouts($filters = [], $limit = 15, $offset = 0)
    {
        $filters['type'] = 'out';
        return $this->get_grouped_transactions($filters, $limit, $offset);
    }

    /**
     * Count unique grouped checkout transactions
     */
    public function count_grouped_checkouts($filters = [])
    {
        $filters['type'] = 'out';
        return $this->count_grouped_transactions($filters);
    }

    /**
     * Get transactions grouped by transaction_id
     */
    public function get_grouped_transactions($filters = [], $limit = 15, $offset = 0)
    {
        // Get unique transactions with their items
        $this->db->select('sm.transaction_id,
                          MAX(sm.type) as type,
                          MAX(sm.reference_type) as reference_type,
                          MAX(sm.created_by) as created_by,
                          MAX(sm.created_at) as created_at,
                          MAX(sm.notes) as notes,
                          GROUP_CONCAT(CONCAT(p.name, " (", sm.quantity, " units)") SEPARATOR ", ") as products,
                          SUM(sm.quantity) as total_quantity,
                          COUNT(sm.id) as item_count,
                          SUM(sm.total_cost) as transaction_total_cost');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');

        // Apply filters
        if (!empty($filters['product_search'])) {
            $this->db->like('p.name', $filters['product_search']);
            $this->db->or_like('p.sku', $filters['product_search']);
        }

        if (!empty($filters['type'])) {
            $this->db->where('sm.type', $filters['type']);
        }

        if (!empty($filters['reason'])) {
            $this->db->where('sm.reference_type', $filters['reason']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(sm.created_at) >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(sm.created_at) <=', $filters['date_to']);
        }

        // Only include records with a transaction_id (exclude NULL values)
        $this->db->where('sm.transaction_id IS NOT NULL', null, false);

        $this->db->group_by('sm.transaction_id');
        $this->db->order_by('MAX(sm.created_at)', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result_array();
    }

    /**
     * Get all items for a specific transaction
     */
    public function get_transaction_items($transaction_id)
    {
        $this->db->select('sm.*, p.name as product_name, p.sku, p.stock as current_quantity');
        $this->db->from('stock_movements sm');
        $this->db->join('products p', 'p.id = sm.product_id', 'left');
        $this->db->where('sm.transaction_id', $transaction_id);
        $this->db->order_by('sm.created_at', 'ASC');

        return $this->db->get()->result_array();
    }
}
