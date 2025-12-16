<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// Set MySQL timezone to match PHP timezone
$hook['post_controller_constructor'] = array(
    'class'    => '',
    'function' => 'set_mysql_timezone',
    'filename' => 'timezone_hook.php',
    'filepath' => 'hooks'
);
