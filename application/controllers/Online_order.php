<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Online_order Controller
 *
 * Handle customer order submission and tracking
 * NO AUTHENTICATION REQUIRED - Public access
 */
class Online_order extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('settings');
        $this->load->library('session');
        $this->load->model('Order_model');
        $this->load->model('Meal_model');
        $this->load->model('Table_model');
        $this->load->model('Table_usage_session_model');
        // NO require_login() - public access for customers
    }

    /**
     * Create new order from customer
     * POST endpoint - receives JSON data
     */
    public function create()
    {
        header('Content-Type: application/json');

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Validation
        if (empty($input['customer_name'])) {
            echo json_encode(['success' => false, 'message' => 'Customer name is required']);
            return;
        }

        if (empty($input['customer_phone'])) {
            echo json_encode(['success' => false, 'message' => 'Phone number is required']);
            return;
        }

        if (empty($input['items']) || !is_array($input['items'])) {
            echo json_encode(['success' => false, 'message' => 'No items in cart']);
            return;
        }

        $order_type = $input['order_type'] ?? 'takeaway';

        // Delivery validation
        if ($order_type === 'delivery') {
            if (empty($input['delivery_address'])) {
                echo json_encode(['success' => false, 'message' => 'Delivery address is required']);
                return;
            }

            // Check minimum order amount
            $subtotal = 0;
            foreach ($input['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $min_delivery = setting('minimum_delivery_order', 500);
            if ($subtotal < $min_delivery) {
                echo json_encode([
                    'success' => false,
                    'message' => "Minimum order for delivery is PKR {$min_delivery}"
                ]);
                return;
            }
        }

        // Rate limiting (simple IP-based)
        if (!$this->check_rate_limit()) {
            echo json_encode(['success' => false, 'message' => 'Too many orders. Please try again later.']);
            return;
        }

        // Start transaction
        $this->db->trans_start();

        try {
            // Generate order number
            $order_number = $this->generate_order_number();

            // Calculate totals
            $subtotal = 0;
            foreach ($input['items'] as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax_rate = setting('tax_rate', 17);
            $tax_amount = $subtotal * ($tax_rate / 100);

            $delivery_fee = 0;
            if ($order_type === 'delivery') {
                $delivery_fee = setting('delivery_fee', 100);
            }

            $total = $subtotal + $tax_amount + $delivery_fee;

            // Create order
            $order_data = [
                'order_number' => $order_number,
                'customer_name' => $input['customer_name'],
                'customer_phone' => $input['customer_phone'],
                'customer_email' => $input['customer_email'] ?? null,
                'table_id' => ($order_type === 'dine-in' && !empty($input['table_id'])) ? $input['table_id'] : null,
                'order_type' => $order_type,
                'delivery_address' => $input['delivery_address'] ?? null,
                'special_instructions' => $input['special_instructions'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'delivery_fee' => $delivery_fee,
                'total_amount' => $total,
                'payment_method' => 'cash', // Default to cash on delivery/pickup
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'created_by' => null, // Customer order, no staff user
            ];

            $this->Order_model->create($order_data);
            $order_id = $this->db->insert_id();

            // Add order items
            foreach ($input['items'] as $item) {
                $item_data = [
                    'order_id' => $order_id,
                    'meal_id' => $item['meal_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ];
                $this->db->insert('order_items', $item_data);
            }

            // Update table status if dine-in
            if ($order_type === 'dine-in' && !empty($input['table_id'])) {
                // Calculate idle time before this session
                $idle_minutes = $this->Table_usage_session_model->calculate_idle_time($input['table_id']);

                // Start new session
                $this->Table_usage_session_model->start_session($input['table_id'], $order_id, $idle_minutes);

                // Update table status to occupied
                $this->Table_model->update($input['table_id'], ['status' => 'occupied']);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['success' => false, 'message' => 'Failed to create order']);
                return;
            }

            // Success response
            echo json_encode([
                'success' => true,
                'order_number' => $order_number,
                'total' => $total,
                'estimated_time' => setting('estimated_preparation_time', 30),
                'tracking_url' => base_url('order/track/' . $order_number)
            ]);

        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Order tracking page
     *
     * @param string $order_number Order number
     */
    public function track($order_number)
    {
        $data = array();
        $data['page_title'] = 'Track Order - ' . $order_number;

        // Get order with items
        $order = $this->Order_model->get_by_order_number($order_number);

        if (!$order) {
            show_error('Order not found', 404);
            return;
        }

        $data['order'] = $order;
        $data['items'] = $this->Order_model->get_items($order['id']);
        $data['currency'] = setting('currency', 'PKR');

        $this->load->view('online_order/track', $data);
    }

    /**
     * Get order status (JSON endpoint for polling)
     *
     * @param string $order_number Order number
     */
    public function get_status($order_number)
    {
        header('Content-Type: application/json');

        $order = $this->Order_model->get_by_order_number($order_number);

        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
            return;
        }

        echo json_encode([
            'order_number' => $order['order_number'],
            'order_status' => $order['order_status'],
            'payment_status' => $order['payment_status'],
            'total_amount' => $order['total_amount'],
            'created_at' => $order['created_at']
        ]);
    }

    /**
     * Generate unique order number
     *
     * @return string Order number (ORD-YYYYMMDD-NNNNN)
     */
    private function generate_order_number()
    {
        $prefix = 'ORD-' . date('Ymd');
        $latest = $this->db->select('order_number')
                          ->from('orders')
                          ->like('order_number', $prefix)
                          ->order_by('id', 'DESC')
                          ->limit(1)
                          ->get()
                          ->row_array();

        if ($latest) {
            $number = (int)substr($latest['order_number'], -5) + 1;
        } else {
            $number = 1;
        }

        return $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Simple rate limiting (max 10 orders per IP per hour)
     *
     * @return bool True if allowed, false if rate limit exceeded
     */
    private function check_rate_limit()
    {
        $ip = $this->input->ip_address();
        $cache_key = "order_rate_limit_{$ip}";

        $attempts = $this->session->userdata($cache_key) ?? 0;

        if ($attempts > 10) {
            return false;
        }

        $this->session->set_userdata($cache_key, $attempts + 1);
        $this->session->mark_as_temp($cache_key, 3600); // Expire in 1 hour

        return true;
    }
}
