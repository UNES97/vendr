<!-- Point of Sale Interface -->
<div class="h-screen flex flex-col bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 flex items-center justify-between shadow-lg">
        <div>
            <h1 class="text-3xl font-bold text-white">Point of Sale</h1>
            <p class="text-red-100 text-sm mt-1" id="current-time"></p>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-red-100 text-sm">Order Total</p>
                <p class="text-3xl font-bold text-white" id="order-total"><?php echo get_currency(); ?> 0</p>
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

    <!-- Main Content -->
    <div class="flex flex-1 overflow-hidden gap-4 p-4">
        <!-- Left: Products/Meals -->
        <div class="flex-1 flex flex-col bg-gray-900 rounded-lg border border-gray-700 overflow-hidden shadow-xl">
            <!-- Category Tabs -->
            <div class="border-b border-gray-700 bg-gray-800">
                <div class="flex overflow-x-auto p-2 gap-2">
                    <button
                        onclick="filterMealsByCategory(0)"
                        class="category-tab px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 text-white whitespace-nowrap hover:bg-red-700 transition"
                        data-category="0"
                    >
                        All Items
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button
                            onclick="filterMealsByCategory(<?php echo $category['id']; ?>)"
                            class="category-tab px-4 py-2 rounded-lg text-sm font-semibold bg-gray-700 text-gray-300 hover:bg-gray-600 border border-gray-600 whitespace-nowrap transition"
                            data-category="<?php echo $category['id']; ?>"
                        >
                            <?php echo htmlspecialchars($category['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Meals Grid -->
            <div class="flex-1 overflow-y-auto p-4">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="meals-grid">
                    <?php foreach ($meals as $meal): ?>
                        <div
                            onclick="addToCart(<?php echo htmlspecialchars(json_encode($meal)); ?>)"
                            class="meal-card bg-gradient-to-b from-gray-800 to-gray-700 border border-gray-600 hover:border-red-500 rounded-lg p-4 cursor-pointer hover:shadow-xl transition transform hover:scale-105"
                            data-category="<?php echo $meal['category_id']; ?>"
                            data-meal-id="<?php echo $meal['id']; ?>"
                        >
                            <!-- Meal Image -->
                            <div class="mb-3 relative">
                                <?php if (!empty($meal['image'])): ?>
                                    <img
                                        src="<?php echo base_url('upload/meals/' . htmlspecialchars($meal['image'])); ?>"
                                        alt="<?php echo htmlspecialchars($meal['name']); ?>"
                                        class="w-full h-32 object-cover rounded-lg"
                                    >
                                <?php else: ?>
                                    <div class="w-full h-32 bg-gray-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-utensils text-gray-500 text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold">
                                    <?php echo format_price($meal['selling_price'], false); ?>
                                </span>
                            </div>

                            <!-- Meal Info -->
                            <h3 class="font-semibold text-white text-sm mb-1 truncate">
                                <?php echo htmlspecialchars($meal['name']); ?>
                            </h3>
                            <p class="text-xs text-gray-400 mb-3 line-clamp-2">
                                <?php echo htmlspecialchars(substr($meal['description'] ?? '', 0, 50)); ?>
                            </p>

                            <!-- Add Button -->
                            <button
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-1"
                                onclick="event.stopPropagation(); addToCart(<?php echo htmlspecialchars(json_encode($meal)); ?>)"
                            >
                                <i class="fas fa-plus text-xs"></i> Add
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Right: Cart -->
        <div class="w-96 bg-gray-900 rounded-lg border border-gray-700 overflow-hidden flex flex-col h-full shadow-xl">
            <!-- Cart Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 flex-shrink-0">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i>
                    Order Summary
                </h2>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto">
                <!-- Cart Items -->
                <div class="p-4 space-y-2" id="cart-items">
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-shopping-cart text-3xl opacity-50 mb-2"></i>
                        <p class="text-sm">Cart is empty</p>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="border-t border-gray-700 p-4 space-y-2 bg-gray-800 sticky top-0">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal:</span>
                        <span class="font-semibold text-white"><?php echo get_currency(); ?> <span id="subtotal">0</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Tax (<?php echo get_tax_rate(); ?>%):</span>
                        <span class="font-semibold text-white"><?php echo get_currency(); ?> <span id="tax">0</span></span>
                    </div>
                    <div class="flex justify-between text-lg border-t border-gray-700 pt-2 font-bold">
                        <span class="text-white">Total:</span>
                        <span class="text-red-400"><?php echo get_currency(); ?> <span id="total">0</span></span>
                    </div>
                </div>

                <!-- Order Type Selection -->
                <div class="border-t border-gray-700 p-4 space-y-3">
                    <label class="block text-sm font-semibold text-white">Order Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer hover:bg-gray-800 p-2 rounded transition">
                            <input type="radio" name="order_type" value="dine-in" checked class="w-4 h-4 text-red-600">
                            <span class="ml-2 text-sm text-gray-300">Dine In</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-800 p-2 rounded transition">
                            <input type="radio" name="order_type" value="takeaway" class="w-4 h-4 text-red-600">
                            <span class="ml-2 text-sm text-gray-300">Takeaway</span>
                        </label>
                        <label class="flex items-center cursor-pointer hover:bg-gray-800 p-2 rounded transition">
                            <input type="radio" name="order_type" value="delivery" class="w-4 h-4 text-red-600">
                            <span class="ml-2 text-sm text-gray-300">Delivery</span>
                        </label>
                    </div>
                </div>

                <!-- Table Selection (for Dine-in) -->
                <div id="table-selection" class="border-t border-gray-700 p-4 space-y-3">
                    <label class="block text-sm font-semibold text-white">Select Table</label>
                    <select id="table-select" class="w-full px-3 py-2 border border-gray-600 bg-gray-700 text-white rounded-lg text-sm focus:ring-2 focus:ring-red-600 focus:border-transparent">
                        <option value="">-- Choose a table --</option>
                        <?php foreach ($tables as $table): ?>
                            <option value="<?php echo $table['id']; ?>">
                                Table <?php echo $table['table_number']; ?> (<?php echo ucfirst($table['status']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Customer Info -->
                <div class="border-t border-gray-700 p-4 space-y-3">
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Customer Name</label>
                        <input
                            type="text"
                            id="customer_name"
                            placeholder="Enter name (optional)"
                            class="w-full px-3 py-2 border border-gray-600 bg-gray-700 text-white placeholder-gray-500 rounded-lg text-sm focus:ring-2 focus:ring-red-600 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Phone Number</label>
                        <input
                            type="tel"
                            id="customer_phone"
                            placeholder="Enter phone (optional)"
                            class="w-full px-3 py-2 border border-gray-600 bg-gray-700 text-white placeholder-gray-500 rounded-lg text-sm focus:ring-2 focus:ring-red-600 focus:border-transparent"
                        >
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Fixed at bottom) -->
            <div class="border-t border-gray-700 p-4 space-y-2 flex-shrink-0 bg-gray-800">
                <button
                    id="clear-cart-btn"
                    class="w-full bg-gray-700 hover:bg-gray-600 text-gray-200 py-2 rounded-lg font-semibold transition flex items-center justify-center gap-1"
                    onclick="clearCart()"
                >
                    <i class="fas fa-trash text-sm"></i> Clear Cart
                </button>
                <button
                    id="checkout-btn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-bold text-lg transition flex items-center justify-center gap-2"
                    onclick="checkout()"
                    disabled
                >
                    <i class="fas fa-credit-card"></i> Checkout
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
const TAX_RATE = <?php echo get_tax_rate() / 100; ?>;
const CURRENCY = '<?php echo get_currency(); ?>';

// Update current time
function updateTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}
setInterval(updateTime, 1000);
updateTime();

// Add meal to cart
function addToCart(meal) {
    // Ensure meal object is parsed correctly
    if (typeof meal === 'string') {
        try {
            meal = JSON.parse(meal);
        } catch(e) {
            console.error('Failed to parse meal:', e);
            return;
        }
    }

    const existingItem = cart.find(item => item.id === meal.id);

    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: meal.id,
            name: meal.name,
            price: parseFloat(meal.selling_price),
            quantity: 1
        });
    }

    updateCartDisplay();
    showCartNotification(meal.name);
}

// Show notification when item added
function showCartNotification(itemName) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${itemName} added to cart`;
    document.body.appendChild(toast);

    // Auto remove after 2 seconds
    setTimeout(() => {
        toast.remove();
    }, 2000);

    console.log('Added: ' + itemName);
}

// Remove from cart
function removeFromCart(event) {
    event.stopPropagation();
    const cartItem = event.target.closest('.cart-item');
    const itemId = parseInt(cartItem.dataset.itemId);
    cart = cart.filter(item => item.id !== itemId);
    updateCartDisplay();
}

// Decrease quantity
function decreaseQuantity(event) {
    event.stopPropagation();
    const cartItem = event.target.closest('.cart-item');
    const itemId = parseInt(cartItem.dataset.itemId);
    const item = cart.find(item => item.id === itemId);

    if (item) {
        item.quantity--;
        if (item.quantity <= 0) {
            removeFromCart(event);
        } else {
            updateCartDisplay();
        }
    }
}

// Update cart display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-shopping-cart text-3xl opacity-50 mb-2"></i>
                <p class="text-sm">Cart is empty</p>
            </div>
        `;
        document.getElementById('checkout-btn').disabled = true;
        return;
    }

    // Clear container
    cartItemsContainer.innerHTML = '';
    document.getElementById('checkout-btn').disabled = false;

    // Create and append cart items
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'flex items-center justify-between p-3 bg-gray-800 rounded-lg border border-gray-700 cart-item hover:bg-gray-700 transition';
        cartItem.dataset.itemId = item.id;

        cartItem.innerHTML = `
            <div class="flex-1">
                <p class="font-semibold text-sm text-white">${item.name}</p>
                <p class="text-xs text-gray-400">${item.quantity} × ${CURRENCY} ${Math.round(item.price)}</p>
            </div>
            <div class="text-right mr-2">
                <p class="font-semibold text-sm text-red-400">${CURRENCY} ${Math.round(item.quantity * item.price)}</p>
            </div>
            <div class="flex gap-1">
                <button class="bg-blue-900 hover:bg-blue-800 text-blue-300 px-2 py-1 rounded text-xs transition" onclick="decreaseQuantity(event)">
                    <i class="fas fa-minus"></i>
                </button>
                <button class="bg-red-900 hover:bg-red-800 text-red-300 px-2 py-1 rounded text-xs transition" onclick="removeFromCart(event)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        cartItemsContainer.appendChild(cartItem);
    });

    updateTotals();
}

// Update totals
function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.quantity * item.price), 0);
    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;

    document.getElementById('subtotal').textContent = Math.round(subtotal);
    document.getElementById('tax').textContent = Math.round(tax);
    document.getElementById('total').textContent = Math.round(total);
    document.getElementById('order-total').textContent = `${CURRENCY} ${Math.round(total)}`;
}

// Clear cart
function clearCart() {
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCartDisplay();
    }
}

// Filter meals by category
function filterMealsByCategory(categoryId) {
    const mealCards = document.querySelectorAll('.meal-card');
    const tabs = document.querySelectorAll('.category-tab');

    tabs.forEach(tab => {
        if (parseInt(tab.dataset.category) === categoryId) {
            tab.classList.add('bg-red-600', 'text-white');
            tab.classList.remove('bg-gray-700', 'text-gray-300', 'border', 'border-gray-600', 'hover:bg-gray-600');
        } else {
            tab.classList.remove('bg-red-600', 'text-white');
            tab.classList.add('bg-gray-700', 'text-gray-300', 'border', 'border-gray-600', 'hover:bg-gray-600');
        }
    });

    mealCards.forEach(card => {
        if (categoryId === 0 || parseInt(card.dataset.category) === categoryId) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Handle order type change
document.querySelectorAll('input[name="order_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const tableSelection = document.getElementById('table-selection');
        tableSelection.style.display = this.value === 'dine-in' ? 'block' : 'none';
    });
});

// Checkout
function checkout() {
    if (cart.length === 0) {
        alert('Cart is empty');
        return;
    }

    const orderType = document.querySelector('input[name="order_type"]:checked').value;
    const customerName = document.getElementById('customer_name').value;
    const customerPhone = document.getElementById('customer_phone').value;
    const tableId = orderType === 'dine-in' ? document.getElementById('table-select').value : null;

    if (orderType === 'dine-in' && !tableId) {
        alert('Please select a table for dine-in orders');
        return;
    }

    const subtotal = cart.reduce((sum, item) => sum + (item.quantity * item.price), 0);
    const tax = subtotal * TAX_RATE;
    const total = subtotal + tax;

    const formData = new FormData();
    formData.append('customer_name', customerName);
    formData.append('customer_phone', customerPhone);
    formData.append('table_id', tableId);
    formData.append('order_type', orderType);
    formData.append('payment_method', 'cash');

    cart.forEach((item, index) => {
        formData.append(`items[${index}][meal_id]`, item.id);
        formData.append(`items[${index}][quantity]`, item.quantity);
        formData.append(`items[${index}][price]`, item.price);
    });

    fetch('<?php echo base_url('pos/create_order'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Order #' + data.order_id + ' created successfully!');
            clearCart();
            document.getElementById('customer_name').value = '';
            document.getElementById('customer_phone').value = '';
            document.getElementById('table-select').value = '';
        } else {
            alert('Error creating order');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating order');
    });
}
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
