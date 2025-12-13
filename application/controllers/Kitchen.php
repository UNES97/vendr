<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('Order_model');
        $this->load->model('Meal_model');
        $this->check_access();
    }

    /**
     * Kitchen Display System - Main Page
     */
    public function index()
    {
        $data['page_title'] = 'Kitchen Display System';

        // Get all pending orders
        $data['pending_orders'] = $this->db
            ->select('o.*, COUNT(oi.id) as item_count')
            ->from('orders o')
            ->join('order_items oi', 'o.id = oi.order_id', 'left')
            ->where('o.order_status', 'pending')
            ->where('o.deleted_at', NULL)
            ->group_by('o.id')
            ->order_by('o.created_at', 'ASC')
            ->get()
            ->result_array();

        // Get all preparing orders
        $data['preparing_orders'] = $this->db
            ->select('o.*, COUNT(oi.id) as item_count')
            ->from('orders o')
            ->join('order_items oi', 'o.id = oi.order_id', 'left')
            ->where('o.order_status', 'preparing')
            ->where('o.deleted_at', NULL)
            ->group_by('o.id')
            ->order_by('o.created_at', 'ASC')
            ->get()
            ->result_array();

        $this->load->view('layouts/pos_minimal', [
            'content' => $this->load->view('kitchen/index', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Get order details via AJAX
     */
    public function get_order_details($order_id)
    {
        header('Content-Type: application/json');

        $order = $this->Order_model->get_by_id($order_id);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
            return;
        }

        $items = $this->Order_model->get_items($order_id);

        // Get meal details for each item
        foreach ($items as &$item) {
            $meal = $this->Meal_model->get_by_id($item['meal_id']);
            $item['meal_name'] = $meal['name'] ?? 'Unknown Item';
            $item['meal_description'] = $meal['description'] ?? '';
        }

        $order['items'] = $items;

        echo json_encode($order);
    }

    /**
     * Update order status via AJAX
     */
    public function update_order_status($order_id, $status)
    {
        header('Content-Type: application/json');

        $valid_statuses = ['pending', 'preparing', 'ready', 'completed', 'cancelled'];

        if (!in_array($status, $valid_statuses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            return;
        }

        if ($this->Order_model->update($order_id, ['order_status' => $status])) {
            echo json_encode(['status' => 'success', 'message' => 'Order status updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update order status']);
        }
    }

    /**
     * Get updated orders list via AJAX
     */
    public function get_orders_list()
    {
        header('Content-Type: application/json');

        $pending = $this->db
            ->select('o.*, COUNT(oi.id) as item_count')
            ->from('orders o')
            ->join('order_items oi', 'o.id = oi.order_id', 'left')
            ->where('o.order_status', 'pending')
            ->where('o.deleted_at', NULL)
            ->group_by('o.id')
            ->order_by('o.created_at', 'ASC')
            ->get()
            ->result_array();

        $preparing = $this->db
            ->select('o.*, COUNT(oi.id) as item_count')
            ->from('orders o')
            ->join('order_items oi', 'o.id = oi.order_id', 'left')
            ->where('o.order_status', 'preparing')
            ->where('o.deleted_at', NULL)
            ->group_by('o.id')
            ->order_by('o.created_at', 'ASC')
            ->get()
            ->result_array();

        echo json_encode([
            'pending' => $pending,
            'preparing' => $preparing
        ]);
    }

    private function check_access()
    {
        require_login();

        // Only chefs and admins can access Kitchen
        if (!has_any_role(array('chef', 'admin'))) {
            $this->session->set_flashdata('error', 'Only chefs can access the Kitchen Display System');
            redirect('dashboard');
        }
    }
}
