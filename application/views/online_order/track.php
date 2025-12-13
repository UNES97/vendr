<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif !important;
        }
        /* Status timeline styles */
        .status-step {
            position: relative;
        }
        .status-step::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 40px;
            width: 2px;
            height: calc(100% - 40px);
            background: #e5e7eb;
        }
        .status-step:last-child::before {
            display: none;
        }
        .status-step.active::before {
            background: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-gradient-to-r from-red-600 to-red-700 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Order Tracking</h1>
                    <p class="text-sm text-red-100 mt-1">Order #<?php echo htmlspecialchars($order['order_number']); ?></p>
                </div>
                <a href="<?php echo base_url('menu'); ?>" class="bg-white text-red-600 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition">
                    <i class="fas fa-home"></i> <span class="hidden md:inline">Back to Menu</span>
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6 max-w-3xl">
        <!-- Order Status Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Status</h2>

            <div class="space-y-6" id="status-timeline">
                <!-- Pending -->
                <div class="status-step <?php echo in_array($order['order_status'], ['pending', 'preparing', 'ready', 'served', 'completed']) ? 'active' : ''; ?>">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?php echo in_array($order['order_status'], ['pending', 'preparing', 'ready', 'served', 'completed']) ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400'; ?>">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Order Received</h3>
                            <p class="text-sm text-gray-500">We've received your order</p>
                            <?php if ($order['order_status'] === 'pending' || in_array($order['order_status'], ['preparing', 'ready', 'served', 'completed'])): ?>
                                <p class="text-xs text-gray-400 mt-1"><?php echo date('g:i A', strtotime($order['created_at'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Preparing -->
                <div class="status-step <?php echo in_array($order['order_status'], ['preparing', 'ready', 'served', 'completed']) ? 'active' : ''; ?>">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?php echo in_array($order['order_status'], ['preparing', 'ready', 'served', 'completed']) ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400'; ?>">
                            <i class="fas fa-fire-burner"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Preparing</h3>
                            <p class="text-sm text-gray-500">Your food is being prepared</p>
                            <?php if ($order['order_status'] === 'preparing'): ?>
                                <p class="text-xs text-red-600 mt-1 font-semibold">
                                    <i class="fas fa-spinner fa-spin"></i> In Progress...
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Ready -->
                <div class="status-step <?php echo in_array($order['order_status'], ['ready', 'served', 'completed']) ? 'active' : ''; ?>">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?php echo in_array($order['order_status'], ['ready', 'served', 'completed']) ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400'; ?>">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Ready</h3>
                            <p class="text-sm text-gray-500">
                                <?php if ($order['order_type'] === 'dine-in'): ?>
                                    Ready to serve
                                <?php elseif ($order['order_type'] === 'takeaway'): ?>
                                    Ready for pickup
                                <?php else: ?>
                                    Out for delivery
                                <?php endif; ?>
                            </p>
                            <?php if ($order['order_status'] === 'ready'): ?>
                                <p class="text-xs text-green-600 mt-1 font-semibold">
                                    <i class="fas fa-bell"></i> Your order is ready!
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="status-step <?php echo $order['order_status'] === 'completed' ? 'active' : ''; ?>">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center <?php echo $order['order_status'] === 'completed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400'; ?>">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Completed</h3>
                            <p class="text-sm text-gray-500">Order completed. Thank you!</p>
                            <?php if ($order['order_status'] === 'completed'): ?>
                                <p class="text-xs text-green-600 mt-1 font-semibold">
                                    <i class="fas fa-smile"></i> Enjoy your meal!
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Cancelled (if applicable) -->
                <?php if ($order['order_status'] === 'cancelled'): ?>
                <div class="status-step">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center bg-gray-600 text-white">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Cancelled</h3>
                            <p class="text-sm text-gray-500">This order has been cancelled</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Auto-refresh notice -->
            <?php if (!in_array($order['order_status'], ['completed', 'cancelled'])): ?>
            <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-sync-alt"></i> This page refreshes automatically every 10 seconds
                </p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Order Details</h2>

            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Order Type:</span>
                    <span class="font-semibold capitalize"><?php echo htmlspecialchars($order['order_type']); ?></span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Customer:</span>
                    <span class="font-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-semibold"><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                </div>

                <?php if (!empty($order['delivery_address'])): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Delivery Address:</span>
                    <span class="font-semibold text-right"><?php echo nl2br(htmlspecialchars($order['delivery_address'])); ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($order['special_instructions'])): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Special Instructions:</span>
                    <span class="font-semibold text-right"><?php echo nl2br(htmlspecialchars($order['special_instructions'])); ?></span>
                </div>
                <?php endif; ?>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Order Time:</span>
                    <span class="font-semibold"><?php echo date('g:i A, M j, Y', strtotime($order['created_at'])); ?></span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Items</h2>

            <div class="space-y-3">
                <?php foreach ($items as $item): ?>
                <div class="flex justify-between items-start py-2 border-b border-gray-100 last:border-0">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['name'] ?? 'Item'); ?></h4>
                        <p class="text-sm text-gray-500">
                            <?php echo $currency; ?> <?php echo number_format($item['unit_price'], 2); ?> × <?php echo $item['quantity']; ?>
                        </p>
                    </div>
                    <div class="font-bold text-gray-900">
                        <?php echo $currency; ?> <?php echo number_format($item['total_price'], 2); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Totals -->
            <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal:</span>
                    <span class="font-semibold"><?php echo $currency; ?> <?php echo number_format($order['subtotal'], 2); ?></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tax:</span>
                    <span class="font-semibold"><?php echo $currency; ?> <?php echo number_format($order['tax_amount'], 2); ?></span>
                </div>
                <?php if ($order['delivery_fee'] > 0): ?>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Delivery Fee:</span>
                    <span class="font-semibold"><?php echo $currency; ?> <?php echo number_format($order['delivery_fee'], 2); ?></span>
                </div>
                <?php endif; ?>
                <div class="flex justify-between text-lg font-bold border-t border-gray-300 pt-2">
                    <span class="text-gray-900">Total:</span>
                    <span class="text-red-600"><?php echo $currency; ?> <?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Payment</h2>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Payment Method</p>
                    <p class="font-semibold text-gray-900 capitalize"><?php echo htmlspecialchars($order['payment_method']); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-semibold capitalize <?php echo $order['payment_status'] === 'completed' ? 'text-green-600' : 'text-orange-600'; ?>">
                        <?php echo htmlspecialchars($order['payment_status']); ?>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Auto-refresh script -->
    <?php if (!in_array($order['order_status'], ['completed', 'cancelled'])): ?>
    <script>
        // Auto-refresh every 10 seconds
        setTimeout(function() {
            location.reload();
        }, 10000);
    </script>
    <?php endif; ?>

</body>
</html>
