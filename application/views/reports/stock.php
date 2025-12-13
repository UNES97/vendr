<!-- Inventory Stock Report -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Inventory Management</h2>
            <p class="text-gray-600 mt-1">Monitor stock levels, track low stock items, and manage inventory</p>
        </div>
        <a href="<?php echo base_url('reports'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Alert Section for Critical Stock -->
    <div id="critical-alerts" class="mb-6"></div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="total-products">0</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-boxes text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Inventory Value -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Inventory Value</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo get_currency(); ?> <span id="inventory-value">0</span></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Low Stock Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="low-stock-count">0</span></p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center text-yellow-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Out of Stock Items -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><span id="critical-stock-count">0</span></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-times-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Search -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Search Product</label>
                <input type="text" id="stock-search" placeholder="Search by name or SKU" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition">
            </div>

            <!-- Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Filter By Status</label>
                <select id="stock-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition">
                    <option value="all">All Products</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="critical">Out of Stock</option>
                </select>
            </div>

            <!-- Action Button -->
            <div class="flex items-end gap-2">
                <button onclick="loadInventoryData()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-900">Stock Levels</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">SKU</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Current Stock</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Min Level</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Max Level</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Cost Price</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">Stock Value</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody id="inventory-table-body">
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <p class="text-sm">Loading inventory data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination Controls -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span id="pagination-start">0</span> to <span id="pagination-end">0</span> of <span id="pagination-total">0</span> items
            </div>
            <div class="flex items-center gap-2">
                <button onclick="previousPage()" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50" id="prev-btn">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <div id="page-numbers" class="flex gap-1"></div>
                <button onclick="nextPage()" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition disabled:opacity-50" id="next-btn">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Flatpickr CSS for date pickers -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';
// Pagination configuration
const ITEMS_PER_PAGE = 10;
let allProducts = [];
let currentPage = 1;

// Load inventory data on page load
function loadInventoryData() {
    const search = document.getElementById('stock-search').value;
    const filter = document.getElementById('stock-filter').value;

    const url = new URL('<?php echo base_url('reports/get_inventory_data'); ?>', window.location.origin);
    if (search) {
        url.searchParams.append('search', search);
    }
    url.searchParams.append('filter', filter);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateSummaryCards(data);
            updateCriticalAlerts(data.products);
            allProducts = data.products;
            currentPage = 1;
            displayPage();
            updatePaginationControls();
        })
        .catch(error => {
            alert('Error loading inventory data: ' + error.message);
        });
}

// Update summary cards
function updateSummaryCards(data) {
    document.getElementById('total-products').textContent = data.total_products || 0;
    document.getElementById('low-stock-count').textContent = data.low_stock_count || 0;
    document.getElementById('critical-stock-count').textContent = data.critical_stock_count || 0;

    const value = parseFloat(data.total_inventory_value || 0).toFixed(0);
    document.getElementById('inventory-value').textContent = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Update critical stock alerts
function updateCriticalAlerts(products) {
    const alertsContainer = document.getElementById('critical-alerts');
    alertsContainer.innerHTML = '';

    const critical = products.filter(p => p.stock <= 0);

    if (critical.length > 0) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'bg-red-50 border-l-4 border-red-600 p-4 rounded-lg';
        alertDiv.innerHTML = `
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-red-600 mt-0.5 text-xl"></i>
                <div>
                    <h3 class="text-red-900 font-semibold text-sm mb-1">Out of Stock Alert</h3>
                    <p class="text-red-700 text-sm">${critical.length} product(s) are out of stock and need immediate reordering:</p>
                    <p class="text-red-700 text-sm mt-2 font-medium">${critical.map(p => p.name).join(', ')}</p>
                </div>
            </div>
        `;
        alertsContainer.appendChild(alertDiv);
    }

    const lowStock = products.filter(p => p.stock > 0 && p.stock <= p.min_stock_level);
    if (lowStock.length > 0) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'bg-yellow-50 border-l-4 border-yellow-600 p-4 rounded-lg';
        alertDiv.innerHTML = `
            <div class="flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 text-xl"></i>
                <div>
                    <h3 class="text-yellow-900 font-semibold text-sm mb-1">Low Stock Alert</h3>
                    <p class="text-yellow-700 text-sm">${lowStock.length} product(s) are below minimum stock level:</p>
                    <p class="text-yellow-700 text-sm mt-2 font-medium">${lowStock.map(p => `${p.name} (${p.stock}/${p.min_stock_level})`).join(', ')}</p>
                </div>
            </div>
        `;
        alertsContainer.appendChild(alertDiv);
    }
}

// Display current page
function displayPage() {
    const tbody = document.getElementById('inventory-table-body');
    tbody.innerHTML = '';

    if (!allProducts || allProducts.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500"><p class="text-sm">No inventory items found</p></td></tr>';
        return;
    }

    const startIdx = (currentPage - 1) * ITEMS_PER_PAGE;
    const endIdx = startIdx + ITEMS_PER_PAGE;
    const pageProducts = allProducts.slice(startIdx, endIdx);

    pageProducts.forEach(product => {
        const row = document.createElement('tr');
        row.className = 'border-b border-gray-200 hover:bg-gray-50 transition';

        const stockValue = (parseFloat(product.cost_price) * parseInt(product.stock)).toFixed(0);
        const formattedValue = stockValue.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        // Determine status badge
        let statusBadge = '';
        let statusClass = '';

        if (product.stock <= 0) {
            statusBadge = 'Out of Stock';
            statusClass = 'bg-red-100 text-red-800';
        } else if (product.stock <= product.min_stock_level) {
            statusBadge = 'Low Stock';
            statusClass = 'bg-yellow-100 text-yellow-800';
        } else if (product.stock >= product.max_stock_level) {
            statusBadge = 'Overstocked';
            statusClass = 'bg-orange-100 text-orange-800';
        } else {
            statusBadge = 'In Stock';
            statusClass = 'bg-green-100 text-green-800';
        }

        row.innerHTML = `
            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${product.name}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${product.sku || 'N/A'}</td>
            <td class="px-6 py-4 text-center text-sm font-semibold text-gray-900">${product.stock}</td>
            <td class="px-6 py-4 text-center text-sm text-gray-600">${product.min_stock_level}</td>
            <td class="px-6 py-4 text-center text-sm text-gray-600">${product.max_stock_level}</td>
            <td class="px-6 py-4 text-right text-sm text-gray-600">${CURRENCY} ${parseFloat(product.cost_price).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>
            <td class="px-6 py-4 text-right text-sm font-semibold text-gray-900">${CURRENCY} ${formattedValue}</td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 rounded-full text-xs font-medium ${statusClass}">
                    ${statusBadge}
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });

    // Update pagination info
    const total = allProducts.length;
    const displayStart = total === 0 ? 0 : startIdx + 1;
    const displayEnd = Math.min(endIdx, total);
    document.getElementById('pagination-start').textContent = displayStart;
    document.getElementById('pagination-end').textContent = displayEnd;
    document.getElementById('pagination-total').textContent = total;
}

// Update pagination controls
function updatePaginationControls() {
    const totalPages = Math.ceil(allProducts.length / ITEMS_PER_PAGE);
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pageNumbersDiv = document.getElementById('page-numbers');

    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages || totalPages === 0;

    pageNumbersDiv.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = `px-2 py-1 text-sm font-medium rounded transition ${
            i === currentPage
                ? 'bg-blue-600 text-white'
                : 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
        }`;
        btn.onclick = () => goToPage(i);
        pageNumbersDiv.appendChild(btn);
    }
}

// Navigation functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayPage();
        updatePaginationControls();
        window.scrollTo(0, 0);
    }
}

function nextPage() {
    const totalPages = Math.ceil(allProducts.length / ITEMS_PER_PAGE);
    if (currentPage < totalPages) {
        currentPage++;
        displayPage();
        updatePaginationControls();
        window.scrollTo(0, 0);
    }
}

function goToPage(pageNum) {
    currentPage = pageNum;
    displayPage();
    updatePaginationControls();
    window.scrollTo(0, 0);
}

// Load data on page load
window.addEventListener('DOMContentLoaded', function() {
    loadInventoryData();
});

// Add search/filter event listeners
document.getElementById('stock-search').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        loadInventoryData();
    }
});

document.getElementById('stock-filter').addEventListener('change', function() {
    loadInventoryData();
});
</script>
