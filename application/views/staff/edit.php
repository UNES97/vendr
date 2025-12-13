<!-- Edit User Form -->
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit User</h2>

        <form method="POST" action="<?php echo base_url('staff/update/' . $staff['id']); ?>" class="space-y-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="<?php echo htmlspecialchars($staff['name']); ?>"
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
                    value="<?php echo htmlspecialchars($staff['email']); ?>"
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
                    value="<?php echo htmlspecialchars($staff['phone'] ?? ''); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
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
                    <option value="admin" <?php echo $staff['role'] === 'admin' ? 'selected' : ''; ?>>Admin - Full system access</option>
                    <option value="manager" <?php echo $staff['role'] === 'manager' ? 'selected' : ''; ?>>Manager - Can manage staff and reports</option>
                    <option value="cashier" <?php echo $staff['role'] === 'cashier' ? 'selected' : ''; ?>>Cashier - Can process orders</option>
                    <option value="chef" <?php echo $staff['role'] === 'chef' ? 'selected' : ''; ?>>Chef - Kitchen management and meal prep</option>
                    <option value="waitress" <?php echo $staff['role'] === 'waitress' ? 'selected' : ''; ?>>Waitress - Customer service and order taking</option>
                    <option value="staff" <?php echo $staff['role'] === 'staff' ? 'selected' : ''; ?>>Staff - Basic access</option>
                </select>
            </div>

            <!-- Status Field -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                <select
                    id="status"
                    name="status"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                    <option value="active" <?php echo $staff['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $staff['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="suspended" <?php echo $staff['status'] === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Update User
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

    <!-- Change Password Section -->
    <div class="bg-white rounded-lg shadow-lg p-8 mt-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Change Password</h3>

        <form method="POST" action="<?php echo base_url('staff/change_password/' . $staff['id']); ?>" class="space-y-6">
            <!-- New Password Field -->
            <div>
                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password *</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    required
                    placeholder="Enter new password (minimum 6 characters)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password *</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    required
                    placeholder="Confirm the new password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Password must be at least 6 characters long.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-key mr-2"></i> Change Password
                </button>
            </div>
        </form>
    </div>
</div>
