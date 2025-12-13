<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Raleway', sans-serif !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        ::-webkit-scrollbar-thumb {
            background: #dc2626;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #b91c1c;
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }
        .slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="menuApp()" x-init="init()">

    <!-- Header -->
    <header class="sticky top-0 z-40 bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold"><?php echo htmlspecialchars($restaurant_name); ?></h1>
                    <?php if ($table): ?>
                        <p class="text-sm text-red-100 mt-1">
                            <i class="fas fa-chair"></i> Table <?php echo htmlspecialchars($table['table_number']); ?>
                            <?php if ($table['section']): ?>
                                - <?php echo htmlspecialchars($table['section']); ?>
                            <?php endif; ?>
                        </p>
                    <?php else: ?>
                        <p class="text-sm text-red-100 mt-1">Online Ordering</p>
                    <?php endif; ?>
                </div>

                <!-- Cart Badge -->
                <button
                    @click="showCart = true"
                    class="relative bg-white text-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition flex items-center gap-2"
                >
                    <i class="fas fa-shopping-cart"></i>
                    <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-bold"></span>
                    <span class="hidden md:inline">Cart</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Category Tabs -->
    <nav class="sticky top-16 md:top-20 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex overflow-x-auto py-3 gap-2 scrollbar-hide">
                <button
                    @click="selectedCategory = 0"
                    :class="selectedCategory === 0 ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition"
                >
                    All Items
                </button>
                <?php foreach ($categories as $category): ?>
                <button
                    @click="selectedCategory = <?php echo $category['id']; ?>"
                    :class="selectedCategory === <?php echo $category['id']; ?> ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition"
                >
                    <?php echo htmlspecialchars($category['name']); ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </nav>

    <!-- Meals Grid -->
    <main class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="meals-grid">
            <template x-for="meal in filteredMeals" :key="meal.id">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    <!-- Meal Image -->
                    <div class="relative h-40 bg-gray-200">
                        <template x-if="meal.image">
                            <img
                                :src="'<?php echo base_url('upload/meals/'); ?>' + meal.image"
                                :alt="meal.name"
                                class="w-full h-full object-cover"
                            >
                        </template>
                        <template x-if="!meal.image">
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-utensils text-gray-400 text-3xl"></i>
                            </div>
                        </template>
                        <span class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded text-sm font-bold">
                            <?php echo $currency; ?> <span x-text="meal.selling_price"></span>
                        </span>
                    </div>

                    <!-- Meal Info -->
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1" x-text="meal.name"></h3>
                        <p class="text-xs text-gray-500 mb-3 line-clamp-2" x-text="meal.description || 'Delicious meal'"></p>

                        <!-- Add to Cart Button -->
                        <button
                            @click="addToCart(meal)"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-semibold transition flex items-center justify-center gap-1"
                        >
                            <i class="fas fa-plus text-xs"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="filteredMeals.length === 0" class="text-center py-16">
            <i class="fas fa-utensils text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No items found in this category</p>
        </div>
    </main>

    <!-- Cart Sidebar/Sheet -->
    <div
        x-show="showCart"
        x-cloak
        @click.self="showCart = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end md:items-center md:justify-end"
    >
        <div
            @click.stop
            class="bg-white w-full md:w-96 h-full md:h-auto md:max-h-screen md:rounded-l-lg flex flex-col slide-in-right"
        >
            <!-- Cart Header -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i>
                    Your Cart (<span x-text="cartCount"></span>)
                </h2>
                <button @click="showCart = false" class="text-white hover:text-red-100">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Cart Content -->
            <div class="flex-1 overflow-y-auto">
                <!-- Cart Items -->
                <div class="p-4 space-y-3">
                    <template x-if="cart.length === 0">
                        <div class="text-center py-12">
                            <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500">Your cart is empty</p>
                        </div>
                    </template>

                    <template x-for="item in cart" :key="item.id">
                        <div class="bg-gray-50 rounded-lg p-3 flex gap-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm" x-text="item.name"></h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo $currency; ?> <span x-text="item.price"></span> each
                                </p>
                            </div>

                            <!-- Quantity Controls -->
                            <div class="flex items-center gap-2">
                                <button
                                    @click="updateQuantity(item.id, item.quantity - 1)"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-7 h-7 rounded flex items-center justify-center transition"
                                >
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="w-8 text-center font-semibold" x-text="item.quantity"></span>
                                <button
                                    @click="updateQuantity(item.id, item.quantity + 1)"
                                    class="bg-red-600 hover:bg-red-700 text-white w-7 h-7 rounded flex items-center justify-center transition"
                                >
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-gray-900">
                                    <?php echo $currency; ?> <span x-text="item.price * item.quantity"></span>
                                </p>
                                <button
                                    @click="removeFromCart(item.id)"
                                    class="text-red-600 hover:text-red-700 text-xs mt-1"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Order Summary -->
                <div x-show="cart.length > 0" class="border-t border-gray-200 p-4 space-y-2 bg-gray-50">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold"><?php echo $currency; ?> <span x-text="subtotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax (<?php echo $tax_rate; ?>%):</span>
                        <span class="font-semibold"><?php echo $currency; ?> <span x-text="tax.toFixed(2)"></span></span>
                    </div>
                    <div x-show="orderType === 'delivery'" class="flex justify-between text-sm">
                        <span class="text-gray-600">Delivery Fee:</span>
                        <span class="font-semibold"><?php echo $currency; ?> <span x-text="deliveryFee"></span></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                        <span class="text-gray-900">Total:</span>
                        <span class="text-red-600"><?php echo $currency; ?> <span x-text="total.toFixed(2)"></span></span>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div x-show="cart.length > 0" class="border-t border-gray-200 p-4 space-y-4">
                    <h3 class="font-bold text-gray-900">Order Details</h3>

                    <!-- Order Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Order Type</label>
                        <div class="space-y-2">
                            <?php if ($table): ?>
                                <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                    <input type="radio" x-model="orderType" value="dine-in" class="w-4 h-4 text-red-600">
                                    <span class="ml-2 text-sm text-gray-700">Dine In (Table <?php echo $table['table_number']; ?>)</span>
                                </label>
                            <?php endif; ?>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                <input type="radio" x-model="orderType" value="takeaway" class="w-4 h-4 text-red-600" <?php echo !$table ? 'checked' : ''; ?>>
                                <span class="ml-2 text-sm text-gray-700">Takeaway</span>
                            </label>
                            <label class="flex items-center cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                                <input type="radio" x-model="orderType" value="delivery" class="w-4 h-4 text-red-600">
                                <span class="ml-2 text-sm text-gray-700">Delivery</span>
                            </label>
                        </div>
                    </div>

                    <!-- Customer Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                        <input
                            type="text"
                            x-model="customerName"
                            placeholder="Your name"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Customer Phone -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Phone *</label>
                        <input
                            type="tel"
                            x-model="customerPhone"
                            placeholder="03XX-XXXXXXX"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Customer Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email (optional)</label>
                        <input
                            type="email"
                            x-model="customerEmail"
                            placeholder="your@email.com"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        >
                    </div>

                    <!-- Delivery Address -->
                    <div x-show="orderType === 'delivery'">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Delivery Address *</label>
                        <textarea
                            x-model="deliveryAddress"
                            placeholder="Full delivery address"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        ></textarea>
                        <p x-show="orderType === 'delivery' && subtotal < <?php echo $minimum_delivery_order; ?>" class="text-xs text-red-600 mt-1">
                            Minimum order for delivery: <?php echo $currency; ?> <?php echo $minimum_delivery_order; ?>
                        </p>
                    </div>

                    <!-- Special Instructions -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Special Instructions (optional)</label>
                        <textarea
                            x-model="specialInstructions"
                            placeholder="Any special requests?"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        @click="submitOrder()"
                        :disabled="submitting"
                        class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white py-3 rounded-lg font-bold transition"
                    >
                        <span x-show="!submitting">Place Order</span>
                        <span x-show="submitting">
                            <i class="fas fa-spinner fa-spin"></i> Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function menuApp() {
            return {
                // Data
                meals: <?php echo json_encode($meals); ?>,
                selectedCategory: 0,
                cart: [],
                showCart: false,

                // Order details
                orderType: '<?php echo $order_type; ?>',
                tableId: <?php echo $table_id ? $table_id : 'null'; ?>,
                customerName: '',
                customerPhone: '',
                customerEmail: '',
                deliveryAddress: '',
                specialInstructions: '',

                // Settings
                taxRate: <?php echo $tax_rate; ?>,
                deliveryFee: <?php echo $delivery_fee; ?>,
                minimumDeliveryOrder: <?php echo $minimum_delivery_order; ?>,
                currency: '<?php echo $currency; ?>',

                // State
                submitting: false,

                // Computed
                get filteredMeals() {
                    if (this.selectedCategory === 0) {
                        return this.meals;
                    }
                    return this.meals.filter(meal => meal.category_id === this.selectedCategory);
                },

                get cartCount() {
                    return this.cart.reduce((sum, item) => sum + item.quantity, 0);
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                get tax() {
                    return this.subtotal * (this.taxRate / 100);
                },

                get total() {
                    let total = this.subtotal + this.tax;
                    if (this.orderType === 'delivery') {
                        total += parseFloat(this.deliveryFee);
                    }
                    return total;
                },

                // Methods
                init() {
                    // Load cart from localStorage
                    const savedCart = localStorage.getItem('cart');
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                    }
                },

                addToCart(meal) {
                    const existingItem = this.cart.find(item => item.id === meal.id);

                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            id: meal.id,
                            name: meal.name,
                            price: parseFloat(meal.selling_price),
                            quantity: 1
                        });
                    }

                    this.saveCart();
                    this.showCart = true;
                },

                updateQuantity(mealId, newQuantity) {
                    if (newQuantity < 1) {
                        this.removeFromCart(mealId);
                        return;
                    }

                    const item = this.cart.find(item => item.id === mealId);
                    if (item) {
                        item.quantity = newQuantity;
                        this.saveCart();
                    }
                },

                removeFromCart(mealId) {
                    this.cart = this.cart.filter(item => item.id !== mealId);
                    this.saveCart();
                },

                saveCart() {
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },

                async submitOrder() {
                    // Validation
                    if (!this.customerName.trim()) {
                        alert('Please enter your name');
                        return;
                    }

                    if (!this.customerPhone.trim()) {
                        alert('Please enter your phone number');
                        return;
                    }

                    if (this.orderType === 'delivery') {
                        if (!this.deliveryAddress.trim()) {
                            alert('Please enter delivery address');
                            return;
                        }

                        if (this.subtotal < this.minimumDeliveryOrder) {
                            alert(`Minimum order for delivery is ${this.currency} ${this.minimumDeliveryOrder}`);
                            return;
                        }
                    }

                    this.submitting = true;

                    // Prepare order data
                    const orderData = {
                        customer_name: this.customerName,
                        customer_phone: this.customerPhone,
                        customer_email: this.customerEmail,
                        order_type: this.orderType,
                        table_id: this.tableId,
                        delivery_address: this.deliveryAddress,
                        special_instructions: this.specialInstructions,
                        items: this.cart.map(item => ({
                            meal_id: item.id,
                            quantity: item.quantity,
                            price: item.price
                        }))
                    };

                    try {
                        const response = await fetch('<?php echo base_url('order/create'); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(orderData)
                        });

                        const result = await response.json();

                        if (result.success) {
                            // Clear cart
                            this.cart = [];
                            this.saveCart();

                            // Redirect to tracking page
                            window.location.href = '<?php echo base_url('order/track/'); ?>' + result.order_number;
                        } else {
                            alert(result.message || 'Failed to place order. Please try again.');
                            this.submitting = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                        this.submitting = false;
                    }
                }
            }
        }
    </script>

</body>
</html>
