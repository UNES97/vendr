<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('roles');
        $this->load->model('Expense_model');
        $this->load->library('upload');

        // Require login for all expense methods
        require_login();
        $this->check_access();
    }

    /**
     * Add new expense page
     */
    public function add()
    {
        $data['page_title'] = 'Add New Expense';

        // Get all expense categories
        $data['categories'] = $this->db
            ->select('*')
            ->from('expense_categories')
            ->where('status', 'active')
            ->where('deleted_at', NULL)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('expense/add', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Manage expense categories
     */
    public function categories()
    {
        $data['page_title'] = 'Expense Categories';

        // Get all categories
        $data['categories'] = $this->db
            ->select('*')
            ->from('expense_categories')
            ->where('deleted_at', NULL)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('expense/categories', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Create expense category
     */
    public function create_category()
    {
        header('Content-Type: application/json');

        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'icon' => $this->input->post('icon'),
            'status' => 'active'
        ];

        if (!$data['name']) {
            http_response_code(400);
            echo json_encode(['error' => 'Category name is required']);
            return;
        }

        // Check if category already exists
        $exists = $this->db->where('name', $data['name'])->where('deleted_at', NULL)->get('expense_categories')->row();
        if ($exists) {
            http_response_code(400);
            echo json_encode(['error' => 'Category already exists']);
            return;
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert('expense_categories', $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Category created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create category']);
        }
    }

    /**
     * Update expense category
     */
    public function update_category($category_id)
    {
        header('Content-Type: application/json');

        $data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'icon' => $this->input->post('icon'),
            'status' => $this->input->post('status')
        ];

        if (!$data['name']) {
            http_response_code(400);
            echo json_encode(['error' => 'Category name is required']);
            return;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($this->db->update('expense_categories', $data, ['id' => $category_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Category updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update category']);
        }
    }

    /**
     * Delete expense category
     */
    public function delete_category($category_id)
    {
        header('Content-Type: application/json');

        // Check if category is being used
        $used = $this->db->where('category_id', $category_id)->get('expenses')->row();
        if ($used) {
            http_response_code(400);
            echo json_encode(['error' => 'Cannot delete category that has expenses']);
            return;
        }

        if ($this->db->update('expense_categories', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $category_id])) {
            echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete category']);
        }
    }

    /**
     * Expenses Dashboard - Display all expenses with filters
     */
    public function index()
    {
        $data['page_title'] = 'Expenses Management';

        // Get all expense categories
        $categories = $this->db
            ->select('*')
            ->from('expense_categories')
            ->where('status', 'active')
            ->where('deleted_at', NULL)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        // Get all expenses with category info
        $expenses = $this->db
            ->select('e.*, ec.name as category_name, u.name as created_by_name')
            ->from('expenses e')
            ->join('expense_categories ec', 'e.category_id = ec.id', 'left')
            ->join('users u', 'e.created_by = u.id', 'left')
            ->where('e.deleted_at', NULL)
            ->order_by('e.created_at', 'DESC')
            ->get()
            ->result_array();

        $data['categories'] = $categories;
        $data['expenses'] = $expenses;

        // Calculate totals
        $data['total_expenses'] = array_sum(array_column($expenses, 'amount'));

        // Calculate by category
        $data['category_totals'] = [];
        foreach ($categories as $cat) {
            $total = array_sum(
                array_map(function($exp) use ($cat) {
                    return ($exp['category_id'] == $cat['id']) ? $exp['amount'] : 0;
                }, $expenses)
            );
            $data['category_totals'][$cat['id']] = $total;
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('expense/index', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Get expenses via AJAX with filters
     */
    public function get_expenses()
    {
        header('Content-Type: application/json');

        $category_id = $this->input->get('category_id');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $search = $this->input->get('search');

        $query = $this->db
            ->select('e.*, ec.name as category_name, u.name as created_by_name')
            ->from('expenses e')
            ->join('expense_categories ec', 'e.category_id = ec.id', 'left')
            ->join('users u', 'e.created_by = u.id', 'left')
            ->where('e.deleted_at', NULL);

        if ($category_id) {
            $query->where('e.category_id', $category_id);
        }

        if ($start_date) {
            $query->where('DATE(e.created_at) >=', $start_date);
        }

        if ($end_date) {
            $query->where('DATE(e.created_at) <=', $end_date);
        }

        if ($search) {
            $query->where("(e.description LIKE '%{$search}%' OR e.reference_number LIKE '%{$search}%')");
        }

        $expenses = $query->order_by('e.created_at', 'DESC')->get()->result_array();

        $total = array_sum(array_column($expenses, 'amount'));

        echo json_encode([
            'expenses' => $expenses,
            'total' => $total,
            'count' => count($expenses)
        ]);
    }

    /**
     * Create new expense
     */
    public function create()
    {
        header('Content-Type: application/json');

        $data = [
            'category_id' => $this->input->post('category_id'),
            'description' => $this->input->post('description'),
            'amount' => floatval($this->input->post('amount')),
            'payment_method' => $this->input->post('payment_method'),
            'reference_number' => $this->input->post('reference_number'),
            'notes' => $this->input->post('notes'),
            'created_by' => $this->session->userdata('user_id')
        ];

        // Validate required fields
        if (!$data['category_id'] || !$data['description'] || !$data['amount']) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        if ($data['amount'] <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Amount must be greater than 0']);
            return;
        }

        // Handle file upload if provided
        $attachment_file = null;
        if (!empty($_FILES['attachment']['name'])) {
            $upload_config = [
                'upload_path' => './upload/expenses/',
                'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx',
                'max_size' => 10240, // 10MB in KB
                'encrypt_name' => TRUE
            ];

            // Create directory if it doesn't exist
            if (!is_dir('./upload/expenses/')) {
                mkdir('./upload/expenses/', 0755, true);
            }

            $this->upload->initialize($upload_config);

            if ($this->upload->do_upload('attachment')) {
                $upload_data = $this->upload->data();
                $attachment_file = $upload_data['file_name'];
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'File upload failed: ' . $this->upload->display_errors()]);
                return;
            }
        }

        $data['attachment'] = $attachment_file;

        if ($this->Expense_model->create($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Expense created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create expense']);
        }
    }

    /**
     * Get expense details via AJAX
     */
    public function get_expense($expense_id)
    {
        header('Content-Type: application/json');

        $expense = $this->db
            ->select('e.*, ec.name as category_name, u.name as created_by_name')
            ->from('expenses e')
            ->join('expense_categories ec', 'e.category_id = ec.id', 'left')
            ->join('users u', 'e.created_by = u.id', 'left')
            ->where('e.id', $expense_id)
            ->where('e.deleted_at', NULL)
            ->get()
            ->row_array();

        if (!$expense) {
            http_response_code(404);
            echo json_encode(['error' => 'Expense not found']);
            return;
        }

        echo json_encode($expense);
    }

    /**
     * Update expense
     */
    public function update($expense_id)
    {
        header('Content-Type: application/json');

        $data = [
            'category_id' => $this->input->post('category_id'),
            'description' => $this->input->post('description'),
            'amount' => floatval($this->input->post('amount')),
            'payment_method' => $this->input->post('payment_method'),
            'reference_number' => $this->input->post('reference_number'),
            'notes' => $this->input->post('notes')
        ];

        // Validate required fields
        if (!$data['category_id'] || !$data['description'] || !$data['amount']) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        if ($data['amount'] <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Amount must be greater than 0']);
            return;
        }

        if ($this->Expense_model->update($expense_id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Expense updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update expense']);
        }
    }

    /**
     * Download expense attachment
     */
    public function download_attachment($expense_id)
    {
        $expense = $this->db->select('attachment')
            ->from('expenses')
            ->where('id', $expense_id)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();

        if (!$expense || !$expense['attachment']) {
            http_response_code(404);
            echo json_encode(['error' => 'Attachment not found']);
            return;
        }

        $file_path = './upload/expenses/' . $expense['attachment'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));

            readfile($file_path);
            exit;
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'File not found']);
        }
    }

    /**
     * Delete expense (soft delete)
     */
    public function delete($expense_id)
    {
        header('Content-Type: application/json');

        if ($this->Expense_model->delete($expense_id)) {
            echo json_encode(['status' => 'success', 'message' => 'Expense deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete expense']);
        }
    }

    /**
     * Reporting Dashboard
     */
    public function reports()
    {
        $data['page_title'] = 'Expense Reports';

        // Get all categories for dropdown
        $data['categories'] = $this->db
            ->select('*')
            ->from('expense_categories')
            ->where('status', 'active')
            ->where('deleted_at', NULL)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('expense/reports', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Get expense statistics
     */
    public function get_statistics()
    {
        header('Content-Type: application/json');

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $category_id = $this->input->get('category_id');

        // Get category totals
        $categories = $this->db
            ->select('ec.id, ec.name, SUM(e.amount) as total')
            ->from('expense_categories ec')
            ->join('expenses e', 'ec.id = e.category_id', 'left')
            ->where('ec.status', 'active')
            ->where('ec.deleted_at', NULL)
            ->where('e.deleted_at', NULL);

        if ($start_date) {
            $categories->where('DATE(e.created_at) >=', $start_date);
        }

        if ($end_date) {
            $categories->where('DATE(e.created_at) <=', $end_date);
        }

        if ($category_id) {
            $categories->where('ec.id', $category_id);
        }

        $categories = $categories->group_by('ec.id')->order_by('total', 'DESC')->get()->result_array();

        // Get payment method totals
        $payment_methods = $this->db
            ->select('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->from('expenses')
            ->where('deleted_at', NULL);

        if ($start_date) {
            $payment_methods->where('DATE(created_at) >=', $start_date);
        }

        if ($end_date) {
            $payment_methods->where('DATE(created_at) <=', $end_date);
        }

        if ($category_id) {
            $payment_methods->where('category_id', $category_id);
        }

        $payment_methods = $payment_methods->group_by('payment_method')->get()->result_array();

        // Get all expenses with details
        $expenses_query = $this->db
            ->select('e.*, ec.name as category_name')
            ->from('expenses e')
            ->join('expense_categories ec', 'e.category_id = ec.id', 'left')
            ->where('e.deleted_at', NULL);

        if ($start_date) {
            $expenses_query->where('DATE(e.created_at) >=', $start_date);
        }

        if ($end_date) {
            $expenses_query->where('DATE(e.created_at) <=', $end_date);
        }

        if ($category_id) {
            $expenses_query->where('e.category_id', $category_id);
        }

        $all_expenses = $expenses_query->get()->result_array();
        $total_amount = array_sum(array_column($all_expenses, 'amount'));
        $max_amount = max(array_column($all_expenses, 'amount')) ?: 0;

        // Get top 10 expenses
        $top_expenses_query = $this->db
            ->select('e.*, ec.name as category_name')
            ->from('expenses e')
            ->join('expense_categories ec', 'e.category_id = ec.id', 'left')
            ->where('e.deleted_at', NULL);

        if ($start_date) {
            $top_expenses_query->where('DATE(e.created_at) >=', $start_date);
        }

        if ($end_date) {
            $top_expenses_query->where('DATE(e.created_at) <=', $end_date);
        }

        if ($category_id) {
            $top_expenses_query->where('e.category_id', $category_id);
        }

        $top_expenses = $top_expenses_query->order_by('e.amount', 'DESC')->limit(10)->get()->result_array();

        echo json_encode([
            'categories' => $categories,
            'payment_methods' => $payment_methods,
            'total' => $total_amount,
            'count' => count($all_expenses),
            'max_amount' => $max_amount,
            'top_expenses' => $top_expenses
        ]);
    }

    private function check_access()
    {
        // Admins and managers can access expense management
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to manage expenses');
            redirect('dashboard');
        }
    }
}
?>
