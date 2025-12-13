<!-- Products Management -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Products</h2>
            <p class="text-gray-600 mt-1">Manage and track all products</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo base_url('products/export?' . http_build_query($filters)); ?>" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-download"></i> Export
            </a>
            <a href="<?php echo base_url('products/add'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Product
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
        <form method="GET" action="<?php echo base_url('products'); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <!-- Search -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Search</label>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                        placeholder="Name, SKU, barcode..."
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
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

                <!-- Sort By -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Sort</label>
                    <select
                        name="sort_by"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                    >
                        <option value="created_at" <?php echo ($filters['sort_by'] === 'created_at') ? 'selected' : ''; ?>>Newest</option>
                        <option value="name" <?php echo ($filters['sort_by'] === 'name') ? 'selected' : ''; ?>>Name</option>
                        <option value="stock" <?php echo ($filters['sort_by'] === 'stock') ? 'selected' : ''; ?>>Stock</option>
                        <option value="selling_price" <?php echo ($filters['sort_by'] === 'selling_price') ? 'selected' : ''; ?>>Price</option>
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
                        href="<?php echo base_url('products'); ?>"
                        class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                        title="Reset filters"
                    >
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Image</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Product Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">SKU</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Cost Price</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Selling Price</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Current Stock</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <?php
                                $stock = $product['stock'];
                                $min_level = $product['min_stock_level'];

                                if ($stock <= $min_level) {
                                    $row_class = 'bg-red-50';
                                } elseif ($stock <= ($min_level * 1.5)) {
                                    $row_class = 'bg-yellow-50';
                                } else {
                                    $row_class = 'hover:bg-gray-50';
                                }
                            ?>
                            <tr class="border-b border-gray-200 <?php echo $row_class; ?> transition">
                                <td class="px-6 py-4">
                                    <?php if (!empty($product['image'])): ?>
                                        <img
                                            src="<?php echo base_url('upload/products/' . htmlspecialchars($product['image'])); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            class="w-12 h-12 rounded-lg object-cover"
                                        >
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 50)); ?><?php echo strlen($product['description'] ?? '') > 50 ? '...' : ''; ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($product['sku'] ?? '-'); ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    <?php echo format_price($product['cost_price']); ?>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <span class="font-semibold text-green-600"><?php echo format_price($product['selling_price']); ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-gray-900">
                                        <?php echo $product['stock']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                        $status = $product['status'];
                                        $status_class = ($status === 'active') ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $status_class; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="<?php echo base_url('products/generate_barcode/' . $product['id']); ?>" class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition" title="Generate Barcode">
                                            <i class="fas fa-barcode"></i>
                                        </a>
                                        <a href="<?php echo base_url('products/edit/' . $product['id']); ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('products/delete/' . $product['id']); ?>" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
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
                                <p class="text-lg mt-2">No products found</p>
                                <p class="text-sm mt-1">
                                    <a href="<?php echo base_url('products/add'); ?>" class="text-red-600 hover:underline">Add your first product</a>
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

    <!-- Stock Alert Summary -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-red-800 font-semibold">Low Stock Items</p>
                    <p class="text-red-600 text-sm">Products below minimum level</p>
                </div>
            </div>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-yellow-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-yellow-800 font-semibold">Medium Stock Items</p>
                    <p class="text-yellow-600 text-sm">Products approaching low level</p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 text-2xl mr-4"></i>
                <div>
                    <p class="text-green-800 font-semibold">Adequate Stock Items</p>
                    <p class="text-green-600 text-sm">Products with sufficient inventory</p>
                </div>
            </div>
        </div>
    </div>
</div>
