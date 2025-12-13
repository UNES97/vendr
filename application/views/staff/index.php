<div class="max-w-full px-4 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Staff Members</h1>
                <p class="text-gray-600 mt-2">Manage your team members, roles, and permissions</p>
            </div>
            <a href="<?php echo base_url('staff/add'); ?>" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i> Add New User
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

    <?php if ($this->session->flashdata('warning')): ?>
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
            <span class="text-yellow-800"><?php echo $this->session->flashdata('warning'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Staff Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Phone</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($staff)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 text-lg">No staff members yet</p>
                                    <p class="text-gray-400 text-sm mt-1">Get started by adding your first team member</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($staff as $member): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            <?php echo substr($member['name'], 0, 1); ?>
                                        </div>
                                        <p class="font-medium text-gray-900"><?php echo htmlspecialchars($member['name']); ?></p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo htmlspecialchars($member['email']); ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?php echo (!empty($member['phone'])) ? htmlspecialchars($member['phone']) : 'N/A'; ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                        $role_colors = [
                                            'admin' => 'bg-red-100 text-red-800',
                                            'manager' => 'bg-blue-100 text-blue-800',
                                            'cashier' => 'bg-green-100 text-green-800',
                                            'chef' => 'bg-orange-100 text-orange-800',
                                            'waitress' => 'bg-purple-100 text-purple-800',
                                            'staff' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $color_class = $role_colors[$member['role']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $color_class; ?>">
                                        <?php echo ucfirst($member['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php
                                        $status_colors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'inactive' => 'bg-gray-100 text-gray-800',
                                            'suspended' => 'bg-red-100 text-red-800'
                                        ];
                                        $status_color = $status_colors[$member['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $status_color; ?>">
                                        <?php echo ucfirst($member['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Show disable/enable button only if not current user -->
                                        <?php if ($member['id'] != user_id()): ?>
                                            <?php if ($member['status'] === 'active'): ?>
                                                <a href="<?php echo base_url('staff/toggle_status/' . $member['id']); ?>" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 p-2 rounded-lg transition" title="Disable User">
                                                    <i class="fas fa-lock-open"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo base_url('staff/toggle_status/' . $member['id']); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition" title="Enable User">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <a href="<?php echo base_url('staff/edit/' . $member['id']); ?>" class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <!-- Don't allow deleting current user -->
                                        <?php if ($member['id'] != user_id()): ?>
                                            <a href="<?php echo base_url('staff/delete/' . $member['id']); ?>" onclick="return confirm('Are you sure?')" class="bg-red-100 hover:bg-red-200 text-red-700 p-2 rounded-lg transition" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
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
