<!-- Orders Management -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Orders</h2>
            <p class="text-gray-600 mt-1">Manage and track all customer orders</p>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-700 text-xs font-semibold uppercase tracking-wide">Total Orders</p>
                    <p class="text-2xl font-bold text-red-900 mt-1"><?php echo $total_orders; ?></p>
                </div>
                <i class="fas fa-receipt text-3xl text-red-300"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-700 text-xs font-semibold uppercase tracking-wide">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-900 mt-1"><?php echo get_currency(); ?> <?php echo number_format($total_revenue, 0); ?></p>
                </div>
                <i class="fas fa-coins text-3xl text-green-300"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-700 text-xs font-semibold uppercase tracking-wide">Pending Orders</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1"><?php echo $pending_orders; ?></p>
                </div>
                <i class="fas fa-hourglass-half text-3xl text-yellow-300"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-700 text-xs font-semibold uppercase tracking-wide">Completed Orders</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1"><?php echo $completed_orders; ?></p>
                </div>
                <i class="fas fa-check-circle text-3xl text-blue-300"></i>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <form method="GET" action="<?php echo base_url('orders'); ?>">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                        placeholder="Order # or customer..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo ($filters['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="preparing" <?php echo ($filters['status'] === 'preparing') ? 'selected' : ''; ?>>Preparing</option>
                        <option value="ready" <?php echo ($filters['status'] === 'ready') ? 'selected' : ''; ?>>Ready</option>
                        <option value="served" <?php echo ($filters['status'] === 'served') ? 'selected' : ''; ?>>Served</option>
                        <option value="completed" <?php echo ($filters['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($filters['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Date</label>
                    <input
                        type="text"
                        id="date-filter"
                        name="date"
                        value="<?php echo htmlspecialchars($filters['date'] ?? ''); ?>"
                        placeholder="Select date..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm"
                    >
                        <i class="fas fa-search mr-1"></i> Search
                    </button>
                    <a
                        href="<?php echo base_url('orders'); ?>"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                        title="Reset filters"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Flatpicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr('#date-filter', {
            mode: 'single',
            dateFormat: 'Y-m-d',
            allowInput: true,
            onClose: function(selectedDates, dateStr, instance) {
                // Optional: auto-submit or add any custom behavior
            }
        });
    </script>

    <style>
        .flatpickr-calendar {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            z-index: 9999 !important;
            width: auto !important;
        }

        .flatpickr-calendar.open {
            display: block !important;
        }

        .flatpickr-months {
            padding: 12px;
            width: 100%;
        }

        .flatpickr-months .flatpickr-month {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }

        .flatpickr-innerContainer {
            padding: 0 12px 12px 12px;
            display: block;
        }

        .flatpickr-weekdays {
            background: #f3f4f6;
            padding: 8px 0;
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
        }

        .flatpickr-days {
            padding: 4px 0;
            width: 100%;
        }

        .flatpickr-day {
            color: #374151;
            font-size: 13px;
            border-radius: 4px;
            margin: 2px;
            padding: 6px;
            flex: 0 0 calc(14.285714% - 4px);
        }

        .flatpickr-day:hover {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background-color: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        .flatpickr-day.today {
            border-color: #dc2626;
            color: #dc2626;
        }

        .flatpickr-day.disabled {
            color: #d1d5db;
            cursor: not-allowed;
        }

        .flatpickr-rContainer {
            padding: 0;
        }

        .flatpickr-time {
            text-align: center;
            outline: none;
            display: none;
        }
    </style>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Order #</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Customer</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Type</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Items</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Total</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <?php
                                // Determine status class
                                if ($order['order_status'] === 'pending') {
                                    $status_class = 'bg-yellow-100 text-yellow-800';
                                } elseif ($order['order_status'] === 'preparing') {
                                    $status_class = 'bg-blue-100 text-blue-800';
                                } elseif ($order['order_status'] === 'ready') {
                                    $status_class = 'bg-purple-100 text-purple-800';
                                } elseif ($order['order_status'] === 'served') {
                                    $status_class = 'bg-green-100 text-green-800';
                                } elseif ($order['order_status'] === 'completed') {
                                    $status_class = 'bg-gray-100 text-gray-800';
                                } elseif ($order['order_status'] === 'cancelled') {
                                    $status_class = 'bg-red-100 text-red-800';
                                } else {
                                    $status_class = 'bg-gray-100 text-gray-800';
                                }

                                // Determine payment status class
                                if ($order['payment_status'] === 'completed') {
                                    $payment_status_class = 'text-green-600';
                                } elseif ($order['payment_status'] === 'pending') {
                                    $payment_status_class = 'text-yellow-600';
                                } elseif ($order['payment_status'] === 'failed') {
                                    $payment_status_class = 'text-red-600';
                                } else {
                                    $payment_status_class = 'text-gray-600';
                                }
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($order['order_number']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in'); ?>
                                    <?php if (!empty($order['customer_phone'])): ?>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo ucfirst(str_replace('-', ' ', $order['order_type'])); ?>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    <span class="inline-block bg-gray-100 px-3 py-1 rounded-full"><?php echo count($this->Order_model->get_items($order['id'])); ?></span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                    <?php echo format_price($order['total_amount']); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $status_class; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo format_date($order['created_at']); ?><br>
                                    <span class="text-xs text-gray-500"><?php echo format_time($order['created_at']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="<?php echo base_url('orders/view/' . $order['id']); ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('orders/delete/' . $order['id']); ?>" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete" onclick="return confirm('Are you sure you want to delete this order?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 inline-block opacity-50"></i>
                                <p class="text-lg mt-2">No orders found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?php echo $pagination; ?>
    </div>
</div>
