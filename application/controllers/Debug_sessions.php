<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Debug_sessions Controller
 * Temporary controller to debug table usage sessions
 * DELETE THIS FILE IN PRODUCTION!
 */
class Debug_sessions extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Table_usage_session_model');
        $this->load->model('Table_model');
        $this->load->database();
    }

    /**
     * Check if table_usage_sessions table exists and show sample data
     */
    public function index()
    {
        echo "<h1>Table Usage Sessions Debug</h1>";

        // Show timezone information
        echo "<h2>Timezone Configuration</h2>";
        $php_tz = date_default_timezone_get();
        $php_time = date('Y-m-d H:i:s');
        echo "<p><strong>PHP Timezone:</strong> {$php_tz}</p>";
        echo "<p><strong>PHP Current Time:</strong> {$php_time}</p>";

        // Check MySQL timezone
        $mysql_tz = $this->db->query("SELECT @@session.time_zone as tz, NOW() as mysql_time")->row();
        echo "<p><strong>MySQL Timezone:</strong> {$mysql_tz->tz}</p>";
        echo "<p><strong>MySQL Current Time:</strong> {$mysql_tz->mysql_time}</p>";

        if ($php_time !== $mysql_tz->mysql_time) {
            echo "<p style='color: orange;'>⚠️ Warning: PHP and MySQL times don't match. This might cause timestamp issues.</p>";
        } else {
            echo "<p style='color: green;'>✓ PHP and MySQL times are synchronized</p>";
        }

        echo "<hr>";

        // Check if table exists
        $query = $this->db->query("SHOW TABLES LIKE 'table_usage_sessions'");
        if ($query->num_rows() > 0) {
            echo "<p style='color: green;'>✓ Table 'table_usage_sessions' exists</p>";
        } else {
            echo "<p style='color: red;'>✗ Table 'table_usage_sessions' does NOT exist</p>";
            echo "<p>You need to run the SQL migration from db/pos_db.sql</p>";
            return;
        }

        // Check table structure
        echo "<h2>Table Structure</h2>";
        $columns = $this->db->query("DESCRIBE table_usage_sessions")->result_array();
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>{$col['Field']}</td>";
            echo "<td>{$col['Type']}</td>";
            echo "<td>{$col['Null']}</td>";
            echo "<td>{$col['Key']}</td>";
            echo "<td>{$col['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Count sessions
        echo "<h2>Session Count</h2>";
        $count = $this->db->count_all('table_usage_sessions');
        echo "<p>Total sessions in database: <strong>{$count}</strong></p>";

        // Show recent sessions
        if ($count > 0) {
            echo "<h2>Recent Sessions (Last 10)</h2>";
            $sessions = $this->db->select('*')
                ->from('table_usage_sessions')
                ->order_by('session_start', 'DESC')
                ->limit(10)
                ->get()
                ->result_array();

            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Table ID</th><th>Order ID</th><th>Start</th><th>End</th><th>Duration (min)</th><th>Idle Before (min)</th><th>Cancelled</th></tr>";
            foreach ($sessions as $session) {
                echo "<tr>";
                echo "<td>{$session['id']}</td>";
                echo "<td>{$session['table_id']}</td>";
                echo "<td>{$session['order_id']}</td>";
                echo "<td>{$session['session_start']}</td>";
                echo "<td>" . ($session['session_end'] ?? 'Active') . "</td>";
                echo "<td>" . ($session['duration_minutes'] ?? '-') . "</td>";
                echo "<td>{$session['idle_before_minutes']}</td>";
                echo "<td>" . ($session['was_cancelled'] ? 'Yes' : 'No') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        // Show active sessions
        echo "<h2>Active Sessions (Ongoing)</h2>";
        $active = $this->db->select('tus.*, t.table_number, o.order_number')
            ->from('table_usage_sessions tus')
            ->join('tables t', 't.id = tus.table_id', 'left')
            ->join('orders o', 'o.id = tus.order_id', 'left')
            ->where('tus.session_end IS NULL')
            ->get()
            ->result_array();

        if (count($active) > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Table</th><th>Order</th><th>Started</th><th>Duration So Far</th></tr>";
            foreach ($active as $session) {
                $duration = round((time() - strtotime($session['session_start'])) / 60);
                echo "<tr>";
                echo "<td>{$session['id']}</td>";
                echo "<td>Table {$session['table_number']}</td>";
                echo "<td>{$session['order_number']}</td>";
                echo "<td>{$session['session_start']}</td>";
                echo "<td>{$duration} minutes</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No active sessions</p>";
        }

        // Check for tables
        echo "<h2>Available Tables</h2>";
        $tables = $this->Table_model->get_all();
        echo "<p>Total tables: " . count($tables) . "</p>";
        if (count($tables) > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Table Number</th><th>Status</th><th>Section</th></tr>";
            foreach (array_slice($tables, 0, 10) as $table) {
                echo "<tr>";
                echo "<td>{$table['id']}</td>";
                echo "<td>{$table['table_number']}</td>";
                echo "<td>{$table['status']}</td>";
                echo "<td>{$table['section']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    /**
     * Test creating a session
     */
    public function test_create()
    {
        echo "<h1>Test Session Creation</h1>";

        // Get first available table
        $tables = $this->Table_model->get_all();
        if (empty($tables)) {
            echo "<p style='color: red;'>No tables found in database</p>";
            return;
        }

        $table_id = $tables[0]['id'];
        echo "<p>Using table ID: {$table_id}</p>";

        // Try to create a test session
        echo "<h2>Creating Test Session...</h2>";
        $session_id = $this->Table_usage_session_model->start_session($table_id, null, 0);

        if ($session_id) {
            echo "<p style='color: green;'>✓ Session created successfully! Session ID: {$session_id}</p>";

            // Get the session
            $session = $this->db->where('id', $session_id)->get('table_usage_sessions')->row_array();
            echo "<pre>";
            print_r($session);
            echo "</pre>";

            echo "<p><a href='" . base_url('debug_sessions/test_end/' . $session_id) . "'>End this test session</a></p>";
        } else {
            echo "<p style='color: red;'>✗ Failed to create session</p>";

            // Check for database errors
            if ($this->db->error()['message']) {
                echo "<p>Database error: " . $this->db->error()['message'] . "</p>";
            }
        }

        echo "<p><a href='" . base_url('debug_sessions') . "'>Back to debug page</a></p>";
    }

    /**
     * Test ending a session
     */
    public function test_end($session_id)
    {
        echo "<h1>Test End Session</h1>";

        // Get the session
        $session = $this->db->where('id', $session_id)->get('table_usage_sessions')->row();

        if (!$session) {
            echo "<p style='color: red;'>Session not found</p>";
            echo "<p><a href='" . base_url('debug_sessions') . "'>Back to debug page</a></p>";
            return;
        }

        echo "<p>Ending session ID: {$session_id} for table ID: {$session->table_id}</p>";

        $result = $this->Table_usage_session_model->end_session($session->table_id, $session->order_id);

        if ($result) {
            echo "<p style='color: green;'>✓ Session ended successfully</p>";

            // Get updated session
            $updated = $this->db->where('id', $session_id)->get('table_usage_sessions')->row_array();
            echo "<pre>";
            print_r($updated);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>✗ Failed to end session</p>";
        }

        echo "<p><a href='" . base_url('debug_sessions') . "'>Back to debug page</a></p>";
    }
}
