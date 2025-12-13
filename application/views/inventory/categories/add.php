<!-- Add Category Form -->
<div class="max-w-4xl mx-auto px-4 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="<?php echo base_url('inventory/categories'); ?>" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Categories
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg p-8 border border-gray-200">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Add Meal Category</h2>

        <form method="POST" action="<?php echo base_url('inventory/add_category'); ?>" class="space-y-6">
            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    placeholder="e.g., Appetizers, Main Course, Desserts"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Category Type (Hidden - Always Meal) -->
            <input type="hidden" name="type" value="meal">

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Category description and details..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-2 pt-4 border-t border-gray-200">
                <a
                    href="<?php echo base_url('inventory/categories'); ?>"
                    class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                >
                    <i class="fas fa-times mr-1"></i>Cancel
                </a>

                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg text-sm transition"
                >
                    <i class="fas fa-save mr-1"></i>Create Category
                </button>
            </div>
        </form>
    </div>
</div>
