<div class="max-w-full px-4 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Business Settings</h1>
                <p class="text-gray-600 mt-2">Configure your restaurant details and financial settings</p>
            </div>
            <a href="<?php echo base_url('settings'); ?>" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <span class="text-green-800"><?php echo $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <form method="POST" action="<?php echo base_url('settings/update_business'); ?>" class="space-y-6">
            <!-- Restaurant Information -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-store text-green-600 mr-3"></i> Restaurant Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Restaurant Name -->
                    <div>
                        <label for="restaurant_name" class="block text-sm font-semibold text-gray-700 mb-2">Restaurant Name *</label>
                        <input
                            type="text"
                            id="restaurant_name"
                            name="restaurant_name"
                            required
                            value="<?php echo htmlspecialchars($restaurant_name); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="My Restaurant"
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            required
                            value="<?php echo htmlspecialchars($phone); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="+92-000-0000000"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="<?php echo htmlspecialchars($email); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="info@restaurant.local"
                        >
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address *</label>
                        <input
                            type="text"
                            id="address"
                            name="address"
                            required
                            value="<?php echo htmlspecialchars($address); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="123 Restaurant Street, City"
                        >
                    </div>
                </div>
            </div>

            <!-- Financial Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-money-bill-wave text-green-600 mr-3"></i> Financial Settings
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tax Rate -->
                    <div>
                        <label for="tax_rate" class="block text-sm font-semibold text-gray-700 mb-2">Tax Rate (%) *</label>
                        <input
                            type="number"
                            id="tax_rate"
                            name="tax_rate"
                            required
                            step="0.01"
                            min="0"
                            value="<?php echo htmlspecialchars($tax_rate); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="17"
                        >
                        <p class="text-xs text-gray-500 mt-1">Applied to all sales transactions</p>
                    </div>

                    <!-- Service Charge -->
                    <div>
                        <label for="service_charge" class="block text-sm font-semibold text-gray-700 mb-2">Service Charge (%) *</label>
                        <input
                            type="number"
                            id="service_charge"
                            name="service_charge"
                            required
                            step="0.01"
                            min="0"
                            value="<?php echo htmlspecialchars($service_charge); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent outline-none transition"
                            placeholder="0"
                        >
                        <p class="text-xs text-gray-500 mt-1">Optional service charge on orders</p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        These settings affect invoice calculations and financial reports. Changes apply to new orders immediately.
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Save Business Settings
                </button>
                <a
                    href="<?php echo base_url('settings'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
