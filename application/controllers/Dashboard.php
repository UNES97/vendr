<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        // Load any models needed
        $this->load->model('Product_model');
        $this->load->model('Order_model');
        $this->load->model('Expense_model');

        // Require login for all dashboard methods
        require_login();
    }

    public function index()
    {
        // Check if user can access dashboard
        $this->roles->require_page_access('dashboard');

        $data['page_title'] = 'Dashboard';
        $data['user_name'] = user_name();
        $data['user_email'] = user_email();
        $data['user_initial'] = get_user_initials();

        // Load application settings
        $data['app_name'] = get_app_name();
        $data['restaurant_name'] = get_restaurant_name();
        $data['currency'] = get_currency();

        // Get dashboard statistics from database
        $total_revenue = floatval($this->Order_model->get_total_revenue());
        $all_orders = $this->Order_model->get_all();
        $total_orders = count($all_orders);
        $avg_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;

        // Get low stock items
        $low_stock_items = $this->Product_model->get_low_stock(100);

        // Get total expenses
        $total_expenses = $this->Expense_model->get_total_expenses();

        // Calculate month-over-month changes
        $this_month_start = date('Y-m-01');
        $this_month_end = date('Y-m-d');
        $last_month_start = date('Y-m-01', strtotime('-1 month'));
        $last_month_end = date('Y-m-t', strtotime('-1 month'));

        $this_month_revenue = floatval($this->Order_model->get_total_revenue($this_month_start, $this_month_end));
        $last_month_revenue = floatval($this->Order_model->get_total_revenue($last_month_start, $last_month_end));
        $revenue_change = $last_month_revenue > 0 ? (($this_month_revenue - $last_month_revenue) / $last_month_revenue) * 100 : 0;

        $this_month_orders = $this->db->select('COUNT(*) as count')
            ->from('orders')
            ->where('deleted_at', NULL)
            ->where('DATE(created_at) >=', $this_month_start)
            ->where('DATE(created_at) <=', $this_month_end)
            ->get()
            ->row_array();
        $this_month_order_count = intval($this_month_orders['count']);

        $last_month_orders = $this->db->select('COUNT(*) as count')
            ->from('orders')
            ->where('deleted_at', NULL)
            ->where('DATE(created_at) >=', $last_month_start)
            ->where('DATE(created_at) <=', $last_month_end)
            ->get()
            ->row_array();
        $last_month_order_count = intval($last_month_orders['count']);
        $orders_change = $last_month_order_count > 0 ? (($this_month_order_count - $last_month_order_count) / $last_month_order_count) * 100 : 0;

        $data['stats'] = [
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders,
            'avg_order_value' => $avg_order_value,
            'items_sold' => $this->_count_items_sold(),
            'low_stock_items' => count($low_stock_items),
            'total_expenses' => $total_expenses,
            'revenue_change' => $revenue_change,
            'orders_change' => $orders_change,
        ];

        // Get today's data
        $today_orders = $this->Order_model->get_today_orders();
        $today_revenue = floatval($this->Order_model->get_total_revenue(date('Y-m-d'), date('Y-m-d')));

        $data['today'] = [
            'revenue' => $today_revenue,
            'orders' => count($today_orders),
            'transactions' => count($today_orders),
        ];

        // Get recent orders (last 5)
        $recent = $this->Order_model->get_all(5);
        $data['recent_orders'] = array_map(function($order) {
            return [
                'id' => $order['id'],
                'order_number' => $order['order_number'],
                'total' => $order['total_amount'],
                'status' => $order['order_status'],
                'time' => $this->_get_relative_time($order['created_at'])
            ];
        }, $recent);

        // Get top products (by revenue)
        $data['top_products'] = $this->_get_top_products(5);

        // Get sales trend (last 7 days)
        $data['sales_trend'] = $this->get_sales_trend(7);

        // Load the view
        $this->load->view('layouts/base', [
            'content' => $this->load->view('dashboard/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_initial' => $data['user_initial'],
        ]);
    }

    /**
     * Count total items sold from all orders
     */
    private function _count_items_sold()
    {
        $total = $this->db->select_sum('quantity')
            ->from('order_items')
            ->get()
            ->row_array();
        return intval($total['quantity'] ?? 0);
    }

    /**
     * Get relative time (e.g., "10 mins ago")
     */
    private function _get_relative_time($timestamp)
    {
        $time = strtotime($timestamp);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) return "{$diff}s ago";
        if ($diff < 3600) return floor($diff / 60) . "m ago";
        if ($diff < 86400) return floor($diff / 3600) . "h ago";
        if ($diff < 604800) return floor($diff / 86400) . "d ago";

        return date('M d, Y', $time);
    }

    /**
     * Get top products by revenue
     */
    private function _get_top_products($limit = 5)
    {
        $query = "
            SELECT m.id, m.name,
                   COUNT(oi.id) as quantity,
                   SUM(oi.total_price) as revenue
            FROM meals m
            LEFT JOIN order_items oi ON m.id = oi.meal_id
            WHERE m.deleted_at IS NULL
            GROUP BY m.id, m.name
            ORDER BY revenue DESC
            LIMIT ?
        ";

        $result = $this->db->query($query, [$limit])->result_array();

        return array_map(function($item) {
            return [
                'name' => $item['name'],
                'quantity' => intval($item['quantity'] ?? 0),
                'revenue' => floatval($item['revenue'] ?? 0),
            ];
        }, $result);
    }

    /**
     * Get statistics for a date range
     * Reusable widget method for flexible date queries
     */
    public function get_stats_by_date_range($start_date = null, $end_date = null)
    {
        if ($start_date === null) {
            $start_date = date('Y-m-01'); // First day of month
        }
        if ($end_date === null) {
            $end_date = date('Y-m-d'); // Today
        }

        $revenue = floatval($this->Order_model->get_total_revenue($start_date, $end_date));

        $orders = $this->db->select('*')
            ->from('orders')
            ->where('deleted_at', NULL)
            ->where('DATE(created_at) >=', $start_date)
            ->where('DATE(created_at) <=', $end_date)
            ->get()
            ->result_array();

        $order_count = count($orders);
        $avg_value = $order_count > 0 ? $revenue / $order_count : 0;

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'revenue' => $revenue,
            'order_count' => $order_count,
            'avg_order_value' => $avg_value,
        ];
    }

    /**
     * Get sales trend data (daily totals for chart)
     */
    public function get_sales_trend($days = 7)
    {
        $trend = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $day_label = date('D', strtotime("-{$i} days"));

            $revenue = floatval($this->Order_model->get_total_revenue($date, $date));

            $trend[] = [
                'date' => $date,
                'day' => $day_label,
                'revenue' => $revenue,
            ];
        }

        return $trend;
    }

    /**
     * Get top categories by revenue
     */
    public function get_top_categories($limit = 5)
    {
        $query = "
            SELECT c.id, c.name,
                   COUNT(oi.id) as items_sold,
                   SUM(oi.total_price) as revenue
            FROM meal_categories c
            LEFT JOIN meals m ON c.id = m.category_id
            LEFT JOIN order_items oi ON m.id = oi.meal_id
            WHERE c.deleted_at IS NULL AND m.deleted_at IS NULL
            GROUP BY c.id, c.name
            ORDER BY revenue DESC
            LIMIT ?
        ";

        $result = $this->db->query($query, [$limit])->result_array();

        return array_map(function($item) {
            return [
                'id' => intval($item['id']),
                'name' => $item['name'],
                'items_sold' => intval($item['items_sold'] ?? 0),
                'revenue' => floatval($item['revenue'] ?? 0),
            ];
        }, $result);
    }

    /**
     * Get payment method breakdown
     */
    public function get_payment_breakdown($start_date = null, $end_date = null)
    {
        $query = $this->db->select('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->from('orders')
            ->where('deleted_at', NULL);

        if ($start_date) {
            $query->where('DATE(created_at) >=', $start_date);
        }
        if ($end_date) {
            $query->where('DATE(created_at) <=', $end_date);
        }

        $result = $query->group_by('payment_method')
            ->order_by('total', 'DESC')
            ->get()
            ->result_array();

        return array_map(function($item) {
            return [
                'method' => $item['payment_method'],
                'count' => intval($item['count']),
                'total' => floatval($item['total']),
            ];
        }, $result);
    }

    /**
     * Get order status breakdown
     */
    public function get_order_status_breakdown($start_date = null, $end_date = null)
    {
        $query = $this->db->select('order_status, COUNT(*) as count')
            ->from('orders')
            ->where('deleted_at', NULL);

        if ($start_date) {
            $query->where('DATE(created_at) >=', $start_date);
        }
        if ($end_date) {
            $query->where('DATE(created_at) <=', $end_date);
        }

        $result = $query->group_by('order_status')
            ->get()
            ->result_array();

        return array_map(function($item) {
            return [
                'status' => $item['order_status'],
                'count' => intval($item['count']),
            ];
        }, $result);
    }
}
