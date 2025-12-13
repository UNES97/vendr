<!-- Expense Reports -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Expense Reports</h2>
            <p class="text-gray-600 mt-1">Analyze and visualize your expenses</p>
        </div>
        <a href="<?php echo base_url('expense'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- Start Date -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Start Date</label>
                <input type="text" id="report-start-date" placeholder="Select start date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">End Date</label>
                <input type="text" id="report-end-date" placeholder="Select end date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                <select id="report-category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end gap-2">
                <button onclick="loadReportData()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex-1">
                    <i class="fas fa-chart-bar mr-1"></i> Generate
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Expenses -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Expenses</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">PKR <span id="total-amount">0</span></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Records -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Records</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="total-count">0</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Expense -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Average Expense</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">PKR <span id="avg-amount">0</span></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Highest Expense -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Highest Expense</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">PKR <span id="max-amount">0</span></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600">
                    <i class="fas fa-arrow-up text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Category Breakdown Chart -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Expenses by Category</h3>
            <canvas id="categoryChart" height="200"></canvas>
        </div>

        <!-- Payment Method Breakdown Chart -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Method Distribution</h3>
            <canvas id="paymentChart" height="200"></canvas>
        </div>
    </div>

    <!-- Top Expenses Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Top 10 Expenses</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Payment</th>
                    </tr>
                </thead>
                <tbody id="top-expenses-body">
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">Select date range to view expenses</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let categoryChart = null;
let paymentChart = null;

// Initialize Flatpickr for date filters
flatpickr("#report-start-date", {
    mode: "single",
    dateFormat: "Y-m-d",
    monthSelectorType: "dropdown",
    defaultDate: new Date(new Date().setMonth(new Date().getMonth() - 1))
});

flatpickr("#report-end-date", {
    mode: "single",
    dateFormat: "Y-m-d",
    monthSelectorType: "dropdown",
    defaultDate: new Date()
});

// Load report data
function loadReportData() {
    const startDate = document.getElementById('report-start-date').value;
    const endDate = document.getElementById('report-end-date').value;
    const categoryId = document.getElementById('report-category').value;

    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }

    const url = new URL('<?php echo base_url('expense/get_statistics'); ?>', window.location.origin);
    url.searchParams.append('start_date', startDate);
    url.searchParams.append('end_date', endDate);
    if (categoryId) {
        url.searchParams.append('category_id', categoryId);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateSummary(data);
            updateCharts(data);
            updateTopExpensesTable(data);
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
}

// Update summary cards
function updateSummary(data) {
    const totalAmount = data.total || 0;
    const totalCount = data.count || 0;
    const avgAmount = totalCount > 0 ? (totalAmount / totalCount) : 0;
    const maxAmount = data.max_amount || 0;

    document.getElementById('total-amount').textContent =
        parseFloat(totalAmount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    document.getElementById('total-count').textContent = totalCount;
    document.getElementById('avg-amount').textContent =
        parseFloat(avgAmount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    document.getElementById('max-amount').textContent =
        parseFloat(maxAmount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update charts
function updateCharts(data) {
    updateCategoryChart(data.categories);
    updatePaymentChart(data.payment_methods);
}

// Update category chart
function updateCategoryChart(categories) {
    const ctx = document.getElementById('categoryChart').getContext('2d');

    if (categoryChart) {
        categoryChart.destroy();
    }

    const labels = categories.map(c => c.name);
    const amounts = categories.map(c => parseFloat(c.total) || 0);

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: amounts,
                backgroundColor: [
                    '#ef4444',
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#8b5cf6',
                    '#ec4899',
                    '#06b6d4',
                    '#84cc16'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15
                    }
                }
            }
        }
    });
}

// Update payment method chart
function updatePaymentChart(paymentMethods) {
    const ctx = document.getElementById('paymentChart').getContext('2d');

    if (paymentChart) {
        paymentChart.destroy();
    }

    const labels = paymentMethods.map(p => p.payment_method.charAt(0).toUpperCase() + p.payment_method.slice(1));
    const amounts = paymentMethods.map(p => parseFloat(p.total) || 0);
    const counts = paymentMethods.map(p => p.count || 0);

    paymentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount (PKR)',
                data: amounts,
                backgroundColor: '#dc2626',
                borderColor: '#991b1b',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        font: { size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'PKR ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Update top expenses table
function updateTopExpensesTable(data) {
    const tbody = document.getElementById('top-expenses-body');
    tbody.innerHTML = '';

    if (!data.top_expenses || data.top_expenses.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500"><p class="text-sm">No expenses found</p></td></tr>';
        return;
    }

    data.top_expenses.forEach(expense => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200 hover:bg-gray-50 transition';
        row.innerHTML = `
            <td class="px-6 py-4 text-sm text-gray-600">${new Date(expense.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">${expense.description.substring(0, 40)}</td>
            <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">${expense.category_name}</span></td>
            <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">PKR ${parseFloat(expense.amount).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="px-6 py-4 text-sm"><span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase">${expense.payment_method}</span></td>
        `;
        tbody.appendChild(row);
    });
}

// Load initial data on page load
window.addEventListener('DOMContentLoaded', function() {
    loadReportData();
});
</script>
