<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Waiter extends CI_Controller {

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
     * Waiter Dashboard - Display all tables with their orders
     */
    public function index()
    {
        $data['page_title'] = 'Waiter Dashboard';

        // Get all tables
        $tables = $this->db
            ->select('*')
            ->from('tables')
            ->where('deleted_at', NULL)
            ->order_by('table_number', 'ASC')
            ->get()
            ->result_array();

        // Enrich tables with order information
        foreach ($tables as &$table) {
            $order = $this->db
                ->select('id, order_number, order_status, total_amount, payment_status')
                ->from('orders')
                ->where('table_id', $table['id'])
                ->where('deleted_at', NULL)
                ->where_not_in('order_status', ['completed', 'cancelled'])
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();

            if ($order) {
                $table['order_id'] = $order['id'];
                $table['order_number'] = $order['order_number'];
                $table['order_status'] = $order['order_status'];
                $table['total_amount'] = $order['total_amount'];
                $table['payment_status'] = $order['payment_status'];

                // Count items in order
                $item_count = $this->db
                    ->select_sum('quantity')
                    ->from('order_items')
                    ->where('order_id', $order['id'])
                    ->get()
                    ->row_array();
                $table['item_count'] = $item_count['quantity'] ?? 0;
            } else {
                $table['order_id'] = null;
                $table['order_number'] = null;
                $table['order_status'] = null;
                $table['total_amount'] = null;
                $table['payment_status'] = null;
                $table['item_count'] = 0;
            }
        }

        $data['tables'] = $tables;

        $this->load->view('layouts/pos_minimal', [
            'content' => $this->load->view('waiter/index', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Get table bill details via AJAX
     */
    public function get_table_bill($table_id)
    {
        header('Content-Type: application/json');

        // Get active order for table
        $order = $this->db
            ->select('o.*')
            ->from('orders o')
            ->where('o.table_id', $table_id)
            ->where('o.deleted_at', NULL)
            ->where_not_in('o.order_status', ['completed', 'cancelled'])
            ->order_by('o.created_at', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'No active order for this table']);
            return;
        }

        // Get order items with meal details
        $items = $this->db
            ->select('oi.*, m.name as meal_name, m.description as meal_description')
            ->from('order_items oi')
            ->join('meals m', 'oi.meal_id = m.id', 'left')
            ->where('oi.order_id', $order['id'])
            ->get()
            ->result_array();

        $order['items'] = $items;
        echo json_encode($order);
    }

    /**
     * Get all tables with active orders via AJAX
     */
    public function get_tables_status()
    {
        header('Content-Type: application/json');

        // Get all tables
        $tables = $this->db
            ->select('*')
            ->from('tables')
            ->where('deleted_at', NULL)
            ->order_by('table_number', 'ASC')
            ->get()
            ->result_array();

        // Enrich tables with order information
        foreach ($tables as &$table) {
            $order = $this->db
                ->select('id, order_number, order_status, total_amount, payment_status')
                ->from('orders')
                ->where('table_id', $table['id'])
                ->where('deleted_at', NULL)
                ->where_not_in('order_status', ['completed', 'cancelled'])
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get()
                ->row_array();

            if ($order) {
                $table['order_id'] = $order['id'];
                $table['order_number'] = $order['order_number'];
                $table['order_status'] = $order['order_status'];
                $table['total_amount'] = $order['total_amount'];
                $table['payment_status'] = $order['payment_status'];

                // Count items in order
                $item_count = $this->db
                    ->select_sum('quantity')
                    ->from('order_items')
                    ->where('order_id', $order['id'])
                    ->get()
                    ->row_array();
                $table['item_count'] = $item_count['quantity'] ?? 0;
            } else {
                $table['order_id'] = null;
                $table['order_number'] = null;
                $table['order_status'] = null;
                $table['total_amount'] = null;
                $table['payment_status'] = null;
                $table['item_count'] = 0;
            }
        }

        echo json_encode(['tables' => $tables]);
    }

    /**
     * Mark table as served and close order
     */
    public function close_table($table_id)
    {
        header('Content-Type: application/json');

        // Get active order
        $order = $this->db
            ->select('id')
            ->from('orders')
            ->where('table_id', $table_id)
            ->where('deleted_at', NULL)
            ->where_not_in('order_status', ['completed', 'cancelled'])
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'No active order found']);
            return;
        }

        // Update order status to completed
        if ($this->Order_model->update($order['id'], ['order_status' => 'completed', 'payment_status' => 'completed'])) {
            // Update table status to available
            $this->db->update('tables', ['status' => 'available'], ['id' => $table_id]);

            echo json_encode(['status' => 'success', 'message' => 'Table closed successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to close table']);
        }
    }

    /**
     * Request payment for table via AJAX
     */
    public function request_payment($table_id)
    {
        header('Content-Type: application/json');

        // Get active order
        $order = $this->db
            ->select('id, payment_status')
            ->from('orders')
            ->where('table_id', $table_id)
            ->where('deleted_at', NULL)
            ->where_not_in('order_status', ['completed', 'cancelled'])
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get()
            ->row_array();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'No active order found']);
            return;
        }

        // Update order to mark payment requested
        if ($this->Order_model->update($order['id'], ['payment_status' => 'pending'])) {
            echo json_encode(['status' => 'success', 'message' => 'Payment requested']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to request payment']);
        }
    }

    /**
     * Update order status via AJAX
     */
    public function update_order_status($order_id, $status)
    {
        header('Content-Type: application/json');

        $valid_statuses = ['pending', 'preparing', 'ready', 'served', 'completed', 'cancelled'];

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

    private function check_access()
    {
        require_login();

        // Only waitresses and admins can access Waiter Dashboard
        if (!has_any_role(array('waitress', 'admin'))) {
            $this->session->set_flashdata('error', 'Only waitresses can access the Waiter Dashboard');
            redirect('dashboard');
        }
    }
}
?>
