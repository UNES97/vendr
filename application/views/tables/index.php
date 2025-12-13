<div class="max-w-full px-4 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Restaurant Tables</h1>
                <p class="text-gray-600 mt-2">Manage your dining tables and seating capacity</p>
            </div>
            <a href="<?php echo base_url('tables/add'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i> Add New Table
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

    <?php if ($this->session->flashdata('error')): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <span class="text-red-800"><?php echo $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Tables</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo $total_tables; ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                    <i class="fas fa-table text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Available</p>
                    <p class="text-3xl font-bold text-green-600 mt-2"><?php echo $available_tables; ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Occupied</p>
                    <p class="text-3xl font-bold text-red-600 mt-2"><?php echo $occupied_tables; ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Table Number</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Capacity</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Location</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tables)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-table text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 text-lg">No tables configured</p>
                                    <p class="text-gray-400 text-sm mt-1">Add your first table to get started</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tables as $table): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            T<?php echo $table['table_number']; ?>
                                        </div>
                                        <p class="font-medium text-gray-900">Table <?php echo $table['table_number']; ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <?php echo $table['capacity']; ?> seats
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?php echo !empty($table['location']) ? htmlspecialchars($table['location']) : 'N/A'; ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                        $status_colors = [
                                            'available' => 'bg-green-100 text-green-800',
                                            'occupied' => 'bg-red-100 text-red-800',
                                            'maintenance' => 'bg-yellow-100 text-yellow-800'
                                        ];
                                        $status_color = $status_colors[$table['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $status_color; ?>">
                                        <?php echo ucfirst($table['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="<?php echo base_url('tables/edit/' . $table['id']); ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="Edit Table">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url('tables/delete/' . $table['id']); ?>" onclick="return confirm('Are you sure you want to delete this table?')" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete Table">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
