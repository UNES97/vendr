<!-- Waiter Dashboard -->
<div class="h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between shadow-lg">
        <div>
            <h1 class="text-3xl font-bold text-white">Waiter Dashboard</h1>
            <p class="text-blue-100 text-sm">Track and manage table orders</p>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-blue-100 text-sm">Current Time</p>
                <p class="text-2xl font-bold text-white" id="current-time">00:00:00</p>
            </div>
            <!-- User Menu -->
            <div class="flex items-center gap-3 border-l border-blue-500 pl-6">
                <div class="text-right">
                    <p class="text-white text-sm font-semibold"><?php echo user_name(); ?></p>
                    <p class="text-blue-100 text-xs"><?php echo ucfirst(user_role()); ?></p>
                </div>
                <div class="w-10 h-10 bg-blue-800 rounded-full flex items-center justify-center text-white font-bold border border-blue-500">
                    <?php echo get_user_initials(); ?>
                </div>
                <a href="<?php echo base_url('auth/logout'); ?>" class="ml-2 px-3 py-1 bg-blue-800 hover:bg-blue-900 text-white rounded text-sm font-semibold transition">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Status Legend Bar -->
    <div class="bg-gray-800 px-6 py-3 flex items-center justify-between border-b border-gray-700 shadow-md">
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-xs text-gray-300 font-medium">Available</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-xs text-gray-300 font-medium">Ordering</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                <span class="text-xs text-gray-300 font-medium">Preparing</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                <span class="text-xs text-gray-300 font-medium">Ready to Serve</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                <span class="text-xs text-gray-300 font-medium">Awaiting Payment</span>
            </div>
        </div>
        <button onclick="refreshTables()" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-xs flex items-center gap-1">
            <i class="fas fa-sync-alt"></i>Refresh
        </button>
    </div>

    <!-- Tables Grid -->
    <div class="flex-1 overflow-y-auto p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="tables-container">
            <?php foreach ($tables as $table): ?>
                <?php
                    $hasOrder = !empty($table['order_id']);
                    $status = $hasOrder ? $table['order_status'] : 'available';
                    $paymentStatus = $hasOrder ? $table['payment_status'] : '';

                    if ($status === 'available') {
                        $bgColor = 'bg-gradient-to-br from-green-900 to-green-800 border-green-600 hover:border-green-500';
                        $statusDot = 'bg-green-500';
                        $statusText = 'Available';
                        $clickable = false;
                    } elseif ($status === 'ready' && $paymentStatus === 'pending') {
                        $bgColor = 'bg-gradient-to-br from-orange-900 to-orange-800 border-orange-600 hover:border-orange-500';
                        $statusDot = 'bg-orange-500';
                        $statusText = 'Awaiting Payment';
                        $clickable = true;
                    } elseif ($status === 'preparing') {
                        $bgColor = 'bg-gradient-to-br from-purple-900 to-purple-800 border-purple-600 hover:border-purple-500';
                        $statusDot = 'bg-purple-500';
                        $statusText = 'Preparing';
                        $clickable = true;
                    } else {
                        $bgColor = 'bg-gradient-to-br from-blue-900 to-blue-800 border-blue-600 hover:border-blue-500';
                        $statusDot = 'bg-blue-500';
                        $statusText = ucfirst(str_replace('_', ' ', $status));
                        $clickable = true;
                    }
                ?>
                <div
                    class="<?php echo $bgColor; ?> rounded-lg border-2 p-4 cursor-pointer transition transform hover:scale-105 shadow-lg"
                    onclick="<?php echo $clickable && $hasOrder ? 'showTableModal(' . $table['id'] . ')' : ''; ?>"
                    data-table-id="<?php echo $table['id']; ?>"
                    id="table-<?php echo $table['id']; ?>"
                >
                    <!-- Status Indicator -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="<?php echo $statusDot; ?> w-3 h-3 rounded-full"></div>
                            <span class="text-xs text-gray-300 font-bold uppercase"><?php echo $statusText; ?></span>
                        </div>
                        <?php if ($hasOrder): ?>
                            <span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold"><?php echo $table['item_count']; ?> items</span>
                        <?php endif; ?>
                    </div>

                    <!-- Table Number -->
                    <div class="mb-3">
                        <h3 class="text-2xl font-bold text-white">Table <?php echo $table['table_number']; ?></h3>
                        <p class="text-xs text-gray-400">Capacity: <?php echo $table['capacity']; ?> persons</p>
                    </div>

                    <!-- Order Info -->
                    <?php if ($hasOrder): ?>
                        <div class="bg-gray-900 bg-opacity-60 rounded p-3 mb-3 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400">Order #</span>
                                <span class="text-sm font-bold text-white"><?php echo htmlspecialchars($table['order_number']); ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400">Amount</span>
                                <span class="text-sm font-bold text-green-400"><?php echo get_currency(); ?> <?php echo number_format($table['total_amount'], 0); ?></span>
                            </div>
                        </div>

                        <!-- Quick Action Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                onclick="event.stopPropagation(); markTableAsReady(<?php echo $table['id']; ?>)"
                                class="px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition flex items-center justify-center gap-1"
                                <?php echo ($status === 'ready') ? 'disabled' : ''; ?>
                            >
                                <i class="fas fa-check text-xs"></i>Ready
                            </button>
                            <button
                                onclick="event.stopPropagation(); showTableModal(<?php echo $table['id']; ?>)"
                                class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded transition flex items-center justify-center gap-1"
                            >
                                <i class="fas fa-eye text-xs"></i>View
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-green-400 text-3xl mb-2 opacity-70"></i>
                            <p class="text-gray-300 font-semibold text-sm">Ready for guests</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Table Details Modal -->
<div id="table-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
    <div class="bg-gray-900 rounded-lg shadow-2xl w-full max-w-2xl max-h-screen flex flex-col overflow-hidden border border-gray-700">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 flex items-center justify-between border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-table"></i>
                <span id="modal-table-number">Table</span>
            </h2>
            <button onclick="closeTableModal()" class="text-white hover:text-blue-200 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 max-h-80 overflow-y-auto flex-1">
            <div id="modal-content" class="space-y-4">
                <div class="text-center text-gray-400">
                    <i class="fas fa-spinner fa-spin text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-800 px-6 py-4 flex gap-3 justify-end border-t border-gray-700 flex-wrap">
            <button
                onclick="markTableAsServed()"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm"
                id="serve-btn"
            >
                <i class="fas fa-check-circle"></i>
                Served
            </button>
            <button
                onclick="markTableAsReady(currentTableId)"
                class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm"
                id="ready-btn"
            >
                <i class="fas fa-check"></i>
                Ready
            </button>
            <button
                onclick="requestPaymentModal()"
                class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm"
                id="payment-btn"
            >
                <i class="fas fa-credit-card"></i>
                Payment
            </button>
            <button
                onclick="closeTableModal()"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition text-sm"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';
let currentTableId = null;

// Update time
setInterval(updateTime, 1000);

function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString();
}

// Show table details modal
function showTableModal(tableId) {
    currentTableId = tableId;
    const modal = document.getElementById('table-modal');
    modal.classList.remove('hidden');

    const url = '<?php echo base_url('waiter/get_table_bill/'); ?>' + tableId;
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load bill');
            return response.json();
        })
        .then(order => {
            document.getElementById('modal-table-number').textContent = 'Table ' + order.table_id;
            renderTableDetails(order);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modal-content').innerHTML = '<p class="text-red-500">Failed to load details: ' + error.message + '</p>';
        });
}

function renderTableDetails(order) {
    let itemsHtml = '<div class="space-y-2">';

    order.items.forEach(item => {
        const unitPrice = parseFloat(item.unit_price);
        const quantity = parseInt(item.quantity);
        const itemTotal = (unitPrice * quantity).toFixed(0);

        itemsHtml += `
            <div class="flex justify-between items-start pb-2 border-b border-gray-700">
                <div>
                    <p class="font-semibold text-white">${quantity}x ${item.meal_name || 'Item #' + item.meal_id}</p>
                    ${item.meal_description ? `<p class="text-xs text-gray-400">${item.meal_description}</p>` : ''}
                </div>
                <span class="font-bold text-gray-300">${CURRENCY} ${itemTotal}</span>
            </div>
        `;
    });

    itemsHtml += '</div>';

    const subtotal = parseFloat(order.subtotal).toFixed(0);
    const tax = parseFloat(order.tax_amount).toFixed(0);
    const total = parseFloat(order.total_amount).toFixed(0);
    const discount = parseFloat(order.discount_amount).toFixed(0);

    const detailsHtml = `
        <div class="space-y-4">
            <!-- Order Info -->
            <div class="bg-gray-800 rounded-lg p-3 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400">Order #</p>
                    <p class="text-white font-bold">${order.order_number}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Status</p>
                    <p class="text-white font-bold">${order.order_status.toUpperCase()}</p>
                </div>
            </div>

            <!-- Items -->
            <div>
                <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                    <i class="fas fa-utensils text-xs"></i>
                    Order Items
                </h3>
                ${itemsHtml}
            </div>

            <!-- Bill Summary -->
            <div class="bg-gray-800 rounded-lg p-3 space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-400">Subtotal:</span>
                    <span class="font-semibold text-white">${CURRENCY} ${subtotal}</span>
                </div>
                ${tax > 0 ? `
                    <div class="flex justify-between">
                        <span class="text-gray-400">Tax:</span>
                        <span class="font-semibold text-white">${CURRENCY} ${tax}</span>
                    </div>
                ` : ''}
                ${discount > 0 ? `
                    <div class="flex justify-between text-green-400">
                        <span>Discount:</span>
                        <span class="font-semibold">-${CURRENCY} ${discount}</span>
                    </div>
                ` : ''}
                <div class="border-t border-gray-700 pt-2 flex justify-between">
                    <span class="text-white font-bold">Total:</span>
                    <span class="text-lg font-bold text-green-400">${CURRENCY} ${total}</span>
                </div>
            </div>
        </div>
    `;

    document.getElementById('modal-content').innerHTML = detailsHtml;

    // Update button visibility based on status
    const serveBtn = document.getElementById('serve-btn');
    const readyBtn = document.getElementById('ready-btn');
    const paymentBtn = document.getElementById('payment-btn');

    if (order.order_status === 'ready' && order.payment_status === 'pending') {
        serveBtn.style.display = 'flex';
        readyBtn.style.display = 'none';
        paymentBtn.style.display = 'flex';
    } else if (order.order_status === 'ready') {
        serveBtn.style.display = 'flex';
        readyBtn.style.display = 'none';
        paymentBtn.style.display = 'none';
    } else {
        serveBtn.style.display = 'none';
        readyBtn.style.display = 'flex';
        paymentBtn.style.display = 'none';
    }
}

function closeTableModal() {
    document.getElementById('table-modal').classList.add('hidden');
    currentTableId = null;
}

function markTableAsReady(tableId) {
    if (!tableId) return;

    // Get the table element to find the order ID
    const tableElement = document.getElementById('table-' + tableId);
    if (!tableElement) {
        alert('Table not found');
        return;
    }

    // Get the order ID from the modal (it should be set when the modal was opened)
    const billUrl = '<?php echo base_url('waiter/get_table_bill/'); ?>' + tableId;
    fetch(billUrl)
        .then(response => response.json())
        .then(order => {
            if (!order.id) {
                alert('Order not found');
                return;
            }
            // Now update the order status with the order ID
            const url = '<?php echo base_url('waiter/update_order_status/'); ?>' + order.id + '/ready';
            return fetch(url).then(res => res.json());
        })
        .then(data => {
            closeTableModal();
            refreshTables();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update table status');
        });
}

function markTableAsServed() {
    if (!currentTableId) return;

    if (!confirm('Mark this table as served and close it?')) {
        return;
    }

    const url = '<?php echo base_url('waiter/close_table/'); ?>' + currentTableId;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            closeTableModal();
            refreshTables();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to close table');
        });
}

function requestPaymentModal() {
    if (!currentTableId) return;

    const url = '<?php echo base_url('waiter/request_payment/'); ?>' + currentTableId;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            alert('Payment request sent to table');
            closeTableModal();
            refreshTables();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to request payment');
        });
}

function refreshTables() {
    const url = '<?php echo base_url('waiter/get_tables_status'); ?>';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateTablesDisplay(data.tables);
        })
        .catch(error => console.error('Failed to refresh tables:', error));
}

function updateTablesDisplay(tables) {
    tables.forEach(table => {
        const tableElement = document.getElementById('table-' + table.id);
        if (!tableElement) return;

        const hasOrder = table.order_id;
        const status = hasOrder ? table.order_status : 'available';
        const paymentStatus = hasOrder ? table.payment_status : '';

        // Determine status colors
        let bgColor, statusDot, statusText;
        if (status === 'available') {
            bgColor = 'bg-gradient-to-br from-green-900 to-green-800 border-green-600 hover:border-green-500';
            statusDot = 'bg-green-500';
            statusText = 'Available';
        } else if (status === 'ready' && paymentStatus === 'pending') {
            bgColor = 'bg-gradient-to-br from-orange-900 to-orange-800 border-orange-600 hover:border-orange-500';
            statusDot = 'bg-orange-500';
            statusText = 'Awaiting Payment';
        } else if (status === 'preparing') {
            bgColor = 'bg-gradient-to-br from-purple-900 to-purple-800 border-purple-600 hover:border-purple-500';
            statusDot = 'bg-purple-500';
            statusText = 'Preparing';
        } else {
            bgColor = 'bg-gradient-to-br from-blue-900 to-blue-800 border-blue-600 hover:border-blue-500';
            statusDot = 'bg-blue-500';
            statusText = status.charAt(0).toUpperCase() + status.slice(1);
        }

        // Update classes
        tableElement.className = bgColor + ' rounded-lg border-2 p-4 cursor-pointer transition transform hover:scale-105 shadow-lg';
        tableElement.onclick = hasOrder ? () => showTableModal(table.id) : null;

        // Update content
        let contentHtml = `
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="${statusDot} w-3 h-3 rounded-full"></div>
                    <span class="text-xs text-gray-300 font-bold uppercase">${statusText}</span>
                </div>
                ${hasOrder ? `<span class="bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">${table.item_count} items</span>` : ''}
            </div>

            <div class="mb-3">
                <h3 class="text-2xl font-bold text-white">Table ${table.table_number}</h3>
                <p class="text-xs text-gray-400">Capacity: ${table.capacity} persons</p>
            </div>
        `;

        if (hasOrder) {
            contentHtml += `
                <div class="bg-gray-900 bg-opacity-60 rounded p-3 mb-3 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Order #</span>
                        <span class="text-sm font-bold text-white">${table.order_number}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-400">Amount</span>
                        <span class="text-sm font-bold text-green-400">${CURRENCY} ${Math.round(table.total_amount)}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button
                        onclick="event.stopPropagation(); markTableAsReady(${table.id})"
                        class="px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition flex items-center justify-center gap-1"
                        ${status === 'ready' ? 'disabled' : ''}
                    >
                        <i class="fas fa-check text-xs"></i>Ready
                    </button>
                    <button
                        onclick="event.stopPropagation(); showTableModal(${table.id})"
                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded transition flex items-center justify-center gap-1"
                    >
                        <i class="fas fa-eye text-xs"></i>View
                    </button>
                </div>
            `;
        } else {
            contentHtml += `
                <div class="text-center py-4">
                    <i class="fas fa-check-circle text-green-400 text-3xl mb-2 opacity-70"></i>
                    <p class="text-gray-300 font-semibold text-sm">Ready for guests</p>
                </div>
            `;
        }

        tableElement.innerHTML = contentHtml;
    });
}

// Refresh tables every 10 seconds
setInterval(refreshTables, 10000);
</script>
