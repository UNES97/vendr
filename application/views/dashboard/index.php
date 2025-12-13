<!-- Dashboard Content -->
<div class="space-y-6">
    <!-- Key Metrics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <!-- Total Revenue -->
        <a href="<?php echo base_url('reports/sales'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-900 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo format_price($stats['total_revenue']); ?></p>
                    <p class="text-xs <?php echo $stats['revenue_change'] >= 0 ? 'text-green-600' : 'text-red-600'; ?> mt-2">
                        <i class="fas fa-arrow-<?php echo $stats['revenue_change'] >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo ($stats['revenue_change'] >= 0 ? '+' : '') . number_format($stats['revenue_change'], 1) . '%'; ?> from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-900 group-hover:bg-gray-200 transition">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Total Orders -->
        <a href="<?php echo base_url('orders'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo number_format($stats['total_orders']); ?></p>
                    <p class="text-xs <?php echo $stats['orders_change'] >= 0 ? 'text-green-600' : 'text-red-600'; ?> mt-2">
                        <i class="fas fa-arrow-<?php echo $stats['orders_change'] >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo ($stats['orders_change'] >= 0 ? '+' : '') . number_format($stats['orders_change'], 1) . '%'; ?> from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 group-hover:bg-green-200 transition">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Avg Order Value -->
        <a href="<?php echo base_url('reports/sales'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-900 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Avg Order Value</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo format_price($stats['avg_order_value']); ?></p>
                    <p class="text-xs <?php echo $stats['revenue_change'] >= 0 ? 'text-green-600' : 'text-red-600'; ?> mt-2">
                        <i class="fas fa-arrow-<?php echo $stats['revenue_change'] >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo ($stats['revenue_change'] >= 0 ? '+' : '') . number_format($stats['revenue_change'], 1) . '%'; ?> from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-900 group-hover:bg-gray-200 transition">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Items Sold -->
        <a href="<?php echo base_url('products'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Items Sold</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo number_format($stats['items_sold']); ?></p>
                    <p class="text-xs <?php echo $stats['orders_change'] >= 0 ? 'text-green-600' : 'text-red-600'; ?> mt-2">
                        <i class="fas fa-arrow-<?php echo $stats['orders_change'] >= 0 ? 'up' : 'down'; ?>"></i>
                        <?php echo ($stats['orders_change'] >= 0 ? '+' : '') . number_format($stats['orders_change'], 1) . '%'; ?> from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600 group-hover:bg-red-200 transition">
                    <i class="fas fa-cubes text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Low Stock Items -->
        <a href="<?php echo base_url('products'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo $stats['low_stock_items']; ?></p>
                    <p class="text-xs text-red-600 mt-2 group-hover:underline"><i class="fas fa-arrow-right"></i> View Details</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600 group-hover:bg-red-200 transition">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Total Expenses -->
        <a href="<?php echo base_url('expense'); ?>" class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-900 hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2 group-hover:text-blue-600 transition"><?php echo format_price($stats['total_expenses']); ?></p>
                    <p class="text-xs text-gray-600 mt-2"><i class="fas fa-arrow-right"></i> View Expenses</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-900 group-hover:bg-gray-200 transition">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Today's Performance -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Performance</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 font-medium">Revenue</span>
                    <span class="text-lg font-bold text-gray-900"><?php echo format_price($today['revenue']); ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 font-medium">Orders</span>
                    <span class="text-lg font-bold text-gray-900"><?php echo $today['orders']; ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-600 font-medium">Transactions</span>
                    <span class="text-lg font-bold text-gray-900"><?php echo $today['transactions']; ?></span>
                </div>
            </div>
            <a href="<?php echo base_url('reports/sales?date=' . date('Y-m-d')); ?>" class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm inline-block text-center">
                View Details
            </a>
        </div>

        <!-- Sales Chart - Real Data -->
        <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Trend (Last 7 Days)</h3>
            <div class="h-64 flex items-end justify-between gap-2">
                <!-- Dynamic Chart bars from sales_trend data -->
                <?php
                // Check if sales_trend exists and has data
                if (isset($sales_trend) && is_array($sales_trend) && count($sales_trend) > 0):
                    // Calculate max revenue for scaling
                    $revenues = array_column($sales_trend, 'revenue');
                    $max_revenue = max($revenues);

                    // Ensure we have a valid number for scaling
                    if (!is_numeric($max_revenue) || $max_revenue <= 0) {
                        $max_revenue = 100;
                    }

                    $scale_factor = 240 / $max_revenue; // 240px is max bar height

                    foreach ($sales_trend as $day_data):
                        $height = isset($day_data['revenue']) && is_numeric($day_data['revenue'])
                                ? max(intval($day_data['revenue'] * $scale_factor), 10)
                                : 10;
                        $revenue_display = format_price(floatval($day_data['revenue']));
                        $title = "{$day_data['day']}: {$revenue_display}";
                    ?>
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-full bg-blue-500 rounded-t transition-all hover:bg-blue-600"
                             style="height: <?php echo $height; ?>px;"
                             title="<?php echo $title; ?>">
                        </div>
                        <p class="text-xs text-gray-600 mt-2"><?php echo $day_data['day']; ?></p>
                    </div>
                    <?php endforeach;
                else:
                ?>
                    <div class="flex items-center justify-center w-full text-gray-500">
                        <p>No sales data available for the last 7 days</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($sales_trend)): ?>
            <div class="mt-4 grid grid-cols-7 gap-2">
                <?php foreach ($sales_trend as $day_data): ?>
                <div class="text-center">
                    <p class="text-xs text-gray-600 font-medium"><?php echo $day_data['day']; ?></p>
                    <p class="text-sm font-semibold text-gray-900"><?php echo format_price($day_data['revenue']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <a href="<?php echo base_url('orders'); ?>" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                <?php foreach ($recent_orders as $order): ?>
                    <a href="<?php echo base_url('orders/view/' . $order['id']); ?>" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition cursor-pointer group">
                        <div>
                            <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition">Order #<?php echo $order['order_number']; ?></p>
                            <p class="text-xs text-gray-500"><?php echo $order['time']; ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900"><?php echo format_price($order['total']); ?></p>
                            <span class="text-xs px-2 py-1 rounded-full <?php echo $order['status'] === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Top Selling Products</h3>
                <a href="<?php echo base_url('reports/sales'); ?>" class="text-red-600 hover:text-red-700 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-3">
                <?php foreach ($top_products as $idx => $product): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                <?php echo $idx + 1; ?>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo $product['name']; ?></p>
                                <p class="text-xs text-gray-500"><?php echo $product['quantity']; ?> units</p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900"><?php echo format_price($product['revenue']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="<?php echo base_url('pos'); ?>" class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg shadow p-6 text-white hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Start New Order</p>
                    <p class="text-2xl font-bold mt-2">POS</p>
                </div>
                <i class="fas fa-arrow-right text-2xl group-hover:translate-x-1 transition"></i>
            </div>
        </a>

        <a href="<?php echo base_url('products'); ?>" class="bg-gradient-to-br from-green-600 to-green-700 rounded-lg shadow p-6 text-white hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Manage Inventory</p>
                    <p class="text-2xl font-bold mt-2">Products</p>
                </div>
                <i class="fas fa-arrow-right text-2xl group-hover:translate-x-1 transition"></i>
            </div>
        </a>

        <a href="<?php echo base_url('expense'); ?>" class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-lg shadow p-6 text-white hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">Log Expenses</p>
                    <p class="text-2xl font-bold mt-2">Expenses</p>
                </div>
                <i class="fas fa-arrow-right text-2xl group-hover:translate-x-1 transition"></i>
            </div>
        </a>

        <a href="<?php echo base_url('reports/sales'); ?>" class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg shadow p-6 text-white hover:shadow-lg transition cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm">View Reports</p>
                    <p class="text-2xl font-bold mt-2">Analytics</p>
                </div>
                <i class="fas fa-arrow-right text-2xl group-hover:translate-x-1 transition"></i>
            </div>
        </a>
    </div>
</div>
