<!-- Edit Product Form -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Edit Product</h2>
            <p class="text-gray-600 mt-1">Update product information and pricing</p>
        </div>
        <a href="<?php echo base_url('products'); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
            <i class="fas fa-arrow-left mr-1"></i>Back to Products
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg p-8 border border-gray-200">
            <form method="POST" action="<?php echo base_url('products/update/' . $product['id']); ?>" enctype="multipart/form-data" class="space-y-6">
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    value="<?php echo htmlspecialchars($product['name']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                ><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>

                <?php if (!empty($product['image'])): ?>
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600 mb-3">Current Image:</p>
                        <img src="<?php echo base_url('upload/products/' . htmlspecialchars($product['image'])); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-32 rounded-lg object-cover">
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

            <!-- SKU and Barcode Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-semibold text-gray-700 mb-2">SKU *</label>
                    <input
                        type="text"
                        id="sku"
                        name="sku"
                        required
                        value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="barcode" class="block text-sm font-semibold text-gray-700 mb-2">Barcode</label>
                    <div class="flex gap-2">
                        <input
                            type="text"
                            id="barcode"
                            name="barcode"
                            value="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                        <button
                            type="button"
                            onclick="generateNewBarcode()"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm"
                        >
                            <i class="fas fa-sync mr-1"></i>Generate
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Click "Generate" to create a new unique barcode</p>
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
                        value="<?php echo number_format($product['cost_price'], 2, '.', ''); ?>"
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
                        value="<?php echo number_format($product['selling_price'], 2, '.', ''); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
            </div>

            <!-- Stock Levels Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="min_stock_level" class="block text-sm font-semibold text-gray-700 mb-2">Minimum Stock Level *</label>
                    <input
                        type="number"
                        id="min_stock_level"
                        name="min_stock_level"
                        required
                        min="0"
                        value="<?php echo $product['min_stock_level']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="max_stock_level" class="block text-sm font-semibold text-gray-700 mb-2">Maximum Stock Level *</label>
                    <input
                        type="number"
                        id="max_stock_level"
                        name="max_stock_level"
                        required
                        min="0"
                        value="<?php echo $product['max_stock_level']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">Unit of Measurement *</label>
                    <select
                        id="unit"
                        name="unit"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="piece" <?php echo ($product['unit'] === 'piece') ? 'selected' : ''; ?>>Piece</option>
                        <option value="kg" <?php echo ($product['unit'] === 'kg') ? 'selected' : ''; ?>>Kilogram (kg)</option>
                        <option value="liter" <?php echo ($product['unit'] === 'liter') ? 'selected' : ''; ?>>Liter (L)</option>
                        <option value="gram" <?php echo ($product['unit'] === 'gram') ? 'selected' : ''; ?>>Gram (g)</option>
                        <option value="ml" <?php echo ($product['unit'] === 'ml') ? 'selected' : ''; ?>>Milliliter (ml)</option>
                        <option value="dozen" <?php echo ($product['unit'] === 'dozen') ? 'selected' : ''; ?>>Dozen</option>
                        <option value="box" <?php echo ($product['unit'] === 'box') ? 'selected' : ''; ?>>Box</option>
                    </select>
                </div>
            </div>

            <!-- Stock and Status Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Current Stock *</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        required
                        min="0"
                        value="<?php echo $product['stock']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                </div>
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="active" <?php echo ($product['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($product['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
                <a
                    href="<?php echo base_url('products'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateNewBarcode() {
    // Generate barcode in format: PROD-TIMESTAMP-RANDOM
    const timestamp = Math.floor(Date.now() / 1000);
    const random = Math.random().toString(16).substring(2, 6).toUpperCase();
    const barcode = 'PROD-' + timestamp + '-' + random;

    document.getElementById('barcode').value = barcode;
}
</script>
