<div class="max-w-full px-4 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Application Settings</h1>
        <p class="text-gray-600 mt-2">Configure your POS system, business details, and preferences</p>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <span class="text-green-800"><?php echo $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <span class="text-red-800"><?php echo $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Settings Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <!-- General Settings -->
        <a href="<?php echo base_url('settings/general'); ?>" class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition group">
            <div class="flex items-center justify-between mb-3">
                <i class="fas fa-sliders-h text-2xl text-blue-600 group-hover:scale-110 transition"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">General Settings</h3>
            <p class="text-sm text-gray-600">App name, currency, language, timezone & formats</p>
        </a>

        <!-- Business Settings -->
        <a href="<?php echo base_url('settings/business'); ?>" class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition group">
            <div class="flex items-center justify-between mb-3">
                <i class="fas fa-store text-2xl text-green-600 group-hover:scale-110 transition"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Business Settings</h3>
            <p class="text-sm text-gray-600">Restaurant details, tax & service charges</p>
        </a>
    </div>

    <!-- System Information -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-info-circle text-blue-600 mr-3"></i> System Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- App Info -->
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Application:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $app_name; ?></span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Version:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $app_version; ?></span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Environment:</span>
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Production</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Currency:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $currency; ?></span>
                </div>
            </div>

            <!-- Current Settings -->
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Timezone:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $timezone; ?></span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Language:</span>
                    <span class="text-gray-900 font-semibold"><?php echo ucfirst($language); ?></span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Date Format:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $date_format; ?></span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                    <span class="text-gray-600 font-medium">Time Format:</span>
                    <span class="text-gray-900 font-semibold"><?php echo $time_format; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
