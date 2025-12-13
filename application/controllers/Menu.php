<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menu Controller
 *
 * Customer-facing menu for online ordering
 * NO AUTHENTICATION REQUIRED - Public access
 */
class Menu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('settings');
        $this->load->library('session');
        $this->load->model('Meal_model');
        $this->load->model('Category_model');
        $this->load->model('Table_model');
        // NO require_login() - public access for customers
    }

    /**
     * Display customer menu
     *
     * @param int|null $table_id Optional table ID from QR code
     */
    public function index($table_id = null)
    {
        // Check if online ordering is enabled
        if (!setting('online_ordering_enabled', '1')) {
            show_error('Online ordering is currently disabled.', 503);
        }

        $data = array();
        $data['page_title'] = 'Menu - ' . setting('restaurant_name', 'Restaurant');

        // Get table info if accessed via table QR code
        $data['table'] = null;
        $data['table_id'] = null;
        $data['order_type'] = 'takeaway'; // Default

        if ($table_id) {
            $table = $this->Table_model->get_by_id($table_id);
            if ($table && !$table['deleted_at']) {
                $data['table'] = $table;
                $data['table_id'] = $table_id;
                $data['order_type'] = 'dine-in'; // Pre-select dine-in for table orders
            }
        }

        // Get all active meal categories
        $data['categories'] = $this->Category_model->get_all('meal');

        // Get all active meals
        $filters = ['status' => 'active'];
        $data['meals'] = $this->Meal_model->get_filtered($filters, 9999, 0, 'name', 'ASC');

        // Get settings
        $data['restaurant_name'] = setting('restaurant_name', 'Restaurant');
        $data['currency'] = setting('currency', 'PKR');
        $data['tax_rate'] = setting('tax_rate', '17');
        $data['delivery_fee'] = setting('delivery_fee', '100');
        $data['minimum_delivery_order'] = setting('minimum_delivery_order', '500');
        $data['estimated_prep_time'] = setting('estimated_preparation_time', '30');

        $this->load->view('menu/index', $data);
    }

    /**
     * Get all active categories (JSON endpoint)
     */
    public function get_categories()
    {
        header('Content-Type: application/json');

        $categories = $this->Category_model->get_all('meal');

        echo json_encode($categories);
    }

    /**
     * Get meals by category (JSON endpoint)
     *
     * @param int $category_id Category ID (0 for all)
     */
    public function get_meals($category_id = 0)
    {
        header('Content-Type: application/json');

        $filters = [
            'status' => 'active',
            'category_id' => $category_id > 0 ? $category_id : null
        ];

        $meals = $this->Meal_model->get_filtered($filters, 9999, 0, 'name', 'ASC');

        // Add image URLs
        foreach ($meals as &$meal) {
            if (!empty($meal['image'])) {
                $meal['image_url'] = base_url('upload/meals/' . $meal['image']);
            } else {
                $meal['image_url'] = null;
            }
        }

        echo json_encode($meals);
    }

    /**
     * Get single meal details (JSON endpoint)
     *
     * @param int $meal_id Meal ID
     */
    public function get_meal_details($meal_id)
    {
        header('Content-Type: application/json');

        $meal = $this->Meal_model->get_by_id($meal_id);

        if (!$meal || $meal['status'] !== 'active') {
            http_response_code(404);
            echo json_encode(['error' => 'Meal not found']);
            return;
        }

        // Add image URL
        if (!empty($meal['image'])) {
            $meal['image_url'] = base_url('upload/meals/' . $meal['image']);
        } else {
            $meal['image_url'] = null;
        }

        echo json_encode($meal);
    }
}
