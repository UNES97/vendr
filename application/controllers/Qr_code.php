<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;

/**
 * QR Code Management Controller
 *
 * Generate and manage QR codes for table-based ordering
 * ADMIN/MANAGER ACCESS ONLY
 */
class Qr_code extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('auth');
        $this->load->helper('settings');
        $this->load->library('session');
        $this->load->model('Table_model');

        // Require authentication
        require_login();

        // Only admins and managers can access
        if (!has_any_role(['admin', 'manager'])) {
            $this->session->set_flashdata('error', 'Access denied. Admin or Manager role required.');
            redirect('dashboard');
        }
    }

    /**
     * QR Code management page
     */
    public function index()
    {
        $data['page_title'] = 'QR Code Management';
        $data['tables'] = $this->Table_model->get_all();

        $this->load->view('layouts/base', [
            'content' => $this->load->view('qr_code/index', $data, true),
            'page_title' => $data['page_title'],
        ]);
    }

    /**
     * Generate QR code for a specific table
     *
     * @param int $table_id Table ID
     */
    public function generate($table_id)
    {
        $table = $this->Table_model->get_by_id($table_id);

        if (!$table) {
            $this->session->set_flashdata('error', 'Table not found');
            redirect('qr-codes');
            return;
        }

        try {
            $qr_url = base_url("menu/table/{$table_id}");

            $qrCode = new QrCode($qr_url);
            $qrCode->setSize(400);
            $qrCode->setMargin(20);
            $qrCode->setWriterByName('png');
            $qrCode->setEncoding('UTF-8');
            $qrCode->setErrorCorrectionLevel(
                new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
            );

            $dir = FCPATH . 'upload/qr_codes/';
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $filename = "table_{$table_id}_" . time() . ".png";
            $filepath = $dir . $filename;

            $qrCode->writeFile($filepath);

            $this->Table_model->update_qr_code($table_id, $filename);

            $this->session->set_flashdata(
                'success',
                "QR Code generated successfully for Table {$table['table_number']}"
            );

        } catch (Exception $e) {
            $this->session->set_flashdata(
                'error',
                'Failed to generate QR code: ' . $e->getMessage()
            );
        }

        redirect('qr-codes');
    }

    /**
     * Generate QR codes for all tables
     */
    public function generate_all()
    {
        $tables = $this->Table_model->get_all();
        $generated = 0;
        $failed = 0;

        // Ensure directory exists ONCE
        $dir = FCPATH . 'upload/qr_codes/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($tables as $table) {
            try {
                // QR URL
                $qr_url = base_url("menu/table/{$table['id']}");

                // Create QR Code (v3 syntax)
                $qrCode = new QrCode($qr_url);
                $qrCode->setSize(400);
                $qrCode->setMargin(20);
                $qrCode->setWriterByName('png');
                $qrCode->setEncoding('UTF-8');
                $qrCode->setErrorCorrectionLevel(
                    new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
                );

                // Unique filename (safe in loop)
                $filename = "table_{$table['id']}_" . time() . '_' . mt_rand(1000, 9999) . ".png";
                $filepath = $dir . $filename;

                // Save QR code
                $qrCode->writeFile($filepath);

                // Update DB
                $this->Table_model->update_qr_code($table['id'], $filename);

                $generated++;

            } catch (Exception $e) {
                log_message('error', 'QR generation failed for table ID ' . $table['id'] . ': ' . $e->getMessage());
                $failed++;
            }
        }

        $this->session->set_flashdata(
            'success',
            "Generated {$generated} QR codes successfully" .
            ($failed > 0 ? " ({$failed} failed)" : "")
        );

        redirect('qr-codes');
    }

    /**
     * Download QR code image
     *
     * @param int $table_id Table ID
     */
    public function download($table_id)
    {
        $table = $this->Table_model->get_by_id($table_id);

        if (!$table || empty($table['qr_code'])) {
            $this->session->set_flashdata('error', 'QR code not found');
            redirect('qr-codes');
            return;
        }

        $filepath = FCPATH . "upload/qr_codes/{$table['qr_code']}";

        if (!file_exists($filepath)) {
            $this->session->set_flashdata('error', 'QR code file not found');
            redirect('qr-codes');
            return;
        }

        // Force download
        $this->load->helper('download');
        force_download("Table_{$table['table_number']}_QR.png", file_get_contents($filepath));
    }

    /**
     * Preview QR code (returns image)
     *
     * @param int $table_id Table ID
     */
    public function preview($table_id)
    {
        $table = $this->Table_model->get_by_id($table_id);

        if (!$table || empty($table['qr_code'])) {
            show_404();
            return;
        }

        $filepath = FCPATH . "upload/qr_codes/{$table['qr_code']}";

        if (!file_exists($filepath)) {
            show_404();
            return;
        }

        // Output image
        header('Content-Type: image/png');
        readfile($filepath);
    }
}
