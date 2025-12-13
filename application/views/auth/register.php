<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Restaurant POS & Inventory</title>
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
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-utensils text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">POS Pro</h1>
                <p class="text-gray-400">Restaurant Management System</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-lg shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Account</h2>

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

                <!-- Register Form -->
                <form method="POST" action="<?php echo base_url('auth/do_register'); ?>" class="space-y-4">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            placeholder="John Admin"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="admin@restaurant.local"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
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

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <!-- Terms -->
                    <label class="flex items-center space-x-2 cursor-pointer text-sm">
                        <input type="checkbox" name="terms" required class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-2 focus:ring-red-600">
                        <span class="text-gray-700">I agree to the <a href="#" class="text-red-600 hover:text-red-700">Terms of Service</a> and <a href="#" class="text-red-600 hover:text-red-700">Privacy Policy</a></span>
                    </label>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold py-2.5 rounded-lg hover:from-red-700 hover:to-red-800 transition duration-200 flex items-center justify-center space-x-2 mt-6"
                    >
                        <i class="fas fa-user-plus"></i>
                        <span>Create Account</span>
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-center text-sm text-gray-600 mt-6">
                    Already have an account?
                    <a href="<?php echo base_url('auth/login'); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in</a>
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
