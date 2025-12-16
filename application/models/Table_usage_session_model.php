<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Table_usage_session_model extends CI_Model {

    protected $table = 'table_usage_sessions';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Start a new table usage session
     * @param int $table_id
     * @param int $order_id
     * @param int $idle_minutes
     * @return int|bool Insert ID or false on failure
     */
    public function start_session($table_id, $order_id, $idle_minutes = 0) {
        $data = array(
            'table_id' => $table_id,
            'order_id' => $order_id,
            'session_start' => date('Y-m-d H:i:s'),
            'idle_before_minutes' => $idle_minutes,
            'was_cancelled' => 0
        );

        if ($this->db->insert($this->table, $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * End a table usage session
     * @param int $table_id
     * @param int $order_id
     * @return bool
     */
    public function end_session($table_id, $order_id) {
        // Get the active session
        $session = $this->get_active_session($table_id);

        if (!$session) {
            return false;
        }

        // Calculate duration in minutes
        $session_end = date('Y-m-d H:i:s');
        $duration = round((strtotime($session_end) - strtotime($session->session_start)) / 60);

        $data = array(
            'session_end' => $session_end,
            'duration_minutes' => $duration
        );

        $this->db->where('id', $session->id);
        return $this->db->update($this->table, $data);
    }

    /**
     * End a table usage session and mark as cancelled
     * @param int $table_id
     * @param int $order_id
     * @return bool
     */
    public function end_session_cancelled($table_id, $order_id) {
        // Get the active session
        $session = $this->get_active_session($table_id);

        if (!$session) {
            return false;
        }

        // Calculate duration in minutes
        $session_end = date('Y-m-d H:i:s');
        $duration = round((strtotime($session_end) - strtotime($session->session_start)) / 60);

        $data = array(
            'session_end' => $session_end,
            'duration_minutes' => $duration,
            'was_cancelled' => 1
        );

        $this->db->where('id', $session->id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Get active (ongoing) session for a table
     * @param int $table_id
     * @return object|null
     */
    public function get_active_session($table_id) {
        $this->db->where('table_id', $table_id);
        $this->db->where('session_end', NULL);
        $this->db->order_by('session_start', 'DESC');
        $this->db->limit(1);
        return $this->db->get($this->table)->row();
    }

    /**
     * Calculate idle time for a table (minutes since last session ended or last_available_at)
     * @param int $table_id
     * @return int Minutes idle
     */
    public function calculate_idle_time($table_id) {
        // Get the last completed session
        $this->db->select('session_end');
        $this->db->where('table_id', $table_id);
        $this->db->where('session_end IS NOT NULL');
        $this->db->order_by('session_end', 'DESC');
        $this->db->limit(1);
        $last_session = $this->db->get($this->table)->row();

        if ($last_session && $last_session->session_end) {
            $idle_seconds = time() - strtotime($last_session->session_end);
            return round($idle_seconds / 60);
        }

        // If no completed session, check table's last_available_at
        $this->db->select('last_available_at');
        $this->db->where('id', $table_id);
        $table = $this->db->get('tables')->row();

        if ($table && $table->last_available_at) {
            $idle_seconds = time() - strtotime($table->last_available_at);
            return round($idle_seconds / 60);
        }

        return 0; // No previous data
    }

    /**
     * Get most used tables in a date range
     * @param string $start_date
     * @param string $end_date
     * @param int $limit
     * @param string|null $section
     * @return array
     */
    public function get_most_used_tables($start_date, $end_date, $limit = 10, $section = null) {
        $this->db->select('t.id, t.table_number, t.section, t.capacity,
                          COUNT(tus.id) as usage_count,
                          SUM(tus.duration_minutes) as total_minutes,
                          AVG(tus.duration_minutes) as avg_duration,
                          AVG(tus.idle_before_minutes) as avg_idle');
        $this->db->from($this->table . ' tus');
        $this->db->join('tables t', 't.id = tus.table_id');
        $this->db->where('tus.session_start >=', $start_date);
        $this->db->where('tus.session_start <=', $end_date);

        if ($section) {
            $this->db->where('t.section', $section);
        }

        $this->db->group_by('tus.table_id');
        $this->db->order_by('usage_count', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Get peak hours usage distribution
     * @param string $start_date
     * @param string $end_date
     * @param int|null $table_id
     * @param string|null $section
     * @return array
     */
    public function get_peak_hours($start_date, $end_date, $table_id = null, $section = null) {
        $this->db->select('HOUR(tus.session_start) as hour, COUNT(*) as session_count');
        $this->db->from($this->table . ' tus');

        if ($section || $table_id) {
            $this->db->join('tables t', 't.id = tus.table_id');
        }

        $this->db->where('tus.session_start >=', $start_date);
        $this->db->where('tus.session_start <=', $end_date);

        if ($table_id) {
            $this->db->where('tus.table_id', $table_id);
        }

        if ($section) {
            $this->db->where('t.section', $section);
        }

        $this->db->group_by('HOUR(tus.session_start)');
        $this->db->order_by('hour', 'ASC');

        $results = $this->db->get()->result_array();

        // Fill in missing hours with 0 counts
        $hours = array_fill(0, 24, 0);
        foreach ($results as $row) {
            $hours[$row['hour']] = $row['session_count'];
        }

        $formatted = [];
        for ($i = 0; $i < 24; $i++) {
            $formatted[] = [
                'hour' => sprintf('%02d:00', $i),
                'session_count' => $hours[$i]
            ];
        }

        return $formatted;
    }

    /**
     * Get summary statistics for date range
     * @param string $start_date
     * @param string $end_date
     * @param int|null $table_id
     * @param string|null $section
     * @return array
     */
    public function get_summary($start_date, $end_date, $table_id = null, $section = null) {
        $this->db->select('COUNT(tus.id) as total_sessions,
                          AVG(tus.duration_minutes) as avg_duration,
                          SUM(tus.duration_minutes) as total_minutes,
                          AVG(tus.idle_before_minutes) as avg_idle');
        $this->db->from($this->table . ' tus');

        if ($section || $table_id) {
            $this->db->join('tables t', 't.id = tus.table_id');
        }

        $this->db->where('tus.session_start >=', $start_date);
        $this->db->where('tus.session_start <=', $end_date);

        if ($table_id) {
            $this->db->where('tus.table_id', $table_id);
        }

        if ($section) {
            $this->db->where('t.section', $section);
        }

        $result = $this->db->get()->row_array();

        // Calculate turnover rate
        $days = max(1, (strtotime($end_date) - strtotime($start_date)) / 86400);

        // Get total tables count
        $this->db->select('COUNT(*) as total_tables');
        $this->db->from('tables');
        $this->db->where('deleted_at', NULL);

        if ($section) {
            $this->db->where('section', $section);
        }

        $table_count = $this->db->get()->row()->total_tables;

        $result['turnover_rate'] = $table_count > 0 ?
            round($result['total_sessions'] / $table_count / $days, 2) : 0;

        return $result;
    }

    /**
     * Get all table statistics
     * @param string $start_date
     * @param string $end_date
     * @param string|null $section
     * @return array
     */
    public function get_all_table_stats($start_date, $end_date, $section = null) {
        // Build join condition without date filters first
        $join_condition = 'tus.table_id = t.id';

        $this->db->select('t.id, t.table_number, t.section, t.status, t.last_available_at,
                          COUNT(tus.id) as total_sessions,
                          SUM(CASE WHEN DATE(tus.session_start) = CURDATE() THEN 1 ELSE 0 END) as sessions_today,
                          SUM(CASE WHEN YEARWEEK(tus.session_start) = YEARWEEK(NOW()) THEN 1 ELSE 0 END) as sessions_this_week');
        $this->db->from('tables t');
        $this->db->join($this->table . ' tus', $join_condition, 'left');

        // Add date filter as WHERE conditions
        $this->db->where('(tus.session_start IS NULL OR (tus.session_start >= ' . $this->db->escape($start_date) . ' AND tus.session_start <= ' . $this->db->escape($end_date) . '))', NULL, FALSE);
        $this->db->where('t.deleted_at', NULL);

        if ($section) {
            $this->db->where('t.section', $section);
        }

        $this->db->group_by('t.id');
        $this->db->order_by('t.table_number', 'ASC');

        $results = $this->db->get()->result_array();

        // Add active session info for occupied tables
        foreach ($results as &$table) {
            if ($table['status'] == 'occupied') {
                $active = $this->get_active_session($table['id']);
                $table['active_session_start'] = $active ? $active->session_start : null;
            }
        }

        return $results;
    }

    /**
     * Get session history for a specific table
     * @param int $table_id
     * @param int $limit
     * @return array
     */
    public function get_table_sessions($table_id, $limit = 30) {
        $this->db->select('tus.*, o.order_number');
        $this->db->from($this->table . ' tus');
        $this->db->join('orders o', 'o.id = tus.order_id', 'left');
        $this->db->where('tus.table_id', $table_id);
        $this->db->order_by('tus.session_start', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Get average duration for a specific table
     * @param int $table_id
     * @param string $start_date
     * @param string $end_date
     * @return float
     */
    public function get_average_duration($table_id, $start_date, $end_date) {
        $this->db->select_avg('duration_minutes');
        $this->db->where('table_id', $table_id);
        $this->db->where('session_start >=', $start_date);
        $this->db->where('session_start <=', $end_date);
        $this->db->where('session_end IS NOT NULL');

        $result = $this->db->get($this->table)->row();
        return $result ? round($result->duration_minutes, 2) : 0;
    }
}
