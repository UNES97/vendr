<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Systemalert_model extends CI_Model {

    private $table = 'system_alerts';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get active alerts for a specific role
     */
    public function get_active_alerts($role = 'all', $limit = 5)
    {
        $this->db->where('is_active', 1);
        $this->db->where("(display_to_role = 'all' OR display_to_role = '{$role}')");
        $this->db->where('(expires_at IS NULL OR expires_at > NOW())', NULL, FALSE);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get all active alerts
     */
    public function get_all_active($limit = 10)
    {
        $this->db->where('is_active', 1);
        $this->db->where('(expires_at IS NULL OR expires_at > NOW())', NULL, FALSE);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Create new alert
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    /**
     * Deactivate alert
     */
    public function deactivate($id)
    {
        return $this->db->where('id', $id)
            ->update($this->table, ['is_active' => 0]);
    }

    /**
     * Delete alert
     */
    public function delete($id)
    {
        return $this->db->where('id', $id)
            ->delete($this->table);
    }

    /**
     * Get alert by ID
     */
    public function get_by_id($id)
    {
        return $this->db->where('id', $id)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Count active alerts
     */
    public function count_active()
    {
        return $this->db->where('is_active', 1)
            ->where('(expires_at IS NULL OR expires_at > NOW())', NULL, FALSE)
            ->count_all_results($this->table);
    }
}
