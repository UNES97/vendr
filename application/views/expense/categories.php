<!-- Expense Categories Management -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Expense Categories</h2>
            <p class="text-gray-600 mt-1">Manage and organize expense categories</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openAddCategoryModal()" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Category
            </button>
            <a href="<?php echo base_url('expense'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Icon</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Category Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="categories-table-body">
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 opacity-30"></i>
                                <p class="text-lg">No categories found</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition" data-category-id="<?php echo $cat['id']; ?>">
                                <td class="px-6 py-4 text-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                                        <i class="<?php echo htmlspecialchars($cat['icon'] ?? 'fas fa-tag'); ?>"></i>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($cat['description'] ?? '-'); ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $cat['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo htmlspecialchars(ucfirst($cat['status'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="openEditCategoryModal(<?php echo $cat['id']; ?>)" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteCategory(<?php echo $cat['id']; ?>)" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div id="category-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-md">
        <!-- Modal Header -->
        <div class="bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">
                <span id="modal-title">Add Category</span>
            </h2>
            <button onclick="closeCategoryModal()" class="text-gray-500 hover:text-gray-700 text-xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name <span class="text-red-600">*</span></label>
                <input type="text" id="modal-name" placeholder="e.g., Utilities, Salaries" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea id="modal-description" placeholder="What is this category for?" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Icon</label>
                <input type="text" id="modal-icon" placeholder="e.g., fas fa-bolt" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                <p class="text-xs text-gray-500 mt-1">Font Awesome class (e.g., fas fa-bolt, fas fa-users)</p>
            </div>

            <div id="status-section" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select id="modal-status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end border-t border-gray-200">
            <button onclick="closeCategoryModal()" class="px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition">
                Cancel
            </button>
            <button onclick="saveCategory()" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition" id="save-btn">
                <i class="fas fa-save mr-2"></i>Save Category
            </button>
        </div>
    </div>
</div>

<script>
let currentCategoryId = null;

// Open add category modal
function openAddCategoryModal() {
    currentCategoryId = null;
    document.getElementById('modal-title').textContent = 'Add Category';
    document.getElementById('save-btn').innerHTML = '<i class="fas fa-save mr-2"></i>Save Category';
    document.getElementById('status-section').classList.add('hidden');
    clearCategoryForm();
    document.getElementById('category-modal').classList.remove('hidden');
}

// Open edit category modal
function openEditCategoryModal(categoryId) {
    currentCategoryId = categoryId;
    document.getElementById('modal-title').textContent = 'Edit Category';
    document.getElementById('save-btn').innerHTML = '<i class="fas fa-edit mr-2"></i>Update Category';
    document.getElementById('status-section').classList.remove('hidden');

    const categoryCard = document.querySelector(`[data-category-id="${categoryId}"]`);
    if (!categoryCard) return;

    const nameEl = categoryCard.querySelector('h3');
    const statusEl = categoryCard.querySelector('p:nth-of-type(1)');
    const descEl = categoryCard.querySelector('p:nth-of-type(2)');
    const iconEl = categoryCard.querySelector('i');

    document.getElementById('modal-name').value = nameEl.textContent.trim();
    document.getElementById('modal-description').value = descEl ? descEl.textContent.trim() : '';
    document.getElementById('modal-icon').value = iconEl ? iconEl.className : '';
    document.getElementById('modal-status').value = statusEl.textContent.toLowerCase().trim();
    document.getElementById('category-modal').classList.remove('hidden');
}

// Close modal
function closeCategoryModal() {
    document.getElementById('category-modal').classList.add('hidden');
    clearCategoryForm();
    currentCategoryId = null;
}

// Clear form
function clearCategoryForm() {
    document.getElementById('modal-name').value = '';
    document.getElementById('modal-description').value = '';
    document.getElementById('modal-icon').value = 'fas fa-tag';
    document.getElementById('modal-status').value = 'active';
}

// Save category
function saveCategory() {
    const name = document.getElementById('modal-name').value.trim();
    const description = document.getElementById('modal-description').value.trim();
    const icon = document.getElementById('modal-icon').value.trim() || 'fas fa-tag';
    const status = document.getElementById('modal-status').value;

    if (!name) {
        alert('Please enter a category name');
        return;
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('icon', icon);
    if (currentCategoryId) {
        formData.append('status', status);
    }

    const url = currentCategoryId
        ? '<?php echo base_url('expense/update_category/'); ?>' + currentCategoryId
        : '<?php echo base_url('expense/create_category'); ?>';

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeCategoryModal();
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to save category');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}

// Delete category
function deleteCategory(categoryId) {
    if (!confirm('Are you sure you want to delete this category?')) {
        return;
    }

    const url = '<?php echo base_url('expense/delete_category/'); ?>' + categoryId;
    fetch(url, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.error || 'Failed to delete category');
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
}
</script>
