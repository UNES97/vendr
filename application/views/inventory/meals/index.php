<!-- Meals Menu Management -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Meals Menu</h2>
            <p class="text-gray-600 mt-1">Manage restaurant menu items and dishes</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo base_url('meals/add'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Meal
            </a>
        </div>
    </div>

    <!-- Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('warning')): ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <?php echo $this->session->flashdata('warning'); ?>
        </div>
    <?php endif; ?>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg p-6 mb-6 border border-gray-200">
        <form method="GET" action="<?php echo base_url('meals'); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                        placeholder="Name, SKU..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category</label>
                    <select
                        name="category"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">All</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                    <select
                        name="status"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">All</option>
                        <option value="active" <?php echo ($filters['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($filters['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex-1"
                    >
                        <i class="fas fa-search"></i>
                    </button>
                    <a
                        href="<?php echo base_url('meals'); ?>"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                        title="Reset filters"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- Meals Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Meal Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">SKU</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Cost Price</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Selling Price</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($meals)): ?>
                        <?php foreach ($meals as $meal): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <?php if (!empty($meal['image'])): ?>
                                        <img
                                            src="<?php echo base_url('upload/meals/' . htmlspecialchars($meal['image'])); ?>"
                                            alt="<?php echo htmlspecialchars($meal['name']); ?>"
                                            class="w-12 h-12 rounded-lg object-cover"
                                        >
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($meal['name']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars(substr($meal['description'] ?? '', 0, 50)); ?><?php echo strlen($meal['description'] ?? '') > 50 ? '...' : ''; ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($meal['sku'] ?? '-'); ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($meal['category_name'] ?? '-'); ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    <?php echo format_price($meal['cost_price']); ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <span class="font-semibold text-green-600"><?php echo format_price($meal['selling_price']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                        $status = $meal['status'];
                                        $status_class = ($status === 'active') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $status_class; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="<?php echo base_url('meals/edit/' . $meal['id']); ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('meals/delete/' . $meal['id']); ?>" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete" onclick="return confirm('Are you sure you want to delete this meal?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 inline-block opacity-50"></i>
                                <p class="text-lg mt-2">No meals found</p>
                                <p class="text-sm mt-1">
                                    <a href="<?php echo base_url('meals/add'); ?>" class="text-red-600 hover:underline">Create your first meal</a>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        <?php echo $pagination; ?>
    </div>
</div>
