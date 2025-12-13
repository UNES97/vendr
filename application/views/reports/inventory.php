<!-- Meal Sales Performance Report -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Top Selling Meals</h2>
            <p class="text-gray-600 mt-1">Analyze your top-selling meals, revenue trends, and performance metrics</p>
        </div>
        <a href="<?php echo base_url('reports'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Revenue from Meals -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo get_currency(); ?> <span id="total-revenue">0</span></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Meals Sold -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Meals Sold</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="total-items">0</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-utensils text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Unique Meals Sold -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Unique Meals Sold</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="unique-products">0</span></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-list text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Revenue per Meal -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Avg Revenue/Meal</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo get_currency(); ?> <span id="avg-revenue">0</span></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
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

            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Meal Search</label>
                <input type="text" id="report-search" placeholder="Search meal name" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>
        </div>
        <div class="flex gap-2 pt-3">
            <button onclick="loadPerformanceData()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-search"></i> Generate Report
            </button>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Meals Chart -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top 10 Meals by Revenue</h3>
            <div style="height: 300px;">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>

        <!-- Meal Revenue Distribution Chart -->
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Meal Revenue Distribution</h3>
            <div style="height: 300px;">
                <canvas id="revenueDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Meals Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Top Selling Meals</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Meal Name</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Servings</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Orders</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Total Revenue</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Price</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">Select date range to view data</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination Controls -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span id="pagination-start">1</span> to <span id="pagination-end">10</span> of <span id="pagination-total">0</span> items
            </div>
            <div class="flex gap-2 items-center">
                <button onclick="previousPage()" id="prev-btn" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
                <div id="pagination-numbers" class="flex gap-1"></div>
                <button onclick="nextPage()" id="next-btn" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';
let topProductsChart = null;
let revenueDistributionChart = null;
const ITEMS_PER_PAGE = 10;
let currentPage = 1;
let allMeals = [];

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

// Load performance data
function loadPerformanceData() {
    const startDate = document.getElementById('report-start-date').value;
    const endDate = document.getElementById('report-end-date').value;
    const search = document.getElementById('report-search').value;

    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }

    const url = new URL('<?php echo base_url('reports/get_product_performance_data'); ?>', window.location.origin);
    url.searchParams.append('start_date', startDate);
    url.searchParams.append('end_date', endDate);
    if (search) {
        url.searchParams.append('search', search);
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            allMeals = data.top_products;
            currentPage = 1;
            updateSummaryCards(data);
            updateTopProductsChart(data.top_products);
            updateRevenueDistributionChart(data.by_product);
            displayPage();
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
}

// Update summary cards
function updateSummaryCards(data) {
    document.getElementById('total-revenue').textContent =
        parseFloat(data.total_revenue || 0).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    document.getElementById('total-items').textContent = Math.round(data.total_items_sold || 0);
    document.getElementById('unique-products').textContent = data.unique_products || 0;
    document.getElementById('avg-revenue').textContent =
        parseFloat(data.avg_revenue_per_product || 0).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update top products chart
function updateTopProductsChart(products) {
    const ctx = document.getElementById('topProductsChart').getContext('2d');

    if (topProductsChart) {
        topProductsChart.destroy();
    }

    const labels = products.map(p => p.name.substring(0, 20));
    const revenues = products.map(p => parseFloat(p.total_revenue) || 0);

    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (' + CURRENCY + ')',
                data: revenues,
                backgroundColor: '#dc2626',
                borderColor: '#991b1b',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: {
                    labels: {
                        font: { size: 11, family: "'Raleway', sans-serif", weight: '600' }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        font: { family: "'Raleway', sans-serif" },
                        callback: function(value) {
                            return CURRENCY + ' ' + value.toLocaleString();
                        }
                    }
                },
                y: {
                    ticks: {
                        font: { family: "'Raleway', sans-serif" }
                    }
                }
            }
        }
    });
}

// Update revenue distribution chart
function updateRevenueDistributionChart(products) {
    const ctx = document.getElementById('revenueDistributionChart').getContext('2d');

    if (revenueDistributionChart) {
        revenueDistributionChart.destroy();
    }

    const labels = products.slice(0, 8).map(p => p.name.substring(0, 15));
    const revenues = products.slice(0, 8).map(p => parseFloat(p.revenue) || 0);

    revenueDistributionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: revenues,
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
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 10, family: "'Raleway', sans-serif", weight: '600' },
                        padding: 12
                    }
                }
            }
        }
    });
}

// Display current page of meals
function displayPage() {
    const tbody = document.getElementById('products-table-body');
    tbody.innerHTML = '';

    if (!allMeals || allMeals.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500"><p class="text-sm">No meals found</p></td></tr>';
        updatePaginationControls();
        return;
    }

    const startIdx = (currentPage - 1) * ITEMS_PER_PAGE;
    const endIdx = startIdx + ITEMS_PER_PAGE;
    const pageMeals = allMeals.slice(startIdx, endIdx);

    pageMeals.forEach((meal, index) => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200 hover:bg-gray-50 transition';
        const actualIndex = startIdx + index + 1;
        row.innerHTML = `
            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                <span class="inline-block w-6 h-6 rounded-full bg-red-600 text-white text-xs flex items-center justify-center mr-2" style="font-size: 10px;">${actualIndex}</span>
                ${meal.name}
            </td>
            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">${Math.round(meal.total_quantity || 0)}</td>
            <td class="px-6 py-4 text-center text-sm text-gray-600">${meal.total_sold || 0}</td>
            <td class="px-6 py-4 text-sm font-semibold text-right text-gray-900">${CURRENCY} ${parseFloat(meal.total_revenue).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="px-6 py-4 text-sm text-right">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    ${CURRENCY} ${parseFloat(meal.selling_price || 0).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });

    updatePaginationControls();
    window.scrollTo(0, 0);
}

// Update pagination controls
function updatePaginationControls() {
    const totalPages = Math.ceil(allMeals.length / ITEMS_PER_PAGE);
    const startIdx = (currentPage - 1) * ITEMS_PER_PAGE + 1;
    const endIdx = Math.min(currentPage * ITEMS_PER_PAGE, allMeals.length);

    document.getElementById('pagination-start').textContent = allMeals.length === 0 ? 0 : startIdx;
    document.getElementById('pagination-end').textContent = endIdx;
    document.getElementById('pagination-total').textContent = allMeals.length;

    // Update prev/next buttons
    document.getElementById('prev-btn').disabled = currentPage === 1;
    document.getElementById('next-btn').disabled = currentPage === totalPages;

    // Generate page number buttons
    const paginationNumbers = document.getElementById('pagination-numbers');
    paginationNumbers.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `px-2 py-1 rounded-lg text-sm transition ${
            i === currentPage
                ? 'bg-red-600 text-white'
                : 'border border-gray-300 hover:bg-gray-100'
        }`;
        btn.onclick = () => goToPage(i);
        paginationNumbers.appendChild(btn);
    }
}

// Navigation functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage();
    }
}

function nextPage() {
    const totalPages = Math.ceil(allMeals.length / ITEMS_PER_PAGE);
    if (currentPage < totalPages) {
        currentPage++;
        displayPage();
    }
}

function goToPage(page) {
    currentPage = page;
    displayPage();
}

// Load initial data on page load
window.addEventListener('DOMContentLoaded', function() {
    loadPerformanceData();
});
</script>
