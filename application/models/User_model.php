<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Get all users
     */
    public function get_all()
    {
        return $this->db->select('id, name, email, phone, role, status, created_at')
            ->from('users')
            ->where('deleted_at', NULL)
            ->order_by('created_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Get user by ID
     */
    public function get_by_id($id)
    {
        return $this->db->select('*')
            ->from('users')
            ->where('id', $id)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    /**
     * Get user by email
     */
    public function get_by_email($email)
    {
        return $this->db->select('*')
            ->from('users')
            ->where('email', $email)
            ->where('deleted_at', NULL)
            ->get()
            ->row_array();
    }

    /**
     * Authenticate user (for login)
     */
    public function authenticate($email, $password)
    {
        $user = $this->get_by_email($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Create new user
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }

    /**
     * Update user
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('users', $data, ['id' => $id]);
    }

    /**
     * Delete user (soft delete)
     */
    public function delete($id)
    {
        return $this->db->update('users', ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    /**
     * Get user count
     */
    public function get_count()
    {
        return $this->db->from('users')
            ->where('deleted_at', NULL)
            ->count_all_results();
    }

    /**
     * Get users by role
     */
    public function get_by_role($role)
    {
        return $this->db->select('*')
            ->from('users')
            ->where('role', $role)
            ->where('deleted_at', NULL)
            ->get()
            ->result_array();
    }

    /**
     * Check if user is admin
     */
    public function is_admin($user_id)
    {
        $user = $this->get_by_id($user_id);
        return $user && $user['role'] === 'admin';
    }
}
