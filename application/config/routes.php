<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
/* ====== POS System Routes ====== */

// Default route
$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Auth routes
$route['login'] = 'auth/login';
$route['logout'] = 'auth/logout';

// Dashboard routes
$route['dashboard'] = 'dashboard/index';
$route['dashboard/(:any)'] = 'dashboard/$1';

// Inventory routes
$route['inventory/products'] = 'inventory/products';
$route['inventory/categories'] = 'inventory/categories';
$route['inventory/stock'] = 'inventory/stock';
$route['inventory/(:any)'] = 'inventory/$1';

// Products routes (direct access)
$route['products'] = 'products/index';
$route['products/(:any)'] = 'products/$1';

// POS routes
$route['pos'] = 'pos/index';
$route['pos/(:any)'] = 'pos/$1';

// Orders routes
$route['orders'] = 'orders/index';
$route['orders/(:any)'] = 'orders/$1';

// Expenses routes
$route['expenses/list'] = 'expenses/list';
$route['expenses/categories'] = 'expenses/categories';
$route['expenses/(:any)'] = 'expenses/$1';

// Reports routes
$route['reports/sales'] = 'reports/sales';
$route['reports/inventory'] = 'reports/inventory';
$route['reports/expenses'] = 'reports/expenses';
$route['reports/(:any)'] = 'reports/$1';

// Staff routes
$route['staff'] = 'staff/index';
$route['staff/(:any)'] = 'staff/$1';

// Tables routes
$route['tables'] = 'tables/index';
$route['tables/(:any)'] = 'tables/$1';

// Settings routes
$route['settings'] = 'settings/index';
$route['settings/(:any)'] = 'settings/$1';

// Profile routes
$route['profile'] = 'profile/index';
$route['profile/(:any)'] = 'profile/$1';

// ====== CUSTOMER-FACING ROUTES (NO AUTHENTICATION) ======

// Customer menu (public access)
$route['menu'] = 'menu/index';
$route['menu/table/(:num)'] = 'menu/index/$1';
$route['menu/api/categories'] = 'menu/get_categories';
$route['menu/api/meals/(:num)'] = 'menu/get_meals/$1';
$route['menu/api/meal/(:num)'] = 'menu/get_meal_details/$1';

// Online orders (public access)
$route['order/create'] = 'online_order/create';
$route['order/track/(:any)'] = 'online_order/track/$1';
$route['order/status/(:any)'] = 'online_order/get_status/$1';

// ====== ADMIN ROUTES ======

// QR Code management (requires admin/manager auth)
$route['qr-codes'] = 'qr_code/index';
$route['qr-codes/generate/(:num)'] = 'qr_code/generate/$1';
$route['qr-codes/generate-all'] = 'qr_code/generate_all';
$route['qr-codes/download/(:num)'] = 'qr_code/download/$1';
$route['qr-codes/preview/(:num)'] = 'qr_code/preview/$1';
