<!-- Order Details -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo base_url('orders'); ?>" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Orders
        </a>
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

    <!-- Order Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg p-6 mb-6 shadow-md">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-red-100 text-xs font-semibold uppercase tracking-wide">Order ID</p>
                <h2 class="text-2xl font-bold mt-1"><?php echo htmlspecialchars($order['order_number']); ?></h2>
            </div>
            <?php
                // Determine status class
                if ($order['order_status'] === 'pending') {
                    $status_class = 'bg-yellow-500 text-white';
                } elseif ($order['order_status'] === 'preparing') {
                    $status_class = 'bg-blue-500 text-white';
                } elseif ($order['order_status'] === 'ready') {
                    $status_class = 'bg-purple-500 text-white';
                } elseif ($order['order_status'] === 'served') {
                    $status_class = 'bg-green-500 text-white';
                } elseif ($order['order_status'] === 'completed') {
                    $status_class = 'bg-gray-600 text-white';
                } elseif ($order['order_status'] === 'cancelled') {
                    $status_class = 'bg-red-800 text-white';
                } else {
                    $status_class = 'bg-gray-600 text-white';
                }

                // Determine payment status class
                if ($order['payment_status'] === 'completed') {
                    $payment_status_class = 'text-green-300';
                } elseif ($order['payment_status'] === 'pending') {
                    $payment_status_class = 'text-yellow-300';
                } elseif ($order['payment_status'] === 'failed') {
                    $payment_status_class = 'text-red-300';
                } else {
                    $payment_status_class = 'text-gray-300';
                }
            ?>
            <div class="text-right">
                <span class="px-3 py-1 rounded-lg text-xs font-semibold <?php echo $status_class; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $order['order_status'])); ?>
                </span>
            </div>
        </div>
        <p class="text-red-100 text-sm"><?php echo format_datetime($order['created_at']); ?></p>
    </div>

    <!-- Order Information Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Customer</p>
            <p class="text-sm font-semibold text-gray-900 mt-2"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in'); ?></p>
            <?php if (!empty($order['customer_phone'])): ?>
                <p class="text-xs text-gray-600 mt-1"><?php echo htmlspecialchars($order['customer_phone']); ?></p>
            <?php endif; ?>
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Order Type</p>
            <p class="text-sm font-semibold text-gray-900 mt-2"><?php echo ucfirst(str_replace('-', ' ', $order['order_type'])); ?></p>
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Payment</p>
            <p class="text-sm font-semibold mt-2 <?php echo $payment_status_class; ?>">
                <?php echo ucfirst($order['payment_status']); ?>
            </p>
            <p class="text-xs text-gray-600 mt-1"><?php echo ucfirst($order['payment_method']); ?></p>
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Amount</p>
            <p class="text-lg font-bold text-red-600 mt-2"><?php echo format_price($order['total_amount']); ?></p>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 mb-6">
        <div class="border-b border-gray-200 px-5 py-3">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left font-semibold text-gray-700">Item</th>
                        <th class="px-5 py-3 text-center font-semibold text-gray-700">Qty</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700">Price</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-5 py-3 text-gray-900 font-semibold">
                                    <?php echo htmlspecialchars($item['meal_name']); ?>
                                </td>
                                <td class="px-5 py-3 text-center text-gray-700">
                                    <span class="inline-block bg-gray-100 px-2 py-1 rounded-lg"><?php echo htmlspecialchars($item['quantity']); ?></span>
                                </td>
                                <td class="px-5 py-3 text-right text-gray-700">
                                    <?php echo format_price($item['unit_price']); ?>
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-900">
                                    <?php echo format_price($item['total_price']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-2xl opacity-30 mb-2 inline-block"></i>
                                <p>No items found</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6 border border-gray-200 mb-6">
        <div class="space-y-2.5">
            <div class="flex items-center justify-between pb-2.5 border-b border-gray-300">
                <span class="text-sm text-gray-700">Subtotal</span>
                <span class="font-semibold text-gray-900"><?php echo format_price($order['subtotal']); ?></span>
            </div>

            <div class="flex items-center justify-between pb-2.5 border-b border-gray-300">
                <span class="text-sm text-gray-700">Tax (<?php echo get_tax_rate(); ?>%)</span>
                <span class="font-semibold text-gray-900"><?php echo format_price($order['tax_amount']); ?></span>
            </div>

            <div class="flex items-center justify-between pt-2.5">
                <span class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Total Amount</span>
                <span class="text-xl font-bold text-red-600"><?php echo format_price($order['total_amount']); ?></span>
            </div>
        </div>
    </div>

    <!-- Update Status Form -->
    <div class="bg-white rounded-lg p-6 border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Update Status</h3>

        <form method="POST" action="<?php echo base_url('orders/update_status/' . $order['id']); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Order Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Order Status</label>
                    <select
                        name="order_status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">-- Select Status --</option>
                        <option value="pending" <?php echo ($order['order_status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="preparing" <?php echo ($order['order_status'] === 'preparing') ? 'selected' : ''; ?>>Preparing</option>
                        <option value="ready" <?php echo ($order['order_status'] === 'ready') ? 'selected' : ''; ?>>Ready</option>
                        <option value="served" <?php echo ($order['order_status'] === 'served') ? 'selected' : ''; ?>>Served</option>
                        <option value="completed" <?php echo ($order['order_status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($order['order_status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Payment Status</label>
                    <select
                        name="payment_status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">-- Select Payment Status --</option>
                        <option value="pending" <?php echo ($order['payment_status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo ($order['payment_status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="failed" <?php echo ($order['payment_status'] === 'failed') ? 'selected' : ''; ?>>Failed</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-2 pt-4 border-t border-gray-200">
                <a
                    href="<?php echo base_url('orders/delete/' . $order['id']); ?>"
                    class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg text-sm transition"
                    onclick="return confirm('Are you sure you want to delete this order?')"
                >
                    <i class="fas fa-trash mr-1"></i>Delete
                </a>

                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition"
                >
                    <i class="fas fa-save mr-1"></i>Update Status
                </button>
            </div>
        </form>
    </div>
</div>
