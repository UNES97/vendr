<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php
        $this->load->helper('settings');
        $restaurant_name = get_restaurant_name() ?: 'RestroFlow';
        echo $page_title . ' - ' . $restaurant_name;
    ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Raleway', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="text-center">
                        <?php
                            $this->load->helper('settings');
                            $restaurant_name = get_restaurant_name() ?: 'RestroFlow';
                        ?>
                        <h1 class="text-4xl font-bold text-white"><?php echo $restaurant_name; ?></h1>
                        <p class="text-sm text-red-500 font-semibold mt-1">Open Source Restaurant POS</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm mt-2">Inventory & Order Management</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Welcome Back</h2>

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-3">
                        <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                        <p class="text-sm text-red-800"><?php echo $this->session->flashdata('error'); ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start space-x-3">
                        <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                        <p class="text-sm text-green-800"><?php echo $this->session->flashdata('success'); ?></p>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="<?php echo base_url('auth/do_login'); ?>" class="space-y-6">
                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="admin@restaurant.local"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-2 focus:ring-red-600">
                            <span class="text-gray-700">Remember me</span>
                        </label>
                        <a href="<?php echo base_url('auth/forgot'); ?>" class="text-red-600 hover:text-red-700 font-medium">Forgot password?</a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold py-2.5 rounded-lg hover:from-red-700 hover:to-red-800 transition duration-200 flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                <!-- Demo Login -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600 mb-4">Demo Credentials</p>
                    <button
                        type="button"
                        onclick="document.getElementById('email').value='demo@restaurant.local'; document.getElementById('password').value='demo123';"
                        class="w-full px-4 py-2 border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:border-gray-400 hover:bg-gray-50 transition"
                    >
                        <i class="fas fa-user-circle"></i> Use Demo Account
                    </button>
                </div>

                <!-- Admin Note -->
                <p class="text-center text-sm text-gray-500 mt-6">
                    Contact your administrator for account creation
                </p>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-400 text-xs mt-8">
                © 2024 POS Pro. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
