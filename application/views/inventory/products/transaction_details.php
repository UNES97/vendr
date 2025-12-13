<!-- Transaction Details -->
<div class="max-w-4xl mx-auto px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">
                <?php echo ucfirst($type) === 'In' ? 'Check-In' : 'Checkout'; ?> Details
            </h2>
            <p class="text-gray-600 mt-1">Transaction ID: <span class="text-sm bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($transaction_id); ?></span></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo base_url('products/edit_transaction/' . urlencode($transaction_id)); ?>" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
            <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition text-sm">
                <i class="fas fa-trash mr-1"></i>Delete
            </button>
            <a href="<?php echo base_url(ucfirst($type) === 'In' ? 'products/checkin_list' : 'products/checkout_list'); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>
        </div>
    </div>

    <!-- Transaction Summary -->
    <div class="grid grid-cols-1 md:grid-cols-<?php echo ($type === 'in' && !empty(array_filter(array_column($items, 'total_cost')))) ? '5' : '4'; ?> gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-gray-600 text-sm font-semibold">Transaction Type</p>
            <p class="text-2xl font-bold mt-1">
                <?php if (ucfirst($type) === 'In'): ?>
                    <span class="text-green-600"><i class="fas fa-arrow-down mr-1"></i>Check-In</span>
                <?php else: ?>
                    <span class="text-red-600"><i class="fas fa-arrow-up mr-1"></i>Checkout</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-gray-600 text-sm font-semibold">Total Items</p>
            <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo count($items); ?></p>
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-gray-600 text-sm font-semibold">Total Quantity</p>
            <p class="text-2xl font-bold mt-1">
                <?php if (ucfirst($type) === 'In'): ?>
                    <span class="text-green-600">+<?php echo $total_quantity; ?></span>
                <?php else: ?>
                    <span class="text-red-600">-<?php echo $total_quantity; ?></span>
                <?php endif; ?>
            </p>
        </div>

        <?php if ($type === 'in' && !empty(array_filter(array_column($items, 'total_cost')))): ?>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <p class="text-gray-600 text-sm font-semibold">Total Cost</p>
                <p class="text-2xl font-bold text-green-600 mt-1">
                    <?php
                        $grandTotal = array_sum(array_filter(array_column($items, 'total_cost'), function($val) {
                            return !is_null($val) && $val > 0;
                        }));
                        echo get_currency() . ' ' . number_format($grandTotal, 2);
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <p class="text-gray-600 text-sm font-semibold">Date & Time</p>
            <p class="text-sm mt-1"><?php echo date('M d, Y H:i', strtotime($created_at)); ?></p>
        </div>
    </div>

    <!-- Transaction Details Card -->
    <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 font-semibold">Reference Type</p>
                <p class="text-gray-900 mt-1">
                    <?php
                        $reason = $reference_type ?? 'other';
                        $reason_colors = [
                            'sale' => 'bg-blue-100 text-blue-800',
                            'waste' => 'bg-orange-100 text-orange-800',
                            'damage' => 'bg-red-100 text-red-800',
                            'return' => 'bg-yellow-100 text-yellow-800',
                            'adjustment' => 'bg-purple-100 text-purple-800',
                            'purchase_order' => 'bg-green-100 text-green-800',
                            'other' => 'bg-gray-100 text-gray-800'
                        ];
                        $reason_class = $reason_colors[$reason] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-3 py-1 rounded-lg text-sm font-semibold <?php echo $reason_class; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $reason)); ?>
                    </span>
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600 font-semibold">Created At</p>
                <p class="text-gray-900 mt-1"><?php echo date('M d, Y H:i:s', strtotime($created_at)); ?></p>
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    <?php
        $cleanNotes = $notes;
        $poReference = null;

        // Extract PO reference
        if (preg_match('/^PO:\s*(.+?)\s*\|/', $cleanNotes, $matches)) {
            $poReference = trim($matches[1]);
            // Remove PO reference from notes
            $cleanNotes = preg_replace('/^PO:\s*.+?\s*\|\s*/', '', $cleanNotes);
        }

        // Remove receipt filename from notes
        $cleanNotes = preg_replace('/\s*\[Receipt:\s*.+?\]/', '', $cleanNotes);
        $cleanNotes = trim($cleanNotes);
    ?>

    <!-- PO Reference Section -->
    <?php if (!empty($poReference)): ?>
        <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-green-100">
                        <i class="fas fa-file-invoice text-green-600"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Purchase Order</h4>
                    <p class="text-xs text-gray-500 mt-1">Reference number for this transaction</p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-gray-900 text-sm font-semibold"><?php echo htmlspecialchars($poReference); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Notes Section -->
    <?php if (!empty($cleanNotes)): ?>
        <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                        <i class="fas fa-sticky-note text-blue-600"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Notes</h4>
                    <p class="text-xs text-gray-500 mt-1">Additional information about this transaction</p>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-gray-900 text-sm leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($cleanNotes)); ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Receipt Section -->
    <?php
        $hasReceipt = false;
        $receiptFile = null;

        // Extract receipt file from notes if it exists
        if (!empty($notes)) {
            if (preg_match('/\[Receipt:\s*(.+?)\]/', $notes, $matches)) {
                $receiptFile = trim($matches[1]);
                $hasReceipt = !empty($receiptFile) && $receiptFile !== '';
            }
        }
    ?>
    <?php if ($hasReceipt): ?>
        <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
            <div class="flex items-start gap-3 mb-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-amber-100">
                        <i class="fas fa-receipt text-amber-600"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Receipt</h4>
                    <p class="text-xs text-gray-500 mt-1">Supporting document for this transaction</p>
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file text-amber-600 text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate"><?php echo htmlspecialchars($receiptFile); ?></p>
                        <p class="text-xs text-gray-600 mt-1">Attached receipt file</p>
                    </div>
                    <?php if (file_exists(FCPATH . 'upload/receipts/' . $receiptFile)): ?>
                        <a href="<?php echo base_url('upload/receipts/' . urlencode($receiptFile)); ?>" target="_blank" class="flex-shrink-0 inline-flex items-center gap-2 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg transition">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    <?php else: ?>
                        <div class="flex-shrink-0 inline-flex items-center gap-2 px-3 py-2 bg-gray-200 text-gray-600 text-xs font-semibold rounded-lg cursor-not-allowed">
                            <i class="fas fa-exclamation-circle"></i>
                            Not Found
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Items Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Product</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">SKU</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Quantity</th>
                        <?php if ($type === 'in' && !empty(array_filter(array_column($items, 'unit_cost')))): ?>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Unit Cost</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Total Cost</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Supplier</th>
                        <?php endif; ?>
                        <?php if ($type === 'out'): ?>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Reason</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <span class="bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($item['sku']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($type === 'in'): ?>
                                    <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-lg font-semibold text-sm">
                                        +<?php echo $item['quantity']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-lg font-semibold text-sm">
                                        -<?php echo $item['quantity']; ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <?php if ($type === 'in' && !empty(array_filter(array_column($items, 'unit_cost')))): ?>
                                <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">
                                    <?php if (!empty($item['unit_cost']) && $item['unit_cost'] > 0): ?>
                                        <?php echo get_currency(); ?> <?php echo number_format($item['unit_cost'], 2); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-green-600">
                                    <?php if (!empty($item['total_cost']) && $item['total_cost'] > 0): ?>
                                        <?php echo get_currency(); ?> <?php echo number_format($item['total_cost'], 2); ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php if (!empty($item['supplier'])): ?>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-truck text-xs text-gray-400"></i>
                                            <?php echo htmlspecialchars($item['supplier']); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <?php if ($type === 'out'): ?>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                        $item_reason = $item['reference_type'] ?? 'other';
                                        $item_reason_colors = [
                                            'sale' => 'bg-blue-100 text-blue-800',
                                            'waste' => 'bg-orange-100 text-orange-800',
                                            'damage' => 'bg-red-100 text-red-800',
                                            'return' => 'bg-yellow-100 text-yellow-800',
                                            'adjustment' => 'bg-purple-100 text-purple-800',
                                            'other' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $item_reason_class = $item_reason_colors[$item_reason] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-lg text-xs font-semibold <?php echo $item_reason_class; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $item_reason)); ?>
                                    </span>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summary Footer -->
    <div class="bg-gradient-to-r <?php echo $type === 'in' ? 'from-green-50 to-green-100 border-green-200' : 'from-red-50 to-red-100 border-red-200'; ?> rounded-lg p-4 border">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold <?php echo $type === 'in' ? 'text-green-800' : 'text-red-800'; ?>">Total Transaction</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-bold <?php echo $type === 'in' ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo $type === 'in' ? '+' : '-'; ?><?php echo $total_quantity; ?> units
                </p>
                <p class="text-sm <?php echo $type === 'in' ? 'text-green-700' : 'text-red-700'; ?> mt-1">
                    <?php echo count($items); ?> product<?php echo count($items) !== 1 ? 's' : ''; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this transaction?\n\nThis will:\n• Remove all stock movement records\n• Reverse stock quantity changes\n• This action CANNOT be undone!\n\nClick OK to proceed or Cancel to go back.')) {
        // Redirect to delete endpoint
        window.location.href = '<?php echo base_url('products/delete_transaction/' . urlencode($transaction_id)); ?>';
    }
}
</script>
