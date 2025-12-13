<!-- Edit Transaction -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Edit Transaction</h2>
            <p class="text-gray-600 mt-1">Update transaction details and stock movements</p>
            <p class="text-gray-600 mt-1">Transaction ID: <span class="text-sm bg-gray-100 px-2 py-1 rounded"><?php echo htmlspecialchars($transaction_id); ?></span></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo base_url('products/transaction_details/' . urlencode($transaction_id)); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Cancel
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

    <!-- Warning Message -->
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle mr-2 mt-1"></i>
            <div>
                <p class="font-semibold">Important: Editing this transaction will update stock quantities</p>
                <ul class="text-sm mt-2 space-y-1">
                    <li>• Changing quantities will automatically adjust product stock levels</li>
                    <li>• The original stock movements will be reversed and new ones will be created</li>
                    <li>• Make sure all changes are correct before saving</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <form id="editTransactionForm" method="POST" action="<?php echo base_url('products/update_transaction/' . urlencode($transaction_id)); ?>">
            <!-- Transaction Info -->
            <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Transaction Type</label>
                        <p class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                            <?php echo ucfirst($type) === 'In' ? 'Check-In' : 'Checkout'; ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Created By</label>
                        <p class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                            <?php echo htmlspecialchars($created_by); ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Created At</label>
                        <p class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg">
                            <?php echo date('M d, Y h:i A', strtotime($created_at)); ?>
                        </p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-4">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="2"
                        placeholder="Add any notes or comments about this transaction..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    ><?php echo htmlspecialchars($notes ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-lg p-6 border border-gray-200 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Transaction Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">SKU</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Current Stock</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Quantity *</th>
                                <?php if ($type === 'in'): ?>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Unit Cost</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total Cost</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Supplier</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="itemsTableBody">
                            <?php foreach ($items as $index => $item): ?>
                                <tr data-item-index="<?php echo $index; ?>">
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['product_name']); ?></p>
                                        <input type="hidden" name="items[<?php echo $index; ?>][product_id]" value="<?php echo $item['product_id']; ?>">
                                        <input type="hidden" name="items[<?php echo $index; ?>][movement_id]" value="<?php echo $item['id']; ?>">
                                        <input type="hidden" name="items[<?php echo $index; ?>][original_quantity]" value="<?php echo $item['quantity']; ?>">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($item['sku']); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-sm font-semibold">
                                            <?php echo number_format($item['current_quantity']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input
                                            type="number"
                                            name="items[<?php echo $index; ?>][quantity]"
                                            value="<?php echo $item['quantity']; ?>"
                                            min="1"
                                            step="1"
                                            required
                                            class="quantity-input w-24 px-3 py-2 border border-gray-300 rounded-lg text-center focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                                            data-index="<?php echo $index; ?>"
                                        >
                                    </td>
                                    <?php if ($type === 'in'): ?>
                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                name="items[<?php echo $index; ?>][unit_cost]"
                                                value="<?php echo $item['unit_cost'] ?? ''; ?>"
                                                min="0"
                                                step="0.01"
                                                placeholder="0.00"
                                                class="unit-cost-input w-28 px-3 py-2 border border-gray-300 rounded-lg text-right focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                                                data-index="<?php echo $index; ?>"
                                            >
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="total-cost-display text-sm font-bold text-green-600" data-index="<?php echo $index; ?>">
                                                <?php echo get_currency(); ?> <?php echo number_format($item['total_cost'] ?? 0, 2); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input
                                                type="text"
                                                name="items[<?php echo $index; ?>][supplier]"
                                                value="<?php echo htmlspecialchars($item['supplier'] ?? ''); ?>"
                                                placeholder="Supplier name"
                                                class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                                            >
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <?php if ($type === 'in'): ?>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Grand Total:</td>
                                    <td class="px-4 py-3 text-right">
                                        <span id="grandTotal" class="text-lg font-bold text-green-600">
                                            <?php echo get_currency(); ?> 0.00
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        All changes will be saved and stock quantities will be automatically adjusted
                    </p>
                    <div class="flex gap-3">
                        <a href="<?php echo base_url('products/transaction_details/' . urlencode($transaction_id)); ?>" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition"
                        >
                            <i class="fas fa-save mr-1"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';
const transactionType = '<?php echo $type; ?>';

// Calculate total cost for an item
function calculateItemTotal(index) {
    if (transactionType !== 'in') return;

    const quantityInput = document.querySelector(`.quantity-input[data-index="${index}"]`);
    const unitCostInput = document.querySelector(`.unit-cost-input[data-index="${index}"]`);
    const totalDisplay = document.querySelector(`.total-cost-display[data-index="${index}"]`);

    if (!quantityInput || !unitCostInput || !totalDisplay) return;

    const quantity = parseFloat(quantityInput.value) || 0;
    const unitCost = parseFloat(unitCostInput.value) || 0;
    const total = quantity * unitCost;

    totalDisplay.textContent = `${CURRENCY} ${total.toFixed(2)}`;

    // Update grand total
    calculateGrandTotal();
}

// Calculate grand total
function calculateGrandTotal() {
    if (transactionType !== 'in') return;

    let grandTotal = 0;
    document.querySelectorAll('.total-cost-display').forEach(display => {
        const text = display.textContent.replace(CURRENCY, '').trim();
        grandTotal += parseFloat(text) || 0;
    });

    const grandTotalElement = document.getElementById('grandTotal');
    if (grandTotalElement) {
        grandTotalElement.textContent = `${CURRENCY} ${grandTotal.toFixed(2)}`;
    }
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Quantity change listeners
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', function() {
            const index = this.getAttribute('data-index');
            calculateItemTotal(index);
        });
    });

    // Unit cost change listeners
    document.querySelectorAll('.unit-cost-input').forEach(input => {
        input.addEventListener('input', function() {
            const index = this.getAttribute('data-index');
            calculateItemTotal(index);
        });
    });

    // Initial calculation
    document.querySelectorAll('.quantity-input').forEach(input => {
        const index = input.getAttribute('data-index');
        calculateItemTotal(index);
    });

    // Form validation
    document.getElementById('editTransactionForm').addEventListener('submit', function(e) {
        let hasError = false;
        let errorMessage = '';

        // Check all quantities
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value);
            if (!quantity || quantity < 1) {
                hasError = true;
                errorMessage = 'All quantities must be at least 1';
            }
        });

        if (hasError) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }

        // Confirm submission
        if (!confirm('Are you sure you want to save these changes?\n\nThis will update stock quantities for all affected products.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
