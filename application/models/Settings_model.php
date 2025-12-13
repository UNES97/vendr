<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    private $table = 'settings';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a single setting value by key
     */
    public function get($key)
    {
        return $this->db->where('key', $key)->get($this->table)->row();
    }

    /**
     * Get all settings
     */
    public function get_all()
    {
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Save or update a setting
     */
    public function save($key, $value)
    {
        $existing = $this->db->where('key', $key)->get($this->table)->row();

        if ($existing) {
            // Update existing setting
            return $this->db->where('key', $key)
                ->update($this->table, ['value' => $value]);
        } else {
            // Insert new setting
            return $this->db->insert($this->table, [
                'key' => $key,
                'value' => $value
            ]);
        }
    }

    /**
     * Save multiple settings at once
     */
    public function save_multiple($settings)
    {
        foreach ($settings as $key => $value) {
            $this->save($key, $value);
        }
        return true;
    }

    /**
     * Get all settings as key-value array (for easy access)
     */
    public function get_as_array()
    {
        $settings = $this->db->get($this->table)->result_array();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        return $result;
    }

    /**
     * Delete a setting
     */
    public function delete($key)
    {
        return $this->db->where('key', $key)->delete($this->table);
    }
}
