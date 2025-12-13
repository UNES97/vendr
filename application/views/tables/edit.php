<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Table</h2>

        <form method="POST" action="<?php echo base_url('tables/update/' . $table['id']); ?>" class="space-y-6">
            <!-- Table Number Field -->
            <div>
                <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-2">Table Number *</label>
                <input
                    type="number"
                    id="table_number"
                    name="table_number"
                    required
                    min="1"
                    value="<?php echo htmlspecialchars($table['table_number']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
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
                    value="<?php echo htmlspecialchars($table['capacity']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Location Field -->
            <div>
                <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="<?php echo htmlspecialchars($table['location'] ?? ''); ?>"
                    placeholder="e.g., Window, Corner, Middle..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
            </div>

            <!-- Status Field -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                <select
                    id="status"
                    name="status"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                >
                    <option value="available" <?php echo $table['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="occupied" <?php echo $table['status'] === 'occupied' ? 'selected' : ''; ?>>Occupied</option>
                    <option value="maintenance" <?php echo $table['status'] === 'maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                </select>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6 border-t border-gray-200">
                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Update Table
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
