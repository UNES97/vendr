<!-- QR Code Management -->
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">QR Code Management</h1>
            <p class="text-gray-600 mt-1">Generate and manage QR codes for table-based online ordering</p>
        </div>
        <a
            href="<?php echo base_url('qr-codes/generate-all'); ?>"
            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center gap-2"
            onclick="return confirm('Generate QR codes for all tables? This may take a moment.');"
        >
            <i class="fas fa-qrcode"></i>
            Generate All QR Codes
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $this->session->flashdata('success'); ?></span>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo $this->session->flashdata('error'); ?></span>
        </div>
    <?php endif; ?>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            How to Use QR Codes
        </h3>
        <ul class="text-sm text-blue-800 space-y-1 ml-6 list-disc">
            <li>Generate QR codes for each table in your restaurant</li>
            <li>Print the QR codes and place them on corresponding tables</li>
            <li>Customers scan the QR code to access the menu and place orders</li>
            <li>Orders are automatically linked to the table number</li>
            <li>Download individual QR codes or generate all at once</li>
        </ul>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($tables as $table): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 text-white px-4 py-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold">Table <?php echo htmlspecialchars($table['table_number']); ?></h3>
                        <?php if (!empty($table['location'])): ?>
                            <p class="text-sm text-gray-300"><?php echo htmlspecialchars($table['location']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-300">Capacity:</span>
                        <p class="text-lg font-bold"><?php echo $table['capacity']; ?></p>
                    </div>
                </div>

                <div class="p-4">
                    <?php if (!empty($table['qr_code'])): ?>
                        <!-- QR Code Generated -->
                        <div class="text-center mb-4">
                            <img
                                src="<?php echo base_url('qr-codes/preview/' . $table['id']); ?>"
                                alt="QR Code for Table <?php echo $table['table_number']; ?>"
                                class="w-48 h-48 mx-auto border-2 border-gray-300 rounded-lg"
                            >
                            <p class="text-xs text-gray-500 mt-2">
                                Generated: <?php echo date('M j, Y g:i A', strtotime($table['qr_code_generated_at'])); ?>
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a
                                href="<?php echo base_url('qr-codes/download/' . $table['id']); ?>"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition text-center"
                            >
                                <i class="fas fa-download"></i> Download
                            </a>
                            <a
                                href="<?php echo base_url('qr-codes/generate/' . $table['id']); ?>"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition text-center"
                                onclick="return confirm('Regenerate QR code for Table <?php echo $table['table_number']; ?>?');"
                            >
                                <i class="fas fa-sync-alt"></i> Regenerate
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- No QR Code -->
                        <div class="text-center py-8">
                            <i class="fas fa-qrcode text-gray-300 text-5xl mb-3"></i>
                            <p class="text-gray-500 mb-4">No QR code generated yet</p>
                            <a
                                href="<?php echo base_url('qr-codes/generate/' . $table['id']); ?>"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition inline-flex items-center gap-2"
                            >
                                <i class="fas fa-plus"></i>
                                Generate QR Code
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- QR URL (for reference) -->
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    <p class="text-xs text-gray-600 mb-1">Menu URL:</p>
                    <code class="text-xs bg-gray-200 px-2 py-1 rounded block overflow-x-auto">
                        <?php echo base_url('menu/table/' . $table['id']); ?>
                    </code>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($tables)): ?>
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-chair text-gray-300 text-5xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Tables Found</h3>
            <p class="text-gray-600 mb-4">Create tables first to generate QR codes</p>
            <a href="<?php echo base_url('tables'); ?>" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition inline-flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Manage Tables
            </a>
        </div>
    <?php endif; ?>

    <!-- Print Tips -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-print"></i>
            Printing Tips
        </h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Best Practices:</h4>
                <ul class="space-y-1 ml-4 list-disc">
                    <li>Print on high-quality paper or cardstock</li>
                    <li>Use a color printer for best results</li>
                    <li>Laminate QR codes to prevent wear and tear</li>
                    <li>Test each QR code after printing</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900 mb-2">Recommended Sizes:</h4>
                <ul class="space-y-1 ml-4 list-disc">
                    <li>Table tents: 4" × 6" (10 × 15 cm)</li>
                    <li>Table stickers: 3" × 3" (7.5 × 7.5 cm)</li>
                    <li>Wall posters: 8" × 10" (20 × 25 cm)</li>
                    <li>Minimum scan distance: 6 inches (15 cm)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Preview Modal (Optional - for future enhancement) -->
    <div id="preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" onclick="this.classList.add('hidden')">
        <div class="bg-white rounded-lg p-6 max-w-md" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold">QR Code Preview</h3>
                <button onclick="document.getElementById('preview-modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <img id="preview-image" src="" alt="QR Code Preview" class="w-full">
        </div>
    </div>
</div>
