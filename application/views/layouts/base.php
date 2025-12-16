<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        $this->load->helper('settings');
        $restaurant_name = get_restaurant_name() ?: 'RestroFlow';
        echo isset($page_title) ? $page_title . ' - ' . $restaurant_name : $restaurant_name . ' - Restaurant POS & Inventory';
    ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Raleway', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
        }

        [x-cloak] { display: none !important; }

        /* Prevent dropdown flashing during page load */
        [data-dropdown] { visibility: hidden; }
        [data-dropdown][style*="display: block"] { visibility: visible; }
        [data-dropdown][style*="display: none"] { visibility: hidden; }

        /* Professional Color Scheme - 3 Main Colors */
        :root {
            --primary: #1f2937;      /* Deep Slate */
            --accent: #dc2626;       /* Vibrant Red */
            --success: #059669;      /* Fresh Green */
        }

        body {
            background-color: #fafafa;
            color: #1f2937;
        }

        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }

        .smooth-transition {
            transition: all 0.2s ease-in-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: window.innerWidth > 768 ? true : false }" @resize.window="sidebarOpen = window.innerWidth > 768 ? true : false">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-900 text-white sidebar-transition fixed h-screen overflow-y-auto md:relative z-40">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <div x-show="sidebarOpen" class="flex items-center space-x-3 w-full justify-center">
                        <div class="text-center">
                            <?php
                                $this->load->helper('settings');
                                $restaurant_name = get_restaurant_name();
                            ?>
                            <h1 class="text-xl font-bold text-white"><?php echo $restaurant_name ?: 'RestroFlow'; ?></h1>
                            <p class="text-xs text-gray-400">Open Source POS</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-1 hover:bg-slate-700 rounded-lg transition">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-8 px-4">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <a href="<?php echo base_url('dashboard'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-chart-line w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Dashboard</span>
                    </a>

                    <!-- Products -->
                    <div x-data="{ productsOpen: false }" class="space-y-1">
                        <button @click="productsOpen = !productsOpen" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200">
                            <i class="fas fa-boxes w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                            <span x-show="sidebarOpen" class="flex-1 text-left">Products</span>
                            <i x-show="sidebarOpen" :class="productsOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-xs transition"></i>
                        </button>
                        <div x-show="productsOpen" class="bg-gray-800 rounded-lg overflow-hidden">
                            <a href="<?php echo base_url('products'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-list w-4"></i>
                                <span x-show="sidebarOpen">All Products</span>
                            </a>
                            <a href="<?php echo base_url('products/checkin_list'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-arrow-down w-4"></i>
                                <span x-show="sidebarOpen">Check-In History</span>
                            </a>
                            <a href="<?php echo base_url('products/checkout_list'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-arrow-up w-4"></i>
                                <span x-show="sidebarOpen">Checkout History</span>
                            </a>
                        </div>
                    </div>

                    <!-- Menu -->
                    <div x-data="{ menuOpen: false }" class="space-y-1">
                        <button @click="menuOpen = !menuOpen" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200">
                            <i class="fas fa-utensils w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                            <span x-show="sidebarOpen" class="flex-1 text-left">Menu</span>
                            <i x-show="sidebarOpen" :class="menuOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-xs transition"></i>
                        </button>
                        <div x-show="menuOpen" class="bg-gray-800 rounded-lg overflow-hidden">
                            <a href="<?php echo base_url('meals'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-plate-wheat w-4"></i>
                                <span x-show="sidebarOpen">Meals</span>
                            </a>
                            <a href="<?php echo base_url('inventory/categories'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-layer-group w-4"></i>
                                <span x-show="sidebarOpen">Categories</span>
                            </a>
                        </div>
                    </div>

                    <!-- POS -->
                    <a href="<?php echo base_url('pos'); ?>" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-cash-register w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">POS</span>
                    </a>

                    <!-- Kitchen Display -->
                    <a href="<?php echo base_url('kitchen'); ?>" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-fire w-5 text-center text-gray-500 group-hover:text-orange-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Kitchen</span>
                    </a>

                    <!-- Waiter Dashboard -->
                    <a href="<?php echo base_url('waiter'); ?>" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-utensils w-5 text-center text-gray-500 group-hover:text-blue-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Waiter</span>
                    </a>

                    <!-- Orders -->
                    <a href="<?php echo base_url('orders'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-receipt w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Orders</span>
                    </a>

                    <!-- Expenses -->
                    <a href="<?php echo base_url('expense'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-wallet w-5 text-center text-gray-500 group-hover:text-yellow-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Expenses</span>
                    </a>

                    <!-- Reports -->
                    <div x-data="{ reportsOpen: false }" class="space-y-1">
                        <button @click="reportsOpen = !reportsOpen" class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200">
                            <i class="fas fa-chart-bar w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                            <span x-show="sidebarOpen" class="flex-1 text-left">Reports</span>
                            <i x-show="sidebarOpen" :class="reportsOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-xs transition"></i>
                        </button>
                        <div x-show="reportsOpen" class="bg-gray-800 rounded-lg overflow-hidden">
                            <a href="<?php echo base_url('reports/sales'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-coins w-4"></i>
                                <span x-show="sidebarOpen">Sales</span>
                            </a>
                            <a href="<?php echo base_url('reports/inventory'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-utensils w-4"></i>
                                <span x-show="sidebarOpen">Top Meals</span>
                            </a>
                            <a href="<?php echo base_url('reports/stock'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-boxes w-4"></i>
                                <span x-show="sidebarOpen">Inventory</span>
                            </a>
                            <a href="<?php echo base_url('reports/expenses'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-chart-pie w-4"></i>
                                <span x-show="sidebarOpen">Expenses</span>
                            </a>
                            <a href="<?php echo base_url('reports/table_usage'); ?>" class="flex items-center space-x-3 px-8 py-2 text-sm text-gray-400 hover:text-white hover:bg-gray-700 transition">
                                <i class="fas fa-table w-4"></i>
                                <span x-show="sidebarOpen">Table Usage</span>
                            </a>
                        </div>
                    </div>

                    <!-- Staff -->
                    <a href="<?php echo base_url('staff'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-users w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Staff</span>
                    </a>

                    <!-- Tables -->
                    <a href="<?php echo base_url('tables'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-chair w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Tables</span>
                    </a>

                    <!-- qrCodes -->
                    <a href="<?php echo base_url('qr-codes'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-qrcode w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">QR Codes</span>
                    </a>

                    <!-- Settings -->
                    <a href="<?php echo base_url('settings'); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition duration-200 group">
                        <i class="fas fa-cog w-5 text-center text-gray-500 group-hover:text-red-600"></i>
                        <span x-show="sidebarOpen" class="group-hover:translate-x-1 transition">Settings</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-bars text-gray-700"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
                    </div>

                    <div class="flex items-center space-x-6">
                        <!-- Search -->
                        <div class="hidden md:flex items-center bg-gray-100 rounded-lg px-4 py-2 space-x-2 w-64">
                            <i class="fas fa-search text-gray-400"></i>
                            <input type="text" placeholder="Search..." class="bg-transparent outline-none text-sm text-gray-700 placeholder-gray-500 w-full">
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ notificationOpen: false }" class="relative">
                            <?php
                                $alerts = array();
                                $alert_count = 0;
                                try {
                                    $CI = &get_instance();
                                    $CI->load->model('systemalert_model');
                                    $current_role = $CI->session->userdata('role') ?: 'staff';
                                    if ($CI->systemalert_model) {
                                        $alerts = $CI->systemalert_model->get_active_alerts($current_role, 10);
                                        $alert_count = count($alerts);
                                    }
                                } catch (Exception $e) {
                                    // Handle error silently - notifications not available
                                }
                            ?>
                            <button @click="notificationOpen = !notificationOpen" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <i class="fas fa-bell text-lg"></i>
                                <?php if ($alert_count > 0): ?>
                                    <span class="absolute top-1 right-1 w-3 h-3 bg-red-600 rounded-full"></span>
                                <?php endif; ?>
                            </button>
                            <div x-show="notificationOpen" x-transition @click.outside="notificationOpen = false" @keydown.escape.window="notificationOpen = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-900">Notifications <?php echo $alert_count > 0 ? '(' . $alert_count . ')' : ''; ?></h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <?php if ($alert_count > 0): ?>
                                        <?php foreach ($alerts as $alert): ?>
                                            <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition">
                                                <div class="flex items-start space-x-2">
                                                    <i class="fas <?php echo htmlspecialchars($alert['icon'] ?: 'fa-info-circle'); ?> text-sm mt-0.5
                                                    <?php
                                                        echo $alert['type'] === 'warning' ? 'text-yellow-600' :
                                                             ($alert['type'] === 'error' ? 'text-red-600' :
                                                             ($alert['type'] === 'success' ? 'text-green-600' : 'text-blue-600'));
                                                    ?>"></i>
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($alert['title']); ?></p>
                                                        <p class="text-xs text-gray-500 mt-1"><?php echo htmlspecialchars($alert['message']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="p-8 text-center">
                                            <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                            <p class="text-sm text-gray-500">No notifications</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- User Menu -->
                        <div x-data="{ profileOpen: false }" class="relative">
                            <button @click="profileOpen = !profileOpen" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    <?php echo isset($user_initial) ? $user_initial : 'A'; ?>
                                </div>
                                <i x-show="false" class="fas fa-chevron-down text-gray-600"></i>
                            </button>
                            <div x-show="profileOpen" x-transition @click.outside="profileOpen = false" @keydown.escape.window="profileOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                <div class="p-4 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900"><?php echo isset($user_name) ? $user_name : 'Admin User'; ?></p>
                                    <p class="text-xs text-gray-500"><?php echo isset($user_email) ? $user_email : 'admin@pos.local'; ?></p>
                                </div>
                                <a href="<?php echo base_url('profile'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-user w-4"></i> Profile
                                </a>
                                <a href="<?php echo base_url('settings'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <i class="fas fa-cog w-4"></i> Settings
                                </a>
                                <div class="border-t border-gray-100">
                                    <a href="<?php echo base_url('auth/logout'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-sign-out-alt w-4"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto">
                <div class="p-6">
                    <!-- Flash Messages -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start space-x-3">
                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-green-900">Success</h3>
                                <p class="text-sm text-green-800"><?php echo $this->session->flashdata('success'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-3">
                            <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-red-900">Error</h3>
                                <p class="text-sm text-red-800"><?php echo $this->session->flashdata('error'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('warning')): ?>
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start space-x-3">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-yellow-900">Warning</h3>
                                <p class="text-sm text-yellow-800"><?php echo $this->session->flashdata('warning'); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <?php echo $content; ?>
                </div>
            </main>
        </div>
    </div>

    <?php if (isset($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>
</body>
</html>
