<!-- User Profile Page -->
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-1">Manage your account information and settings</p>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
            <p class="text-green-800"><?php echo $this->session->flashdata('success'); ?></p>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
            <p class="text-red-800"><?php echo $this->session->flashdata('error'); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-white text-4xl font-bold mx-auto mb-4">
                    <?php echo get_user_initials(); ?>
                </div>
                <h2 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h2>
                <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                <div class="mt-4">
                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                        <i class="fas fa-badge mr-1"></i><?php echo ucfirst($user['role']); ?>
                    </span>
                    <span class="ml-2 inline-block px-3 py-1 <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'; ?> rounded-full text-sm font-semibold">
                        <i class="fas fa-circle text-xs mr-1"></i><?php echo ucfirst($user['status']); ?>
                    </span>
                </div>
            </div>

            <hr class="my-6">

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-600 font-semibold">Phone</p>
                    <p class="text-gray-900 mt-1"><?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Not provided'; ?></p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Member Since</p>
                    <p class="text-gray-900 mt-1"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Edit Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-red-600"></i>Edit Profile
                </h3>

                <form method="POST" action="<?php echo base_url('profile/update'); ?>" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo htmlspecialchars($user['name']); ?>"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address (Cannot be changed)</label>
                        <input
                            type="email"
                            id="email"
                            value="<?php echo htmlspecialchars($user['email']); ?>"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                            placeholder="e.g., +92 300 1234567"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-lock text-red-600"></i>Change Password
                </h3>

                <form method="POST" action="<?php echo base_url('profile/change_password'); ?>" class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            required
                            placeholder="Enter your current password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            required
                            placeholder="Enter new password (minimum 6 characters)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required
                            placeholder="Confirm your new password"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Password must be at least 6 characters long
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-red-900 mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>Danger Zone
        </h3>
        <p class="text-red-800 text-sm mb-4">Contact your administrator if you need to delete your account or change your role.</p>
        <a href="<?php echo base_url('auth/logout'); ?>" class="inline-block px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
            <i class="fas fa-sign-out-alt mr-2"></i>Logout
        </a>
    </div>
</div>
