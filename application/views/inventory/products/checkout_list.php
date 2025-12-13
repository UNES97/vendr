<!-- Checkout History -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Checkout History</h2>
            <p class="text-gray-600 mt-1">View all product stock removals</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo base_url('products'); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Products
            </a>
            <a href="<?php echo base_url('products/checkout'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> New Checkout
            </a>
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

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <form method="GET" action="<?php echo base_url('products/checkout_list'); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <!-- Search Product -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Product Name</label>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                        placeholder="Search product..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Reason Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Reason</label>
                    <select
                        name="reason"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">All Reasons</option>
                        <option value="sale" <?php echo ($filters['reason'] ?? '') === 'sale' ? 'selected' : ''; ?>>Sale/Order</option>
                        <option value="waste" <?php echo ($filters['reason'] ?? '') === 'waste' ? 'selected' : ''; ?>>Waste/Spoilage</option>
                        <option value="damage" <?php echo ($filters['reason'] ?? '') === 'damage' ? 'selected' : ''; ?>>Damaged</option>
                        <option value="return" <?php echo ($filters['reason'] ?? '') === 'return' ? 'selected' : ''; ?>>Return</option>
                        <option value="adjustment" <?php echo ($filters['reason'] ?? '') === 'adjustment' ? 'selected' : ''; ?>>Adjustment</option>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">From Date</label>
                    <input
                        type="text"
                        id="checkout_date_from"
                        name="date_from"
                        value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>"
                        placeholder="Select date..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">To Date</label>
                    <input
                        type="text"
                        id="checkout_date_to"
                        name="date_to"
                        value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>"
                        placeholder="Select date..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-3 py-2 rounded-lg transition text-sm flex-1"
                    >
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a
                        href="<?php echo base_url('products/checkout_list'); ?>"
                        class="px-3 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                        title="Reset filters"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#checkout_date_from", {
            mode: "single",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        flatpickr("#checkout_date_to", {
            mode: "single",
            dateFormat: "Y-m-d",
            allowInput: true
        });
    </script>

    <!-- Checkouts Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Products</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Items</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Total Units</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Reason</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($checkouts)): ?>
                        <?php foreach ($checkouts as $transaction): ?>
                            <?php
                                $reason = $transaction['reference_type'] ?? 'other';
                                $reason_colors = [
                                    'sale' => 'bg-blue-100 text-blue-800',
                                    'waste' => 'bg-orange-100 text-orange-800',
                                    'damage' => 'bg-red-100 text-red-800',
                                    'return' => 'bg-yellow-100 text-yellow-800',
                                    'adjustment' => 'bg-purple-100 text-purple-800',
                                    'other' => 'bg-gray-100 text-gray-800'
                                ];
                                $reason_class = $reason_colors[$reason] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($transaction['products']); ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-lg font-semibold text-sm">
                                        <?php echo $transaction['item_count']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-lg font-semibold text-sm">
                                        -<?php echo $transaction['total_quantity']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-lg text-sm font-semibold <?php echo $reason_class; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $reason)); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo date('M d, Y H:i', strtotime($transaction['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a
                                        href="<?php echo base_url('products/transaction_details/' . urlencode($transaction['transaction_id'])); ?>"
                                        class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition inline-block"
                                        title="View Details"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 inline-block opacity-50"></i>
                                <p class="text-lg mt-2">No checkouts found</p>
                                <p class="text-sm mt-1">
                                    <a href="<?php echo base_url('products/checkout'); ?>" class="text-red-600 hover:underline">Create your first checkout</a>
                                </p>
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

    <!-- Statistics -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-arrow-up text-red-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-red-800 font-semibold">Total Checkouts</p>
                    <p class="text-red-600 text-sm"><?php echo $total_checkouts ?? 0; ?> transactions</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-cube text-blue-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-blue-800 font-semibold">Units Removed</p>
                    <p class="text-blue-600 text-sm"><?php echo $total_units ?? 0; ?> units</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-trash text-orange-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-orange-800 font-semibold">Waste/Loss</p>
                    <p class="text-orange-600 text-sm"><?php echo $waste_units ?? 0; ?> units</p>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-calendar text-purple-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-purple-800 font-semibold">Latest Checkout</p>
                    <p class="text-purple-600 text-sm"><?php echo isset($latest_checkout) ? date('M d, Y', strtotime($latest_checkout)) : 'N/A'; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

