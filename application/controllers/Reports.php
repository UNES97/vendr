<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('Table_model');
        $this->load->model('Table_usage_session_model');

        // Require login for all report methods
        require_login();
        $this->check_access();
    }

    /**
     * Sales Report
     */
    public function sales()
    {
        $data['page_title'] = 'Sales Report';

        // Get sales statistics
        $data['total_sales'] = $this->get_total_sales();
        $data['total_orders'] = $this->get_total_orders();
        $data['average_order'] = $this->get_average_order_value();
        $data['payment_methods'] = $this->get_payment_methods();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('reports/sales', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Meal Sales Performance Report
     */
    public function inventory()
    {
        $data['page_title'] = 'Top Selling Meals';

        $this->load->view('layouts/base', [
            'content' => $this->load->view('reports/inventory', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Stock/Inventory Report
     */
    public function stock()
    {
        $data['page_title'] = 'Inventory Management';

        $this->load->view('layouts/base', [
            'content' => $this->load->view('reports/stock', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Expenses Report
     */
    public function expenses()
    {
        $data['page_title'] = 'Expenses Report';

        // Get expense categories for filtering
        $data['categories'] = $this->db
            ->select('*')
            ->from('expense_categories')
            ->where('status', 'active')
            ->where('deleted_at', NULL)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('reports/expenses', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Get total sales
     */
    private function get_total_sales()
    {
        $result = $this->db
            ->select_sum('total_amount')
            ->from('orders')
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();

        return $result['total_amount'] ?? 0;
    }

    /**
     * Get total orders
     */
    private function get_total_orders()
    {
        return $this->db
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL)
            ->count_all_results('orders');
    }

    /**
     * Get average order value
     */
    private function get_average_order_value()
    {
        $result = $this->db
            ->select_avg('total_amount')
            ->from('orders')
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();

        return $result['total_amount'] ?? 0;
    }

    /**
     * Get payment methods breakdown
     */
    private function get_payment_methods()
    {
        return $this->db
            ->select('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->from('orders')
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL)
            ->group_by('payment_method')
            ->order_by('total', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Get total products
     */
    private function get_total_products()
    {
        return $this->db
            ->where('deleted_at', NULL)
            ->count_all_results('products');
    }

    /**
     * Get total quantity in stock
     */
    private function get_total_quantity()
    {
        $result = $this->db
            ->select_sum('stock')
            ->from('products')
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();

        return $result['stock'] ?? 0;
    }

    /**
     * Get low stock items
     */
    private function get_low_stock_items()
    {
        return $this->db
            ->select('id, name, stock, min_stock_level')
            ->from('products')
            ->where('stock <=', 10)
            ->where('deleted_at', NULL)
            ->order_by('stock', 'ASC')
            ->limit(10)
            ->get()
            ->result_array();
    }

    /**
     * Get sales data via AJAX
     */
    public function get_sales_data()
    {
        header('Content-Type: application/json');

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $payment_method = $this->input->get('payment_method');

        // Get orders
        $orders_query = $this->db
            ->select('o.*, COUNT(oi.id) as item_count')
            ->from('orders o')
            ->join('order_items oi', 'o.id = oi.order_id', 'left')
            ->where('o.order_status', 'completed')
            ->where('o.deleted_at', NULL);

        if ($start_date) {
            $orders_query->where('DATE(o.created_at) >=', $start_date);
        }

        if ($end_date) {
            $orders_query->where('DATE(o.created_at) <=', $end_date);
        }

        if ($payment_method) {
            $orders_query->where('o.payment_method', $payment_method);
        }

        $orders = $orders_query->group_by('o.id')->order_by('o.created_at', 'DESC')->get()->result_array();

        // Get payment method breakdown
        $payment_query = $this->db
            ->select('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->from('orders')
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL);

        if ($start_date) {
            $payment_query->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $payment_query->where('DATE(created_at) <=', $end_date);
        }

        if ($payment_method) {
            $payment_query->where('payment_method', $payment_method);
        }

        $payment_methods = $payment_query->group_by('payment_method')->get()->result_array();

        // Get daily sales
        $daily_query = $this->db
            ->select('DATE(created_at) as date, SUM(total_amount) as total')
            ->from('orders')
            ->where('order_status', 'completed')
            ->where('deleted_at', NULL);

        if ($start_date) {
            $daily_query->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $daily_query->where('DATE(created_at) <=', $end_date);
        }

        if ($payment_method) {
            $daily_query->where('payment_method', $payment_method);
        }

        $daily_sales = $daily_query->group_by('DATE(created_at)')->order_by('date', 'ASC')->get()->result_array();

        // Calculate totals
        $total_sales = array_sum(array_column($orders, 'total_amount'));
        $total_orders = count($orders);
        $average_order = $total_orders > 0 ? $total_sales / $total_orders : 0;

        echo json_encode([
            'total_sales' => $total_sales,
            'total_orders' => $total_orders,
            'average_order' => $average_order,
            'payment_methods' => $payment_methods,
            'daily_sales' => $daily_sales,
            'orders' => $orders
        ]);
    }

    /**
     * Meal Sales Performance Report - Get data via AJAX
     */
    public function get_product_performance_data()
    {
        header('Content-Type: application/json');

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $search = $this->input->get('search');

        // Build query for top meals by revenue
        $this->db->select('m.id, m.name, m.selling_price, COUNT(oi.id) as total_sold, COALESCE(SUM(oi.quantity), 0) as total_quantity, COALESCE(SUM(oi.total_price), 0) as total_revenue', FALSE)
            ->from('meals m')
            ->join('order_items oi', 'm.id = oi.meal_id', 'left')
            ->join('orders o', 'oi.order_id = o.id', 'left')
            ->where('m.deleted_at', NULL)
            ->where('(oi.id IS NOT NULL AND o.order_status = "completed" AND o.deleted_at IS NULL)', NULL, FALSE);

        if ($start_date) {
            $this->db->where('DATE(o.created_at) >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('DATE(o.created_at) <=', $end_date);
        }

        if ($search) {
            $this->db->like('m.name', $search);
        }

        $top_meals = $this->db
            ->group_by('m.id')
            ->order_by('total_revenue', 'DESC')
            ->limit(10)
            ->get()
            ->result_array();

        // Reset query builder
        $this->db->reset_query();

        // Get revenue breakdown by meal
        $this->db->select('m.id, m.name, COALESCE(SUM(oi.total_price), 0) as revenue', FALSE)
            ->from('meals m')
            ->join('order_items oi', 'm.id = oi.meal_id', 'left')
            ->join('orders o', 'oi.order_id = o.id', 'left')
            ->where('m.deleted_at', NULL)
            ->where('(oi.id IS NOT NULL AND o.order_status = "completed" AND o.deleted_at IS NULL)', NULL, FALSE);

        if ($start_date) {
            $this->db->where('DATE(o.created_at) >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('DATE(o.created_at) <=', $end_date);
        }

        if ($search) {
            $this->db->like('m.name', $search);
        }

        $by_meal = $this->db->group_by('m.id')->order_by('revenue', 'DESC')->get()->result_array();

        // Calculate performance metrics
        $total_revenue = array_sum(array_column($top_meals, 'total_revenue'));
        $total_items_sold = array_sum(array_column($top_meals, 'total_quantity'));
        $avg_revenue_per_meal = count($top_meals) > 0 ? $total_revenue / count($top_meals) : 0;

        echo json_encode([
            'top_products' => $top_meals,
            'by_product' => $by_meal,
            'total_revenue' => $total_revenue,
            'total_items_sold' => $total_items_sold,
            'avg_revenue_per_product' => $avg_revenue_per_meal,
            'unique_products' => count($top_meals)
        ]);
    }

    /**
     * Get inventory data via AJAX
     */
    public function get_inventory_data()
    {
        header('Content-Type: application/json');

        $search = $this->input->get('search');
        $filter = $this->input->get('filter'); // all, low_stock, critical

        // Base query
        $this->db->select('id, name, sku, stock, min_stock_level, max_stock_level, cost_price, status')
            ->from('products')
            ->where('deleted_at', NULL);

        // Apply search filter
        if ($search) {
            $this->db->like('name', $search);
        }

        // Apply stock status filter
        if ($filter == 'low_stock') {
            $this->db->where('stock <=', 'min_stock_level', FALSE);
            $this->db->where('stock > 0');
        } elseif ($filter == 'critical') {
            $this->db->where('stock <=', 0);
        }

        $products = $this->db
            ->order_by('stock', 'ASC')
            ->get()
            ->result_array();

        // Get stock movements for trend analysis
        $this->db->reset_query();
        $stock_movements = $this->db
            ->select('product_id, type, COUNT(*) as count, DATE(created_at) as date')
            ->from('stock_movements')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
            ->group_by('product_id, type, DATE(created_at)')
            ->get()
            ->result_array();

        // Calculate metrics
        $total_products = count($products);
        $low_stock = 0;
        $critical_stock = 0;
        $total_cost = 0;

        foreach ($products as $product) {
            $total_cost += $product['cost_price'] * $product['stock'];
            if ($product['stock'] <= 0) {
                $critical_stock++;
            } elseif ($product['stock'] <= $product['min_stock_level']) {
                $low_stock++;
            }
        }

        echo json_encode([
            'products' => $products,
            'stock_movements' => $stock_movements,
            'total_products' => $total_products,
            'low_stock_count' => $low_stock,
            'critical_stock_count' => $critical_stock,
            'total_inventory_value' => $total_cost
        ]);
    }

    /**
     * Table Usage Report
     */
    public function table_usage()
    {
        $data['page_title'] = 'Table Usage Report';
        $data['tables'] = $this->Table_model->get_all();
        $data['sections'] = $this->Table_model->get_distinct_sections();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('reports/table_usage', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * AJAX: Get table usage data
     */
    public function get_table_usage_data()
    {
        header('Content-Type: application/json');

        $start_date = $this->input->post('start_date') ?: date('Y-m-d', strtotime('-7 days'));
        $end_date = $this->input->post('end_date') ?: date('Y-m-d');
        $table_id = $this->input->post('table_id') ?: null;
        $section = $this->input->post('section') ?: null;

        // Ensure dates include full day
        $start_date .= ' 00:00:00';
        $end_date .= ' 23:59:59';

        $data = [
            'summary' => $this->Table_usage_session_model->get_summary($start_date, $end_date, $table_id, $section),
            'most_used' => $this->Table_usage_session_model->get_most_used_tables($start_date, $end_date, 10, $section),
            'peak_hours' => $this->Table_usage_session_model->get_peak_hours($start_date, $end_date, $table_id, $section),
            'table_details' => $this->Table_usage_session_model->get_all_table_stats($start_date, $end_date, $section)
        ];

        echo json_encode($data);
    }

    /**
     * AJAX: Get table detail history
     */
    public function get_table_detail($table_id)
    {
        header('Content-Type: application/json');

        $sessions = $this->Table_usage_session_model->get_table_sessions($table_id, 30);
        echo json_encode($sessions);
    }

    private function check_access()
    {
        // Admins and managers can access reports
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to view reports');
            redirect('dashboard');
        }
    }
}
?>
