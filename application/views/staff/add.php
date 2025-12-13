<!-- Add New User Form -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New User</h2>

        <form method="POST" action="<?php echo base_url('staff/create'); ?>" class="space-y-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    placeholder="John Doe"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    placeholder="john@restaurant.local"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Phone Field -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="+1 (555) 000-0000"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="••••••••"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                <p class="text-xs text-gray-500 mt-2">Minimum 8 characters recommended</p>
            </div>

            <!-- Role Field -->
            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role *</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                    <option value="">Select a role</option>
                    <option value="admin">Admin - Full system access</option>
                    <option value="manager">Manager - Can manage staff and reports</option>
                    <option value="cashier">Cashier - Can process orders</option>
                    <option value="chef">Chef - Kitchen management and meal prep</option>
                    <option value="waitress">Waitress - Customer service and order taking</option>
                    <option value="staff">Staff - Basic access</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Create User
                </button>
                <a
                    href="<?php echo base_url('staff'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
