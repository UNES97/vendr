<!-- Expenses Management -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Expenses</h2>
            <p class="text-gray-600 mt-1">Manage and track all business expenses</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo base_url('expense/categories'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-tag"></i> Categories
            </a>
            <a href="<?php echo base_url('expense/add'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Expense
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-900 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo get_currency(); ?> <span id="total-amount"><?php echo number_format($total_expenses, 0); ?></span></p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-900">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="total-count"><?php echo count($expenses); ?></span></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Categories</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo count($categories); ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-tag text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Average Expense</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo get_currency(); ?> <span id="avg-amount"><?php echo $total_expenses > 0 ? number_format($total_expenses / count($expenses), 0) : 0; ?></span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                <select id="filter-category" onchange="filterExpenses()" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="text" id="filter-start-date" placeholder="Select start date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">End Date</label>
                <input type="text" id="filter-end-date" placeholder="Select end date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                <input type="text" id="filter-search" placeholder="Description or Ref #" onchange="filterExpenses()" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button onclick="filterExpenses()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex-1">
                    <i class="fas fa-search"></i>
                </button>
                <button onclick="resetFilters()" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm flex-1" title="Reset filters">
                    <i class="fas fa-redo"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Created By</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="expenses-table-body">
                    <?php if (empty($expenses)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 opacity-30"></i>
                                <p class="text-lg">No expenses found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($expense['created_at'])); ?></td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo htmlspecialchars(substr($expense['description'], 0, 40)); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <?php echo htmlspecialchars($expense['category_name']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo format_price($expense['amount']); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">
                                        <?php echo htmlspecialchars($expense['payment_method']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($expense['reference_number'] ?? '-'); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($expense['created_by_name'] ?? 'System'); ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="viewExpenseDetails(<?php echo $expense['id']; ?>)" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if (!empty($expense['attachment'])): ?>
                                            <a href="<?php echo base_url('expense/download_attachment/' . $expense['id']); ?>" class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition" title="Download Attachment">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button onclick="deleteExpense(<?php echo $expense['id']; ?>)" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';

// Initialize Flatpickr for date filters
flatpickr("#filter-start-date", {
    mode: "single",
    dateFormat: "Y-m-d",
    onChange: function() { filterExpenses(); }
});

flatpickr("#filter-end-date", {
    mode: "single",
    dateFormat: "Y-m-d",
    onChange: function() { filterExpenses(); }
});

// Delete expense
function deleteExpense(expenseId) {
    if (!confirm('Are you sure you want to delete this expense?')) {
        return;
    }

    const url = '<?php echo base_url('expense/delete/'); ?>' + expenseId;
    fetch(url, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                filterExpenses();
                alert(data.message);
            } else {
                alert(data.error || 'Failed to delete expense');
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
}

// Filter expenses
function filterExpenses() {
    const category = document.getElementById('filter-category').value;
    const startDate = document.getElementById('filter-start-date').value;
    const endDate = document.getElementById('filter-end-date').value;
    const search = document.getElementById('filter-search').value;

    const params = new URLSearchParams();
    if (category) params.append('category_id', category);
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (search) params.append('search', search);

    const url = '<?php echo base_url('expense/get_expenses'); ?>' + (params.toString() ? '?' + params.toString() : '');

    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateExpensesTable(data.expenses);
            document.getElementById('total-amount').textContent = data.total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById('total-count').textContent = data.count;
            document.getElementById('avg-amount').textContent = data.count > 0 ? (data.total / data.count).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",") : 0;
        })
        .catch(error => {
            alert('Error fetching expenses: ' + error.message);
        });
}

// Update expenses table
function updateExpensesTable(expenses) {
    const tbody = document.getElementById('expenses-table-body');
    tbody.innerHTML = '';

    if (expenses.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-inbox text-4xl mb-2 opacity-30"></i><p class="text-lg">No expenses found</p></td></tr>';
        return;
    }

    expenses.forEach(expense => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200 hover:bg-gray-50 transition';
        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-gray-600">${new Date(expense.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">${expense.description.substring(0, 40)}</td>
            <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">${expense.category_name}</span></td>
            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${CURRENCY} ${parseFloat(expense.amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">${expense.payment_method}</span></td>
            <td class="px-6 py-4 text-sm text-gray-600">${expense.reference_number || '-'}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${expense.created_by_name || 'System'}</td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center space-x-2">
                    <button onclick="viewExpenseDetails(${expense.id})" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${expense.attachment ? `<a href="<?php echo base_url('expense/download_attachment/'); ?>${expense.id}" class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition" title="Download Attachment"><i class="fas fa-download"></i></a>` : ''}
                    <button onclick="deleteExpense(${expense.id})" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Reset filters
function resetFilters() {
    document.getElementById('filter-category').value = '';
    document.getElementById('filter-start-date').value = '';
    document.getElementById('filter-end-date').value = '';
    document.getElementById('filter-search').value = '';
    filterExpenses();
}

// View expense details
function viewExpenseDetails(expenseId) {
    const url = '<?php echo base_url('expense/get_expense/'); ?>' + expenseId;
    fetch(url)
        .then(response => response.json())
        .then(expense => {
            // Populate modal with data
            document.getElementById('detail-date').textContent = new Date(expense.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            document.getElementById('detail-category').textContent = expense.category_name;
            document.getElementById('detail-amount').textContent = CURRENCY + ' ' + parseFloat(expense.amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById('detail-description').textContent = expense.description;
            document.getElementById('detail-payment-method').textContent = expense.payment_method.charAt(0).toUpperCase() + expense.payment_method.slice(1);
            document.getElementById('detail-reference').textContent = expense.reference_number || '-';
            document.getElementById('detail-notes').textContent = expense.notes || '-';
            document.getElementById('detail-created-by').textContent = expense.created_by_name || 'System';

            // Handle attachment
            const attachmentSection = document.getElementById('detail-attachment-section');
            if (expense.attachment) {
                attachmentSection.innerHTML = `
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file text-2xl text-green-600"></i>
                                <div>
                                    <p class="font-semibold text-green-900">Attachment Available</p>
                                    <p class="text-sm text-green-700">${expense.attachment}</p>
                                </div>
                            </div>
                            <a href="<?php echo base_url('expense/download_attachment/'); ?>${expense.id}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold flex items-center gap-2">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                `;
            } else {
                attachmentSection.innerHTML = '<p class="text-gray-500 text-sm italic">No attachment</p>';
            }

            // Show modal
            document.getElementById('details-modal').classList.remove('hidden');
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
}

// Close details modal
function closeDetailsModal() {
    document.getElementById('details-modal').classList.add('hidden');
}
</script>

<!-- Expense Details Modal -->
<div id="details-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl max-h-screen overflow-y-auto">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between sticky top-0">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-receipt"></i>
                Expense Details
            </h2>
            <button onclick="closeDetailsModal()" class="text-white hover:text-red-100 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Date</label>
                    <p id="detail-date" class="text-lg font-semibold text-gray-900">-</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Category</label>
                    <p id="detail-category" class="text-lg font-semibold text-red-600">-</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Amount</label>
                    <p id="detail-amount" class="text-2xl font-bold text-gray-900"><?php echo get_currency(); ?> 0</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-1">Payment Method</label>
                    <span id="detail-payment-method" class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">-</span>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Description</label>
                <p id="detail-description" class="text-gray-700 bg-gray-50 p-4 rounded-lg">-</p>
            </div>

            <!-- Reference and Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Reference Number</label>
                    <p id="detail-reference" class="text-gray-700 bg-gray-50 p-3 rounded-lg">-</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Created By</label>
                    <p id="detail-created-by" class="text-gray-700 bg-gray-50 p-3 rounded-lg">-</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Notes</label>
                <p id="detail-notes" class="text-gray-700 bg-gray-50 p-4 rounded-lg min-h-20">-</p>
            </div>

            <!-- Attachment -->
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-2">Attachment</label>
                <div id="detail-attachment-section">
                    <p class="text-gray-500 text-sm italic">Loading...</p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end sticky bottom-0">
            <button onclick="closeDetailsModal()" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>
