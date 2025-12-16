<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_model extends CI_Model {

    private $table = 'tables';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all tables
     */
    public function get_all()
    {
        return $this->db->where('deleted_at', NULL)
            ->order_by('table_number', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Get table by ID
     */
    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
            ->where('deleted_at', NULL)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Create new table
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Update table
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)
            ->update($this->table, $data);
    }

    /**
     * Delete table (soft delete)
     */
    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->update($this->table, ['deleted_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Get total tables
     */
    public function count()
    {
        return $this->db->where('deleted_at', NULL)
            ->count_all_results($this->table);
    }

    /**
     * Get available tables (not occupied)
     */
    public function get_available()
    {
        return $this->db->where('status', 'available')
            ->where('deleted_at', NULL)
            ->order_by('table_number', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Get occupied tables
     */
    public function get_occupied()
    {
        return $this->db->where('status', 'occupied')
            ->where('deleted_at', NULL)
            ->order_by('table_number', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    // ===== QR CODE METHODS =====

    /**
     * Update QR code filename for table
     */
    public function update_qr_code($table_id, $filename)
    {
        return $this->db->where('id', $table_id)
            ->update($this->table, [
                'qr_code' => $filename,
                'qr_code_generated_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Get tables without QR codes
     */
    public function get_without_qr()
    {
        return $this->db->where('deleted_at', NULL)
            ->where('(qr_code IS NULL OR qr_code = "")')
            ->order_by('table_number', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Get tables with QR codes
     */
    public function get_with_qr()
    {
        return $this->db->where('deleted_at', NULL)
            ->where('qr_code IS NOT NULL')
            ->where('qr_code !=', '')
            ->order_by('table_number', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Get distinct sections (for filtering in reports)
     */
    public function get_distinct_sections()
    {
        return $this->db->select('section')
            ->where('deleted_at', NULL)
            ->where('section IS NOT NULL')
            ->where('section !=', '')
            ->group_by('section')
            ->order_by('section', 'ASC')
            ->get($this->table)
            ->result_array();
    }
}
