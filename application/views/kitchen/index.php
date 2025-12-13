<!-- Kitchen Display System -->
<div class="h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between shadow-lg">
        <div>
            <h1 class="text-3xl font-bold text-white">Kitchen Display</h1>
            <p class="text-red-100 text-sm">Real-time order management</p>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-red-100 text-sm">Current Time</p>
                <p class="text-2xl font-bold text-white" id="current-time">00:00:00</p>
            </div>
            <!-- User Menu -->
            <div class="flex items-center gap-3 border-l border-red-500 pl-6">
                <div class="text-right">
                    <p class="text-white text-sm font-semibold"><?php echo user_name(); ?></p>
                    <p class="text-red-100 text-xs"><?php echo ucfirst(user_role()); ?></p>
                </div>
                <div class="w-10 h-10 bg-red-800 rounded-full flex items-center justify-center text-white font-bold border border-red-500">
                    <?php echo get_user_initials(); ?>
                </div>
                <a href="<?php echo base_url('auth/logout'); ?>" class="ml-2 px-3 py-1 bg-red-800 hover:bg-red-900 text-white rounded text-sm font-semibold transition">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Kanban Board -->
    <div class="flex flex-1 gap-6 p-6 overflow-hidden">
        <!-- Pending Orders Column -->
        <div class="flex-1 flex flex-col bg-gradient-to-b from-gray-700 to-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <!-- Column Header -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 flex items-center gap-3 border-b-2 border-orange-700">
                <i class="fas fa-inbox text-white text-2xl"></i>
                <div>
                    <h2 class="text-xl font-bold text-white">New Orders</h2>
                    <p class="text-orange-100 text-xs">Orders awaiting preparation</p>
                </div>
                <div class="ml-auto bg-orange-700 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg" id="pending-count">
                    <?php echo count($pending_orders); ?>
                </div>
            </div>

            <!-- Orders Container -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="pending-orders-container">
                <?php foreach ($pending_orders as $order): ?>
                    <div
                        class="order-card bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg p-4 cursor-pointer border-2 border-orange-400 hover:border-orange-300 transition transform hover:scale-105 shadow-lg"
                        onclick="showOrderDetails(<?php echo $order['id']; ?>)"
                        data-order-id="<?php echo $order['id']; ?>"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-orange-300 font-bold text-lg">#<?php echo htmlspecialchars($order['order_number']); ?></p>
                                <p class="text-gray-300 text-xs">Table <?php echo htmlspecialchars($order['table_id'] ?? 'Takeout'); ?></p>
                            </div>
                            <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                <?php echo $order['item_count']; ?> items
                            </span>
                        </div>
                        <div class="space-y-1 text-sm text-gray-200">
                            <?php
                            $items = $this->db->where('order_id', $order['id'])->get('order_items')->result_array();
                            foreach (array_slice($items, 0, 2) as $item):
                            ?>
                                <p class="flex items-center gap-2">
                                    <span class="text-orange-400">•</span>
                                    <span><?php echo $item['quantity']; ?>x</span>
                                    <span class="text-gray-100">Item #<?php echo $item['meal_id']; ?></span>
                                </p>
                            <?php endforeach; ?>
                            <?php if ($order['item_count'] > 2): ?>
                                <p class="text-gray-400 text-xs italic">+<?php echo $order['item_count'] - 2; ?> more...</p>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3 text-xs text-gray-400 flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            <span id="time-<?php echo $order['id']; ?>">Just now</span>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($pending_orders)): ?>
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                        <i class="fas fa-inbox text-6xl mb-3 opacity-30"></i>
                        <p class="font-semibold">No pending orders</p>
                        <p class="text-xs text-gray-600">All caught up!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Preparing Orders Column -->
        <div class="flex-1 flex flex-col bg-gradient-to-b from-gray-700 to-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <!-- Column Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex items-center gap-3 border-b-2 border-blue-700">
                <i class="fas fa-fire-alt text-white text-2xl"></i>
                <div>
                    <h2 class="text-xl font-bold text-white">Preparing</h2>
                    <p class="text-blue-100 text-xs">Orders in progress</p>
                </div>
                <div class="ml-auto bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg" id="preparing-count">
                    <?php echo count($preparing_orders); ?>
                </div>
            </div>

            <!-- Orders Container -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="preparing-orders-container">
                <?php foreach ($preparing_orders as $order): ?>
                    <div
                        class="order-card bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg p-4 cursor-pointer border-2 border-blue-400 hover:border-blue-300 transition transform hover:scale-105 shadow-lg"
                        onclick="showOrderDetails(<?php echo $order['id']; ?>)"
                        data-order-id="<?php echo $order['id']; ?>"
                    >
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-blue-300 font-bold text-lg">#<?php echo htmlspecialchars($order['order_number']); ?></p>
                                <p class="text-gray-300 text-xs">Table <?php echo htmlspecialchars($order['table_id'] ?? 'Takeout'); ?></p>
                            </div>
                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                <?php echo $order['item_count']; ?> items
                            </span>
                        </div>
                        <div class="space-y-1 text-sm text-gray-200">
                            <?php
                            $items = $this->db->where('order_id', $order['id'])->get('order_items')->result_array();
                            foreach (array_slice($items, 0, 2) as $item):
                            ?>
                                <p class="flex items-center gap-2">
                                    <span class="text-blue-400">•</span>
                                    <span><?php echo $item['quantity']; ?>x</span>
                                    <span class="text-gray-100">Item #<?php echo $item['meal_id']; ?></span>
                                </p>
                            <?php endforeach; ?>
                            <?php if ($order['item_count'] > 2): ?>
                                <p class="text-gray-400 text-xs italic">+<?php echo $order['item_count'] - 2; ?> more...</p>
                            <?php endif; ?>
                        </div>
                        <div class="mt-3 text-xs text-gray-400 flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            <span id="time-<?php echo $order['id']; ?>">In progress</span>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($preparing_orders)): ?>
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                        <i class="fas fa-check-circle text-6xl mb-3 opacity-30"></i>
                        <p class="font-semibold">All orders ready</p>
                        <p class="text-xs text-gray-600">Great job!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ready Orders Column -->
        <div class="flex-1 flex flex-col bg-gradient-to-b from-gray-700 to-gray-800 rounded-lg shadow-2xl border border-gray-700 overflow-hidden">
            <!-- Column Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 flex items-center gap-3 border-b-2 border-green-700">
                <i class="fas fa-check-circle text-white text-2xl"></i>
                <div>
                    <h2 class="text-xl font-bold text-white">Ready to Serve</h2>
                    <p class="text-green-100 text-xs">Waiting for pickup</p>
                </div>
                <div class="ml-auto bg-green-700 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg" id="ready-count">
                    0
                </div>
            </div>

            <!-- Orders Container -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3" id="ready-orders-container">
                <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                    <i class="fas fa-bell text-6xl mb-3 opacity-30"></i>
                    <p class="font-semibold">No orders ready</p>
                    <p class="text-xs text-gray-600">Orders will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="order-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-lg shadow-2xl max-w-2xl w-full mx-4 border border-gray-700">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-receipt"></i>
                <span id="modal-order-number">Order #1</span>
            </h2>
            <button onclick="closeOrderModal()" class="text-white hover:text-red-200 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <div id="modal-content" class="space-y-4">
                <!-- Loading state -->
                <div class="text-center text-gray-400">
                    <i class="fas fa-spinner fa-spin text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-800 px-6 py-4 flex gap-3 justify-end border-t border-gray-700">
            <button
                onclick="markOrderAsReady()"
                class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition flex items-center gap-2"
                id="ready-btn"
            >
                <i class="fas fa-check-circle"></i>
                Mark as Ready
            </button>
            <button
                onclick="markOrderAsPrep()"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition flex items-center gap-2"
                id="prep-btn"
            >
                <i class="fas fa-fire-alt"></i>
                Start Preparing
            </button>
            <button
                onclick="closeOrderModal()"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
const CURRENCY = '<?php echo get_currency(); ?>';
let currentOrderId = null;

// Update time
setInterval(updateTime, 1000);

function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString();
}

// Show order details in modal
function showOrderDetails(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('order-modal');
    modal.classList.remove('hidden');

    // Fetch order details
    const url = '<?php echo base_url('kitchen/get_order_details/'); ?>' + orderId;
    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load order');
            return response.json();
        })
        .then(order => {
            document.getElementById('modal-order-number').textContent = '#' + order.order_number;
            renderOrderDetails(order);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modal-content').innerHTML = '<p class="text-red-500">Failed to load order details: ' + error.message + '</p>';
        });
}

function renderOrderDetails(order) {
    let itemsHtml = '<div class="space-y-3">';

    order.items.forEach(item => {
        const unitPrice = parseFloat(item.unit_price);
        const quantity = parseInt(item.quantity);
        const itemTotal = (unitPrice * quantity).toFixed(0);

        itemsHtml += `
            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-white font-bold text-lg">${quantity}x ${item.meal_name || 'Item #' + item.meal_id}</h3>
                    <span class="text-gray-400 text-sm">${CURRENCY} ${itemTotal}</span>
                </div>
                ${item.meal_description ? `<p class="text-gray-400 text-sm">${item.meal_description}</p>` : ''}
            </div>
        `;
    });

    itemsHtml += '</div>';

    const statusColors = {
        'pending': { bg: 'bg-orange-500', text: 'New Order' },
        'preparing': { bg: 'bg-blue-500', text: 'Preparing' },
        'ready': { bg: 'bg-green-500', text: 'Ready to Serve' },
        'completed': { bg: 'bg-gray-500', text: 'Completed' }
    };

    const status = statusColors[order.order_status] || statusColors['pending'];

    const tableInfo = order.table_id ? `Table ${order.table_id}` : 'Takeout Order';
    const totalAmount = parseFloat(order.total_amount).toFixed(0);

    const detailsHtml = `
        <div class="bg-gray-700 rounded-lg p-4 mb-4 border-l-4 border-red-500">
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <p class="text-gray-400 text-xs uppercase">Order Type</p>
                    <p class="text-white font-bold">${tableInfo}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase">Status</p>
                    <p class="text-white font-bold">
                        <span class="px-3 py-1 rounded-full text-white text-sm font-bold ${status.bg}">${status.text}</span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase">Customer</p>
                    <p class="text-white font-bold">${order.customer_name || 'Walk-in'}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase">Total Amount</p>
                    <p class="text-white font-bold">${CURRENCY} ${totalAmount}</p>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-gray-400 text-xs uppercase mb-2">Items</p>
            ${itemsHtml}
        </div>

        ${order.order_type === 'dine-in' ? `
            <div class="bg-blue-900 bg-opacity-50 border border-blue-500 rounded-lg p-4">
                <p class="text-blue-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    This is a dine-in order. Prepare and serve at table ${order.table_id}
                </p>
            </div>
        ` : `
            <div class="bg-orange-900 bg-opacity-50 border border-orange-500 rounded-lg p-4">
                <p class="text-orange-200 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    This is a takeout order. Prepare for pickup.
                </p>
            </div>
        `}
    `;

    document.getElementById('modal-content').innerHTML = detailsHtml;

    // Update button visibility based on status
    const prepBtn = document.getElementById('prep-btn');
    const readyBtn = document.getElementById('ready-btn');

    if (order.order_status === 'pending') {
        prepBtn.style.display = 'flex';
        readyBtn.style.display = 'none';
    } else if (order.order_status === 'preparing') {
        prepBtn.style.display = 'none';
        readyBtn.style.display = 'flex';
    } else {
        prepBtn.style.display = 'none';
        readyBtn.style.display = 'none';
    }
}

function closeOrderModal() {
    document.getElementById('order-modal').classList.add('hidden');
    currentOrderId = null;
}

function markOrderAsPrep() {
    if (!currentOrderId) return;

    const url = '<?php echo base_url('kitchen/update_order_status/'); ?>' + currentOrderId + '/preparing';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            closeOrderModal();
            refreshOrders();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update order status');
        });
}

function markOrderAsReady() {
    if (!currentOrderId) return;

    const url = '<?php echo base_url('kitchen/update_order_status/'); ?>' + currentOrderId + '/ready';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            closeOrderModal();
            refreshOrders();
            // Play notification sound
            playNotificationSound();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to update order status');
        });
}

function refreshOrders() {
    const url = '<?php echo base_url('kitchen/get_orders_list'); ?>';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            updateOrdersDisplay(data.pending, data.preparing);
        })
        .catch(error => console.error('Failed to refresh orders:', error));
}

function updateOrdersDisplay(pending, preparing) {
    // Update pending orders
    let pendingHtml = '';
    if (pending.length === 0) {
        pendingHtml = `
            <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                <i class="fas fa-inbox text-6xl mb-3 opacity-30"></i>
                <p class="font-semibold">No pending orders</p>
                <p class="text-xs text-gray-600">All caught up!</p>
            </div>
        `;
    } else {
        pending.forEach(order => {
            pendingHtml += `
                <div
                    class="order-card bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg p-4 cursor-pointer border-2 border-orange-400 hover:border-orange-300 transition transform hover:scale-105 shadow-lg"
                    onclick="showOrderDetails(${order.id})"
                    data-order-id="${order.id}"
                >
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-orange-300 font-bold text-lg">#${order.order_number}</p>
                            <p class="text-gray-300 text-xs">Table ${order.table_id || 'Takeout'}</p>
                        </div>
                        <span class="bg-orange-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                            ${order.item_count} items
                        </span>
                    </div>
                </div>
            `;
        });
    }

    // Update preparing orders
    let preparingHtml = '';
    if (preparing.length === 0) {
        preparingHtml = `
            <div class="flex flex-col items-center justify-center h-full text-center text-gray-500">
                <i class="fas fa-check-circle text-6xl mb-3 opacity-30"></i>
                <p class="font-semibold">All orders ready</p>
                <p class="text-xs text-gray-600">Great job!</p>
            </div>
        `;
    } else {
        preparing.forEach(order => {
            preparingHtml += `
                <div
                    class="order-card bg-gradient-to-br from-gray-600 to-gray-700 rounded-lg p-4 cursor-pointer border-2 border-blue-400 hover:border-blue-300 transition transform hover:scale-105 shadow-lg"
                    onclick="showOrderDetails(${order.id})"
                    data-order-id="${order.id}"
                >
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-blue-300 font-bold text-lg">#${order.order_number}</p>
                            <p class="text-gray-300 text-xs">Table ${order.table_id || 'Takeout'}</p>
                        </div>
                        <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                            ${order.item_count} items
                        </span>
                    </div>
                </div>
            `;
        });
    }

    document.getElementById('pending-orders-container').innerHTML = pendingHtml;
    document.getElementById('preparing-orders-container').innerHTML = preparingHtml;
    document.getElementById('pending-count').textContent = pending.length;
    document.getElementById('preparing-count').textContent = preparing.length;
}

function playNotificationSound() {
    // Simple beep sound using Web Audio API
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gain = audioContext.createGain();

    oscillator.connect(gain);
    gain.connect(audioContext.destination);

    oscillator.frequency.value = 800;
    oscillator.type = 'sine';

    gain.gain.setValueAtTime(0.3, audioContext.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
}

// Refresh orders every 5 seconds
setInterval(refreshOrders, 5000);

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeOrderModal();
    }
});
</script>
