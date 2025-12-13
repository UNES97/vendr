<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Get all orders
    public function get_all($limit = NULL, $offset = 0)
    {
        $query = $this->db->select('*')
            ->from('orders')
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC');

        if ($limit) {
            $query->limit($limit, $offset);
        }

        return $query->get()->result_array();
    }

    // Get order by ID
    public function get_by_id($id)
    {
        return $this->db->select('*')
            ->from('orders')
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    // Get order items
    public function get_items($order_id)
    {
        return $this->db->select('order_items.*, meals.name')
            ->from('order_items')
            ->join('meals', 'meals.id = order_items.meal_id', 'left')
            ->where('order_id', $order_id)
            ->get()
            ->result_array();
    }

    // Create new order
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('orders', $data);
    }

    // Update order
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('orders', $data, ['id' => $id]);
    }

    // Delete order (soft delete)
    public function delete($id)
    {
        return $this->db->update('orders', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    // Get today's orders
    public function get_today_orders()
    {
        $today = date('Y-m-d');
        return $this->db->select('*')
            ->from('orders')
            ->where('DATE(created_at)', $today)
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC')
            ->get()
            ->result_array();
    }

    // Get total revenue
    public function get_total_revenue($start_date = NULL, $end_date = NULL)
    {
        $this->db->select_sum('total_amount');
        $this->db->from('orders');
        $this->db->where('deleted_at', NULL);

        if ($start_date) {
            $this->db->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('DATE(created_at) <=', $end_date);
        }

        $result = $this->db->get()->row_array();
        return $result['total_amount'] ?? 0;
    }

    // ===== ONLINE ORDERING METHODS =====

    // Get order by order number
    public function get_by_order_number($order_number)
    {
        return $this->db->select('*')
            ->from('orders')
            ->where('order_number', $order_number)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    // Get order with items (joined query)
    public function get_order_with_items($order_number)
    {
        $order = $this->get_by_order_number($order_number);

        if (!$order) {
            return null;
        }

        $order['items'] = $this->db->select('order_items.*, meals.name')
            ->from('order_items')
            ->join('meals', 'meals.id = order_items.meal_id', 'left')
            ->where('order_items.order_id', $order['id'])
            ->get()
            ->result_array();

        return $order;
    }

    // Get online orders (order_type = 'online' or created_by IS NULL)
    public function get_online_orders($limit = 50, $offset = 0)
    {
        return $this->db->select('*')
            ->from('orders')
            ->where('deleted_at', NULL)
            ->where('created_by', NULL) // Customer orders have no staff user
            ->order_by('created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }

    // Count online orders
    public function count_online_orders()
    {
        return $this->db->from('orders')
            ->where('deleted_at', NULL)
            ->where('created_by', NULL)
            ->count_all_results();
    }
}
