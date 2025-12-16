<div class="max-w-full px-4 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Table Usage Analytics</h1>
                <p class="text-gray-600 mt-2">Track table occupancy, turnover rates, and usage patterns</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Quick Filter Buttons -->
            <div class="col-span-1 md:col-span-4 flex gap-2 mb-2">
                <button onclick="setDateRange('today')" class="quick-filter px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium transition">Today</button>
                <button onclick="setDateRange('week')" class="quick-filter active px-4 py-2 bg-red-600 text-white rounded text-sm font-medium transition">This Week</button>
                <button onclick="setDateRange('month')" class="quick-filter px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium transition">This Month</button>
                <button onclick="setDateRange('custom')" class="quick-filter px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded text-sm font-medium transition">Custom</button>
            </div>

            <!-- Date Inputs -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="text" id="start_date" placeholder="Select start date" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="text" id="end_date" placeholder="Select end date" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <!-- Table Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Table</label>
                <select id="table_filter" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">All Tables</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?php echo $table['id']; ?>">Table <?php echo $table['table_number']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Section Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                <select id="section_filter" class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">All Sections</option>
                    <?php foreach ($sections as $section): ?>
                        <option value="<?php echo htmlspecialchars($section['section']); ?>"><?php echo htmlspecialchars($section['section']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <button onclick="loadReportData()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded transition">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Data
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8" id="summary-cards">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Sessions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2" id="total-sessions">--</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Avg Duration</p>
                    <p class="text-3xl font-bold text-green-600 mt-2" id="avg-duration">--</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Tables</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2"><?php echo count($tables); ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                    <i class="fas fa-table text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Turnover Rate</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2" id="turnover-rate">--</p>
                    <p class="text-xs text-gray-500 mt-1">sessions/table/day</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                    <i class="fas fa-sync text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Used Tables -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>Most Used Tables
        </h2>
        <canvas id="mostUsedChart" height="80"></canvas>

        <!-- Detailed Table -->
        <div class="mt-6 overflow-x-auto">
            <table class="w-full" id="most-used-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Rank</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Table</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Section</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Uses</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Time</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Avg Duration</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Avg Idle</th>
                    </tr>
                </thead>
                <tbody id="most-used-body">
                    <tr><td colspan="7" class="text-center py-8 text-gray-500">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-clock text-blue-500 mr-2"></i>Peak Usage Hours
        </h2>
        <canvas id="peakHoursChart" height="80"></canvas>
    </div>

    <!-- All Tables Performance -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">
            <i class="fas fa-table text-purple-500 mr-2"></i>Table Performance Details
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full" id="table-details">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Table #</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Section</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Sessions Today</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Sessions This Week</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Total Sessions</th>
                    </tr>
                </thead>
                <tbody id="table-details-body">
                    <tr><td colspan="6" class="text-center py-8 text-gray-500">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let mostUsedChart = null;
let peakHoursChart = null;

// Initialize Flatpickr for date filters
flatpickr("#start_date", {
    mode: "single",
    dateFormat: "Y-m-d",
    monthSelectorType: "dropdown",
    defaultDate: new Date(new Date().setDate(new Date().getDate() - 7))
});

flatpickr("#end_date", {
    mode: "single",
    dateFormat: "Y-m-d",
    monthSelectorType: "dropdown",
    defaultDate: new Date()
});

// Load initial data
document.addEventListener('DOMContentLoaded', function() {
    loadReportData();
});

// Set date range quick filters
function setDateRange(range) {
    const today = new Date();
    let startDate, endDate;

    // Remove active class from all buttons
    document.querySelectorAll('.quick-filter').forEach(btn => {
        btn.classList.remove('active', 'bg-red-600', 'text-white');
        btn.classList.add('bg-gray-100');
    });

    switch(range) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'week':
            startDate = new Date(today);
            startDate.setDate(today.getDate() - 7);
            endDate = today;
            break;
        case 'month':
            startDate = new Date(today);
            startDate.setMonth(today.getMonth() - 1);
            endDate = today;
            break;
        case 'custom':
            // Just highlight the button, don't change dates
            event.target.classList.add('active', 'bg-red-600', 'text-white');
            event.target.classList.remove('bg-gray-100');
            return;
    }

    // Highlight active button
    event.target.classList.add('active', 'bg-red-600', 'text-white');
    event.target.classList.remove('bg-gray-100');

    // Update input fields
    document.getElementById('start_date').value = formatDate(startDate);
    document.getElementById('end_date').value = formatDate(endDate);

    // Load data
    loadReportData();
}

function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Load report data
function loadReportData() {
    const formData = new FormData();
    formData.append('start_date', document.getElementById('start_date').value);
    formData.append('end_date', document.getElementById('end_date').value);
    formData.append('table_id', document.getElementById('table_filter').value);
    formData.append('section', document.getElementById('section_filter').value);

    fetch('<?php echo base_url('reports/get_table_usage_data'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        updateSummary(data.summary);
        renderMostUsedChart(data.most_used);
        renderMostUsedTable(data.most_used);
        renderPeakHoursChart(data.peak_hours);
        renderTableDetails(data.table_details);
    })
    .catch(error => {
        console.error('Error loading data:', error);
        alert('Failed to load report data');
    });
}

// Update summary cards
function updateSummary(summary) {
    document.getElementById('total-sessions').textContent = summary.total_sessions || 0;
    document.getElementById('avg-duration').textContent = Math.round(summary.avg_duration || 0) + ' min';
    document.getElementById('turnover-rate').textContent = (summary.turnover_rate || 0).toFixed(2);
}

// Render Most Used Tables chart
function renderMostUsedChart(data) {
    const ctx = document.getElementById('mostUsedChart').getContext('2d');

    if (mostUsedChart) {
        mostUsedChart.destroy();
    }

    const labels = data.map(item => `Table ${item.table_number}`);
    const values = data.map(item => parseInt(item.usage_count));

    mostUsedChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Uses',
                data: values,
                backgroundColor: 'rgba(220, 38, 38, 0.8)',
                borderColor: 'rgba(220, 38, 38, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: "'Raleway', sans-serif" }
                    }
                },
                x: {
                    ticks: {
                        font: { family: "'Raleway', sans-serif" }
                    }
                }
            }
        }
    });
}

// Render most used table details
function renderMostUsedTable(data) {
    const tbody = document.getElementById('most-used-body');

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">No data available</td></tr>';
        return;
    }

    let html = '';
    data.forEach((item, index) => {
        const rank = index + 1;
        const totalTime = Math.round(item.total_minutes || 0);
        const avgDuration = Math.round(item.avg_duration || 0);
        const avgIdle = Math.round(item.avg_idle || 0);

        html += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">
                    ${rank <= 3 ? `<span class="text-yellow-500 font-bold">#${rank}</span>` : `#${rank}`}
                </td>
                <td class="px-4 py-3 font-medium">Table ${item.table_number}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${item.section || 'N/A'}</td>
                <td class="px-4 py-3"><span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">${item.usage_count}</span></td>
                <td class="px-4 py-3 text-sm text-gray-600">${formatMinutes(totalTime)}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${avgDuration} min</td>
                <td class="px-4 py-3 text-sm text-gray-600">${avgIdle} min</td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

// Render peak hours chart
function renderPeakHoursChart(data) {
    const ctx = document.getElementById('peakHoursChart').getContext('2d');

    if (peakHoursChart) {
        peakHoursChart.destroy();
    }

    const labels = data.map(item => item.hour);
    const values = data.map(item => parseInt(item.session_count));

    peakHoursChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sessions Started',
                data: values,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: "'Raleway', sans-serif" }
                    }
                },
                x: {
                    ticks: {
                        font: { family: "'Raleway', sans-serif" }
                    }
                }
            }
        }
    });
}

// Render table details
function renderTableDetails(data) {
    const tbody = document.getElementById('table-details-body');

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No data available</td></tr>';
        return;
    }

    let html = '';
    data.forEach(item => {
        const statusColors = {
            'available': 'bg-green-100 text-green-800',
            'occupied': 'bg-red-100 text-red-800',
            'reserved': 'bg-yellow-100 text-yellow-800',
            'maintenance': 'bg-gray-100 text-gray-800'
        };
        const statusColor = statusColors[item.status] || 'bg-gray-100 text-gray-800';

        html += `
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">Table ${item.table_number}</td>
                <td class="px-4 py-3 text-sm text-gray-600">${item.section || 'N/A'}</td>
                <td class="px-4 py-3"><span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor}">${item.status}</span></td>
                <td class="px-4 py-3 text-center">${item.sessions_today || 0}</td>
                <td class="px-4 py-3 text-center">${item.sessions_this_week || 0}</td>
                <td class="px-4 py-3 text-center"><span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">${item.total_sessions || 0}</span></td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

// Helper: Format minutes to hours and minutes
function formatMinutes(minutes) {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    if (hours > 0) {
        return `${hours}h ${mins}m`;
    }
    return `${mins}m`;
}
</script>
