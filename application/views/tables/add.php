<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Table</h2>

        <form method="POST" action="<?php echo base_url('tables/create'); ?>" class="space-y-6">
            <!-- Table Number Field -->
            <div>
                <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-2">Table Number *</label>
                <input
                    type="number"
                    id="table_number"
                    name="table_number"
                    required
                    min="1"
                    placeholder="e.g., 1, 2, 3..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                <p class="text-xs text-gray-500 mt-1">A unique number to identify this table</p>
            </div>

            <!-- Capacity Field -->
            <div>
                <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Seating Capacity *</label>
                <input
                    type="number"
                    id="capacity"
                    name="capacity"
                    required
                    min="1"
                    max="20"
                    placeholder="e.g., 2, 4, 6..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                <p class="text-xs text-gray-500 mt-1">Number of seats at this table</p>
            </div>

            <!-- Location Field -->
            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location (Optional)</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    placeholder="e.g., Window, Corner, Middle..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                <p class="text-xs text-gray-500 mt-1">Where this table is located in the restaurant</p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    All new tables are set to "Available" status by default.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-plus mr-2"></i> Add Table
                </button>
                <a
                    href="<?php echo base_url('tables'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
