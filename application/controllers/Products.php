<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('roles');
        $this->load->model('Product_model');
        $this->load->model('Stock_movements_model');

        // Require login for all product methods
        require_login();
    }

    public function index()
    {
        $this->check_access();

        $data['page_title'] = 'Products';

        // Get filter parameters
        $search = $this->input->get('search', true);
        $status = $this->input->get('status', true);
        $sort_by = $this->input->get('sort_by', true) ?: 'created_at';
        $sort_order = $this->input->get('sort_order', true) ?: 'DESC';
        $page = (int)$this->input->get('page', true) ?: 1;

        // Store filters in data for view
        $data['filters'] = [
            'search' => $search,
            'status' => $status,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order,
        ];

        // Build query parameters
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($status) && $status != 'all') {
            $filters['status'] = $status;
        }

        // Pagination configuration
        $per_page = 15;
        $total_rows = $this->Product_model->count_products($filters);
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('products');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;

        // Pagination styling
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="flex items-center justify-center space-x-2 mt-6">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="bg-red-600 text-white px-3 py-2 rounded-lg">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;

        // Apply pagination
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // Get products with filters and pagination
        $data['products'] = $this->Product_model->get_filtered(
            $filters,
            $per_page,
            $offset,
            $sort_by,
            $sort_order
        );

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/index', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function add()
    {
        $this->check_access();

        $data['page_title'] = 'Add Product';

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/add', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function create()
    {
        $this->check_access();

        $product_data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'sku' => $this->input->post('sku'),
            'barcode' => $this->input->post('barcode'),
            'cost_price' => $this->input->post('cost_price'),
            'selling_price' => $this->input->post('selling_price'),
            'stock' => $this->input->post('stock'),
            'min_stock_level' => $this->input->post('min_stock_level'),
            'max_stock_level' => $this->input->post('max_stock_level'),
            'unit' => $this->input->post('unit'),
            'status' => 'active'
        ];

        // Auto-generate barcode if not provided
        if (empty($product_data['barcode'])) {
            $product_data['barcode'] = $this->generate_barcode_value();
        }

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->upload_product_image();
            if ($image_name) {
                $product_data['image'] = $image_name;
            }
        }

        if ($this->Product_model->create($product_data)) {
            $this->session->set_flashdata('success', 'Product created successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to create product');
        }

        redirect('products');
    }

    public function edit($id)
    {
        $this->check_access();

        $data['page_title'] = 'Edit Product';
        $data['product'] = $this->Product_model->get_by_id($id);

        if (!$data['product']) {
            $this->session->set_flashdata('error', 'Product not found');
            redirect('products');
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/edit', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function update($id)
    {
        $this->check_access();

        $product_data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'sku' => $this->input->post('sku'),
            'barcode' => $this->input->post('barcode'),
            'cost_price' => $this->input->post('cost_price'),
            'selling_price' => $this->input->post('selling_price'),
            'stock' => $this->input->post('stock'),
            'min_stock_level' => $this->input->post('min_stock_level'),
            'max_stock_level' => $this->input->post('max_stock_level'),
            'unit' => $this->input->post('unit'),
            'status' => $this->input->post('status')
        ];

        // Auto-generate barcode if not provided
        if (empty($product_data['barcode'])) {
            $product_data['barcode'] = $this->generate_barcode_value();
        }

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
            $image_name = $this->upload_product_image();
            if ($image_name) {
                // Delete old image if exists
                $old_product = $this->Product_model->get_by_id($id);
                if ($old_product && !empty($old_product['image'])) {
                    $old_path = FCPATH . 'upload/products/' . $old_product['image'];
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
                $product_data['image'] = $image_name;
            }
        }

        if ($this->Product_model->update($id, $product_data)) {
            $this->session->set_flashdata('success', 'Product updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update product');
        }

        redirect('products');
    }

    public function delete($id)
    {
        $this->check_access();

        // Delete image if exists
        $product = $this->Product_model->get_by_id($id);
        if ($product && !empty($product['image'])) {
            $image_path = FCPATH . 'upload/products/' . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if ($this->Product_model->delete($id)) {
            $this->session->set_flashdata('success', 'Product deleted successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete product');
        }

        redirect('products');
    }

    public function export()
    {
        $this->check_access();

        // Get filter parameters
        $search = $this->input->get('search', true);
        $status = $this->input->get('status', true);

        // Build query parameters
        $filters = [];
        if (!empty($search)) {
            $filters['search'] = $search;
        }
        if (!empty($status) && $status != 'all') {
            $filters['status'] = $status;
        }

        // Get all products matching filters (no pagination for export)
        $products = $this->Product_model->get_filtered($filters, 9999, 0, 'created_at', 'DESC');

        // Generate CSV
        $csv_data = $this->generate_csv($products);

        // Send as download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="products_' . date('Y-m-d_H-i-s') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $csv_data;
        exit;
    }

    public function checkin_list()
    {
        $this->check_access();

        $data['page_title'] = 'Check-In History';

        // Get filter parameters
        $search = $this->input->get('search', true);
        $date_from = $this->input->get('date_from', true);
        $date_to = $this->input->get('date_to', true);
        $page = (int)$this->input->get('page', true) ?: 1;

        $data['filters'] = [
            'search' => $search,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ];

        // Build filter array for model
        $filters = [];
        if (!empty($search)) {
            $filters['product_search'] = $search;
        }
        if (!empty($date_from)) {
            $filters['date_from'] = $date_from;
        }
        if (!empty($date_to)) {
            $filters['date_to'] = $date_to;
        }

        // Pagination configuration
        $per_page = 15;
        $total_rows = $this->Stock_movements_model->count_grouped_checkins($filters);
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('products/checkin_list');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;

        // Pagination styling
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="flex items-center justify-center space-x-2 mt-6">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="bg-green-600 text-white px-3 py-2 rounded-lg">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // Get grouped checkins by transaction_id
        $data['checkins'] = $this->Stock_movements_model->get_grouped_checkins($filters, $per_page, $offset);
        $data['total_checkins'] = $total_rows;
        $data['total_units'] = $this->Stock_movements_model->get_total_units('in', $filters);
        $data['latest_checkin'] = $this->Stock_movements_model->get_latest_date('in', $filters);

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/checkin_list', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function checkout_list()
    {
        $this->check_access();

        $data['page_title'] = 'Checkout History';

        // Get filter parameters
        $search = $this->input->get('search', true);
        $reason = $this->input->get('reason', true);
        $date_from = $this->input->get('date_from', true);
        $date_to = $this->input->get('date_to', true);
        $page = (int)$this->input->get('page', true) ?: 1;

        $data['filters'] = [
            'search' => $search,
            'reason' => $reason,
            'date_from' => $date_from,
            'date_to' => $date_to,
        ];

        // Build filter array for model
        $filters = [];
        if (!empty($search)) {
            $filters['product_search'] = $search;
        }
        if (!empty($reason)) {
            $filters['reason'] = $reason;
        }
        if (!empty($date_from)) {
            $filters['date_from'] = $date_from;
        }
        if (!empty($date_to)) {
            $filters['date_to'] = $date_to;
        }

        // Pagination configuration
        $per_page = 15;
        $total_rows = $this->Stock_movements_model->count_grouped_checkouts($filters);
        $offset = ($page - 1) * $per_page;

        $config['base_url'] = base_url('products/checkout_list');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = 'page';
        $config['use_page_numbers'] = true;

        // Pagination styling
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="flex items-center justify-center space-x-2 mt-6">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="bg-red-600 text-white px-3 py-2 rounded-lg">';
        $config['cur_tag_close'] = '</span></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_links'] = 5;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        // Get grouped checkouts by transaction_id
        $data['checkouts'] = $this->Stock_movements_model->get_grouped_checkouts($filters, $per_page, $offset);
        $data['total_checkouts'] = $total_rows;
        $data['total_units'] = $this->Stock_movements_model->get_total_units('out', $filters);
        $data['waste_units'] = $this->Stock_movements_model->get_waste_units($filters);
        $data['latest_checkout'] = $this->Stock_movements_model->get_latest_date('out', $filters);

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/checkout_list', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    private function upload_product_image()
    {
        $config['upload_path'] = FCPATH . 'upload/products/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'product_' . uniqid() . '_' . time();

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('image')) {
            return $this->upload->data('file_name');
        } else {
            $this->session->set_flashdata('warning', 'Image upload failed: ' . $this->upload->display_errors('', ''));
            return false;
        }
    }

    private function upload_receipt()
    {
        $config['upload_path'] = FCPATH . 'upload/receipts/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
        $config['max_size'] = 5120; // 5MB
        $config['file_name'] = 'receipt_' . uniqid() . '_' . time();

        // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('receipt')) {
            return $this->upload->data('file_name');
        } else {
            $this->session->set_flashdata('warning', 'Receipt upload failed: ' . $this->upload->display_errors('', ''));
            return false;
        }
    }

    private function generate_csv($products)
    {
        $output = fopen('php://memory', 'r+');

        // Header row
        fputcsv($output, [
            'ID',
            'Product Name',
            'SKU',
            'Barcode',
            'Cost Price (PKR)',
            'Selling Price (PKR)',
            'Current Stock',
            'Min Stock',
            'Max Stock',
            'Unit',
            'Status',
            'Created Date'
        ]);

        // Data rows
        foreach ($products as $product) {
            fputcsv($output, [
                $product['id'],
                $product['name'],
                $product['sku'],
                $product['barcode'],
                $product['cost_price'],
                $product['selling_price'],
                $product['stock'],
                $product['min_stock_level'],
                $product['max_stock_level'],
                $product['unit'],
                $product['status'],
                $product['created_at']
            ]);
        }

        rewind($output);
        $csv_data = stream_get_contents($output);
        fclose($output);
        return $csv_data;
    }

    public function checkin()
    {
        $this->check_access();

        $data['page_title'] = 'Product Check-In';
        $data['products'] = $this->Product_model->get_all();
        $data['recent_checkins'] = $this->Stock_movements_model->get_recent('in', 5);

        // Handle POST request
        if ($this->input->post()) {
            $cart_data = $this->input->post('cart_data');
            $reference = $this->input->post('reference');
            $notes = $this->input->post('notes');

            // Parse cart data
            $cart = json_decode($cart_data, true);
            if (empty($cart) || !is_array($cart)) {
                $this->session->set_flashdata('error', 'Please add at least one product to the cart');
            } else {
                // Handle file upload
                $receipt_file = null;
                if (!empty($_FILES['receipt']['name'])) {
                    $receipt_file = $this->upload_receipt();
                }

                $success_count = 0;
                $error_count = 0;
                $error_messages = [];

                // Generate unique transaction ID for this batch
                $transaction_id = 'CHK-IN-' . date('YmdHis') . '-' . uniqid();

                // Process each item in cart
                foreach ($cart as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $unit_cost = isset($item['unit_cost']) ? $item['unit_cost'] : null;
                    $total_cost = isset($item['total_cost']) ? $item['total_cost'] : null;
                    $supplier = isset($item['supplier']) && !empty($item['supplier']) ? $item['supplier'] : null;

                    $product = $this->Product_model->get_by_id($product_id);
                    if (!$product) {
                        $error_count++;
                        $error_messages[] = 'Product not found: ' . htmlspecialchars($item['product_name']);
                        continue;
                    }

                    // Update stock
                    $new_stock = $product['stock'] + $quantity;
                    if ($this->Product_model->update($product_id, ['stock' => $new_stock])) {
                        // Create stock movement record
                        $movement_notes = $notes . ($receipt_file ? ' [Receipt: ' . $receipt_file . ']' : '');
                        if (!empty($reference)) {
                            $movement_notes = 'PO: ' . $reference . ' | ' . $movement_notes;
                        }

                        $movement_data = [
                            'product_id' => $product_id,
                            'type' => 'in',
                            'quantity' => $quantity,
                            'unit_cost' => $unit_cost,
                            'total_cost' => $total_cost,
                            'supplier' => $supplier,
                            'reference_type' => 'purchase_order',
                            'reference_id' => null,
                            'transaction_id' => $transaction_id,
                            'notes' => $movement_notes,
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $this->Stock_movements_model->create($movement_data);
                        $success_count++;
                    } else {
                        $error_count++;
                        $error_messages[] = 'Failed to update: ' . htmlspecialchars($item['product_name']);
                    }
                }

                // Set flashdata based on results
                if ($success_count > 0) {
                    $message = 'Check-in successful! Added ' . $success_count . ' product' . ($success_count > 1 ? 's' : '');
                    if ($error_count > 0) {
                        $message .= ' (' . $error_count . ' failed)';
                    }
                    $this->session->set_flashdata('success', $message);
                } elseif ($error_count > 0) {
                    $this->session->set_flashdata('error', 'Check-in failed: ' . implode(', ', $error_messages));
                }

                redirect('products/checkin_list');
            }
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/checkin', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function checkout()
    {
        $this->check_access();

        $data['page_title'] = 'Product Checkout';
        $data['products'] = $this->Product_model->get_all();
        $data['recent_checkouts'] = $this->Stock_movements_model->get_recent('out', 5);

        // Handle POST request
        if ($this->input->post()) {
            $cart_data = $this->input->post('cart_data');
            $notes = $this->input->post('notes');

            // Parse cart data
            $cart = json_decode($cart_data, true);
            if (empty($cart) || !is_array($cart)) {
                $this->session->set_flashdata('error', 'Please add at least one product to the cart');
            } else {
                // Handle file upload
                $receipt_file = null;
                if (!empty($_FILES['receipt']['name'])) {
                    $receipt_file = $this->upload_receipt();
                }

                $success_count = 0;
                $error_count = 0;
                $error_messages = [];

                // Generate unique transaction ID for this batch
                $transaction_id = 'CHK-OUT-' . date('YmdHis') . '-' . uniqid();

                // Process each item in cart
                foreach ($cart as $item) {
                    $product_id = $item['product_id'];
                    $quantity = $item['quantity'];
                    $reason = $item['reason'];

                    $product = $this->Product_model->get_by_id($product_id);
                    if (!$product) {
                        $error_count++;
                        $error_messages[] = 'Product not found: ' . htmlspecialchars($item['product_name']);
                        continue;
                    }

                    if ($product['stock'] < $quantity) {
                        $error_count++;
                        $error_messages[] = 'Insufficient stock: ' . htmlspecialchars($item['product_name']) . ' (Available: ' . $product['stock'] . ')';
                        continue;
                    }

                    // Update stock
                    $new_stock = $product['stock'] - $quantity;
                    if ($this->Product_model->update($product_id, ['stock' => $new_stock])) {
                        // Create stock movement record
                        $movement_notes = $notes . ($receipt_file ? ' [Receipt: ' . $receipt_file . ']' : '');

                        $movement_data = [
                            'product_id' => $product_id,
                            'type' => 'out',
                            'quantity' => $quantity,
                            'reference_type' => $reason,
                            'reference_id' => null,
                            'transaction_id' => $transaction_id,
                            'notes' => $movement_notes,
                            'created_by' => $this->session->userdata('user_id'),
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $this->Stock_movements_model->create($movement_data);
                        $success_count++;
                    } else {
                        $error_count++;
                        $error_messages[] = 'Failed to update: ' . htmlspecialchars($item['product_name']);
                    }
                }

                // Set flashdata based on results
                if ($success_count > 0) {
                    $message = 'Checkout successful! Removed ' . $success_count . ' product' . ($success_count > 1 ? 's' : '');
                    if ($error_count > 0) {
                        $message .= ' (' . $error_count . ' failed)';
                    }
                    $this->session->set_flashdata('success', $message);
                } elseif ($error_count > 0) {
                    $this->session->set_flashdata('error', 'Checkout failed: ' . implode(', ', $error_messages));
                }

                redirect('products/checkout_list');
            }
        }

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/checkout', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function transaction_details($transaction_id = null)
    {
        $this->check_access();

        // If transaction_id is not provided in the parameter, try to get it from URI segment
        if (empty($transaction_id)) {
            $transaction_id = $this->uri->segment(3);
        }

        // Decode URL-encoded transaction ID
        if (!empty($transaction_id)) {
            $transaction_id = urldecode($transaction_id);
        }

        if (empty($transaction_id)) {
            $this->session->set_flashdata('error', 'Transaction ID not provided');
            redirect('products/checkin_list');
            return;
        }

        // Get transaction details
        $items = $this->Stock_movements_model->get_transaction_items($transaction_id);

        if (empty($items)) {
            $this->session->set_flashdata('error', 'Transaction not found');
            redirect('products/checkin_list');
        }

        $first_item = $items[0];
        $type = $first_item['type'];

        $data['page_title'] = ucfirst($type) === 'In' ? 'Check-In Details' : 'Checkout Details';
        $data['transaction_id'] = $transaction_id;
        $data['type'] = $type;
        $data['items'] = $items;
        $data['total_quantity'] = array_sum(array_column($items, 'quantity'));
        $data['created_by'] = $first_item['created_by'];
        $data['created_at'] = $first_item['created_at'];
        $data['notes'] = $first_item['notes'];
        $data['reference_type'] = $first_item['reference_type'];

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/transaction_details', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function delete_transaction($transaction_id = null)
    {
        $this->check_access();

        // If transaction_id is not provided in the parameter, try to get it from URI segment
        if (empty($transaction_id)) {
            $transaction_id = $this->uri->segment(3);
        }

        // Decode URL-encoded transaction ID
        if (!empty($transaction_id)) {
            $transaction_id = urldecode($transaction_id);
        }

        if (empty($transaction_id)) {
            $this->session->set_flashdata('error', 'Transaction ID not provided');
            redirect('products/checkin_list');
            return;
        }

        // Get transaction details before deleting
        $items = $this->Stock_movements_model->get_transaction_items($transaction_id);

        if (empty($items)) {
            $this->session->set_flashdata('error', 'Transaction not found');
            redirect('products/checkin_list');
            return;
        }

        $first_item = $items[0];
        $type = $first_item['type'];

        // Start database transaction
        $this->db->trans_start();

        // Reverse stock quantities for each item
        foreach ($items as $item) {
            $product = $this->Product_model->get_by_id($item['product_id']);

            if ($product) {
                $new_quantity = $product['stock'];

                // Reverse the stock movement
                if ($item['type'] === 'in') {
                    // If it was a check-in, subtract the quantity
                    $new_quantity -= $item['quantity'];
                } else {
                    // If it was a checkout, add the quantity back
                    $new_quantity += $item['quantity'];
                }

                // Update product quantity
                $this->Product_model->update($item['product_id'], [
                    'stock' => $new_quantity
                ]);
            }
        }

        // Delete all stock movement records for this transaction
        $this->db->where('transaction_id', $transaction_id);
        $this->db->delete('stock_movements');

        // Complete database transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to delete transaction. Please try again.');
        } else {
            $this->session->set_flashdata('success', 'Transaction deleted successfully and stock quantities have been reversed.');
        }

        // Redirect to appropriate list based on transaction type
        if ($type === 'in') {
            redirect('products/checkin_list');
        } else {
            redirect('products/checkout_list');
        }
    }

    public function edit_transaction($transaction_id = null)
    {
        $this->check_access();

        // If transaction_id is not provided in the parameter, try to get it from URI segment
        if (empty($transaction_id)) {
            $transaction_id = $this->uri->segment(3);
        }

        // Decode URL-encoded transaction ID
        if (!empty($transaction_id)) {
            $transaction_id = urldecode($transaction_id);
        }

        if (empty($transaction_id)) {
            $this->session->set_flashdata('error', 'Transaction ID not provided');
            redirect('products/checkin_list');
            return;
        }

        // Get transaction details
        $items = $this->Stock_movements_model->get_transaction_items($transaction_id);

        if (empty($items)) {
            $this->session->set_flashdata('error', 'Transaction not found');
            redirect('products/checkin_list');
            return;
        }

        $first_item = $items[0];
        $type = $first_item['type'];

        $data['page_title'] = 'Edit ' . (ucfirst($type) === 'In' ? 'Check-In' : 'Checkout');
        $data['transaction_id'] = $transaction_id;
        $data['type'] = $type;
        $data['items'] = $items;
        $data['created_by'] = $first_item['created_by'];
        $data['created_at'] = $first_item['created_at'];
        $data['notes'] = $first_item['notes'];

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/edit_transaction', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function update_transaction($transaction_id = null)
    {
        $this->check_access();

        // Decode URL-encoded transaction ID
        if (!empty($transaction_id)) {
            $transaction_id = urldecode($transaction_id);
        }

        if (empty($transaction_id)) {
            $this->session->set_flashdata('error', 'Transaction ID not provided');
            redirect('products/checkin_list');
            return;
        }

        // Get existing transaction details
        $items = $this->Stock_movements_model->get_transaction_items($transaction_id);

        if (empty($items)) {
            $this->session->set_flashdata('error', 'Transaction not found');
            redirect('products/checkin_list');
            return;
        }

        $first_item = $items[0];
        $type = $first_item['type'];

        // Get form data
        $updated_items = $this->input->post('items');
        $notes = $this->input->post('notes');

        if (empty($updated_items)) {
            $this->session->set_flashdata('error', 'No items provided');
            redirect('products/edit_transaction/' . urlencode($transaction_id));
            return;
        }

        // Start database transaction
        $this->db->trans_start();

        // Process each updated item
        foreach ($updated_items as $item_data) {
            $movement_id = $item_data['movement_id'];
            $product_id = $item_data['product_id'];
            $original_quantity = $item_data['original_quantity'];
            $new_quantity = $item_data['quantity'];

            // Get product
            $product = $this->Product_model->get_by_id($product_id);

            if ($product) {
                // Calculate the difference
                $quantity_diff = $new_quantity - $original_quantity;

                if ($quantity_diff != 0) {
                    // Update product stock
                    $new_stock = $product['stock'];

                    if ($type === 'in') {
                        // For check-ins, add the difference
                        $new_stock += $quantity_diff;
                    } else {
                        // For checkouts, subtract the difference
                        $new_stock -= $quantity_diff;
                    }

                    $this->Product_model->update($product_id, [
                        'stock' => $new_stock
                    ]);
                }
            }

            // Update stock movement record
            $update_data = [
                'quantity' => $new_quantity,
                'notes' => $notes
            ];

            // For check-ins, also update price and supplier if provided
            if ($type === 'in') {
                $unit_cost = !empty($item_data['unit_cost']) ? $item_data['unit_cost'] : null;
                $supplier = !empty($item_data['supplier']) ? $item_data['supplier'] : null;

                if ($unit_cost !== null) {
                    $update_data['unit_cost'] = $unit_cost;
                    $update_data['total_cost'] = $new_quantity * $unit_cost;
                } else {
                    $update_data['unit_cost'] = null;
                    $update_data['total_cost'] = null;
                }

                $update_data['supplier'] = $supplier;
            }

            // Update the stock movement
            $this->db->where('id', $movement_id);
            $this->db->update('stock_movements', $update_data);
        }

        // Complete database transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to update transaction. Please try again.');
            redirect('products/edit_transaction/' . urlencode($transaction_id));
        } else {
            $this->session->set_flashdata('success', 'Transaction updated successfully and stock quantities have been adjusted.');
            redirect('products/transaction_details/' . urlencode($transaction_id));
        }
    }

    public function generate_barcode($product_id = null)
    {
        $this->check_access();

        if (empty($product_id)) {
            $product_id = $this->uri->segment(3);
        }

        if (empty($product_id)) {
            $this->session->set_flashdata('error', 'Product ID not provided');
            redirect('products');
            return;
        }

        $product = $this->Product_model->get_by_id($product_id);

        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found');
            redirect('products');
            return;
        }

        $data['page_title'] = 'Generate Barcode - ' . $product['name'];
        $data['product'] = $product;
        $data['barcode'] = $product['barcode'] ?? $product['sku'];

        $this->load->view('layouts/base', [
            'content' => $this->load->view('inventory/products/generate_barcode', $data, true),
            'page_title' => $data['page_title'],
            'user_name' => $this->session->userdata('user_name'),
            'user_email' => $this->session->userdata('user_email'),
            'user_initial' => $this->session->userdata('user_initial'),
        ]);
    }

    public function download_barcode_pdf($product_id = null)
    {
        $this->check_access();

        if (empty($product_id)) {
            $product_id = $this->uri->segment(3);
        }

        if (empty($product_id)) {
            $this->session->set_flashdata('error', 'Product ID not provided');
            redirect('products');
            return;
        }

        $product = $this->Product_model->get_by_id($product_id);

        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found');
            redirect('products');
            return;
        }

        // Get parameters from POST
        $pages = (int)$this->input->post('quantity') ?: 1;
        $barcode_format = $this->input->post('barcode_format') ?: 'CODE128';
        $barcode_value = $this->input->post('barcode_value') ?: $product['sku'];

        // Sanitize barcode value
        $barcode_value = trim($barcode_value);
        $barcode_value = preg_replace('/[^A-Za-z0-9\-]/', '', $barcode_value);
        $barcode_value = substr($barcode_value, 0, 30);

        if (empty($barcode_value)) {
            $barcode_value = $product['sku'];
        }

        // Limit pages to prevent abuse (each page = 50 labels)
        $pages = min(max($pages, 1), 10);
        $quantity = $pages * 50;

        // Load TCPDF library
        require_once APPPATH . '../vendor/autoload.php';

        // Create PDF
        $pdf = new \TCPDF('P', 'mm', 'A4');
        $pdf->SetCreator('POS System');
        $pdf->SetAuthor('POS System');
        $pdf->SetTitle('Barcode Labels');
        $pdf->SetSubject('Barcode Labels for ' . $product['name']);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();

        // Label grid: 5 columns x 10 rows on A4 page (210mm x 297mm)
        $label_width = 42;   // 210mm / 5
        $label_height = 29.7; // 297mm / 10
        $labels_per_page = 50;

        // Generate barcode generator instance
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

        // Map format
        $format_map = array(
            'CODE128' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128,
            'CODE39' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39,
            'EAN13' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_13,
            'UPCA' => \Picqer\Barcode\BarcodeGenerator::TYPE_UPC_A,
            'CODE11' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_11,
        );
        $format = isset($format_map[$barcode_format]) ? $format_map[$barcode_format] : \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128;

        // Generate all labels
        $label_count = 0;

        for ($i = 0; $i < $quantity; $i++) {
            // Add new page if needed
            if ($label_count > 0 && $label_count % $labels_per_page === 0) {
                $pdf->AddPage();
            }

            // Calculate position
            $position_in_page = $label_count % $labels_per_page;
            $row = (int)floor($position_in_page / 5);
            $col = $position_in_page % 5;

            $x = $col * $label_width;
            $y = $row * $label_height;

            // Draw border
            $pdf->SetDrawColor(180, 180, 180);
            $pdf->SetLineWidth(0.1);
            $pdf->Rect($x, $y, $label_width, $label_height);

            // Add SKU at top
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetXY($x + 1, $y + 1);
            $pdf->Cell($label_width - 2, 3, $product['sku'], 0, 1, 'C');

            // Add product name
            $pdf->SetFont('helvetica', '', 5);
            $pdf->SetXY($x + 1, $y + 4);
            $product_name = substr($product['name'], 0, 18);
            $pdf->Cell($label_width - 2, 2.5, $product_name, 0, 1, 'C');

            // Generate and embed barcode
            try {
                $barcode_png = $generator->getBarcode($barcode_value, $format);
                $temp_file = '/tmp/barcode_' . uniqid() . '.png';
                file_put_contents($temp_file, $barcode_png);

                // Embed barcode image
                $pdf->Image($temp_file, $x + 2, $y + 7, $label_width - 4, 12, 'PNG');

                // Clean up
                if (file_exists($temp_file)) {
                    unlink($temp_file);
                }
            } catch (Exception $e) {
                // Skip if barcode generation fails
            }

            // Add barcode value at bottom
            $pdf->SetFont('helvetica', 'B', 5);
            $pdf->SetXY($x + 1, $y + $label_height - 3);
            $pdf->Cell($label_width - 2, 2.5, $barcode_value, 0, 0, 'C');

            $label_count++;
        }

        // Download
        $filename = 'barcode_' . $product['sku'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
        $pdf->Output($filename, 'D');
        exit;
    }

    private function generate_barcode_value()
    {
        // Generate a unique barcode using timestamp and random number
        // Format: PROD-TIMESTAMP-RANDOM (e.g., PROD-1732721234-5A8C)
        $timestamp = time();
        $random = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        return 'PROD-' . $timestamp . '-' . $random;
    }

    private function get_recent_stock_movements($type, $limit = 10)
    {
        // This is a placeholder for demo purposes
        // In production, you would fetch from a stock_movements table
        return [];
    }

    private function check_access()
    {
        // Only admins and managers can access product management
        if (!has_any_role(array('admin', 'manager'))) {
            $this->session->set_flashdata('error', 'You do not have permission to access product management');
            redirect('dashboard');
        }
    }
}

