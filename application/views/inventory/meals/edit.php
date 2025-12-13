<!-- Edit Meal Form -->
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg p-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Edit Meal</h2>

        <form method="POST" action="<?php echo base_url('meals/update/' . $meal['id']); ?>" enctype="multipart/form-data" class="space-y-6">
            <!-- Name and Category Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Meal Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        value="<?php echo htmlspecialchars($meal['name']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                    <select
                        id="category_id"
                        name="category_id"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo ($meal['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                ><?php echo htmlspecialchars($meal['description'] ?? ''); ?></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Meal Image</label>

                <?php if (!empty($meal['image'])): ?>
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-3">Current Image:</p>
                        <img src="<?php echo base_url('upload/meals/' . htmlspecialchars($meal['image'])); ?>" alt="<?php echo htmlspecialchars($meal['name']); ?>" class="h-32 rounded-lg object-cover">
                    </div>
                <?php endif; ?>

                <div class="mt-2" x-data="{ fileName: '' }" @change="fileName = $refs.fileInput.files[0]?.name || ''">
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/jpg,image/jpeg,image/png,image/gif,image/webp"
                        x-ref="fileInput"
                        class="hidden"
                    >
                    <label for="image" class="flex items-center justify-center w-full px-6 py-4 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-red-600 transition">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-700 font-semibold">Click to upload new image</p>
                            <p class="text-sm text-gray-500">PNG, JPG, GIF, WebP up to 5MB</p>
                            <p class="text-sm text-red-600 font-semibold mt-2" x-show="fileName" x-text="fileName"></p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- SKU Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-gray-700 mb-2">SKU *</label>
                    <input
                        type="text"
                        id="sku"
                        name="sku"
                        required
                        value="<?php echo htmlspecialchars($meal['sku'] ?? ''); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
            </div>

            <!-- Pricing Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cost_price" class="block text-sm font-semibold text-gray-700 mb-2">Cost Price (<?php echo get_currency(); ?>) *</label>
                    <input
                        type="number"
                        id="cost_price"
                        name="cost_price"
                        required
                        step="0.01"
                        min="0"
                        value="<?php echo number_format($meal['cost_price'], 2, '.', ''); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="selling_price" class="block text-sm font-semibold text-gray-700 mb-2">Selling Price (<?php echo get_currency(); ?>) *</label>
                    <input
                        type="number"
                        id="selling_price"
                        name="selling_price"
                        required
                        step="0.01"
                        min="0"
                        value="<?php echo number_format($meal['selling_price'], 2, '.', ''); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
            </div>

            <!-- Status Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="active" <?php echo ($meal['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($meal['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Update Meal
                </button>
                <a
                    href="<?php echo base_url('meals'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
