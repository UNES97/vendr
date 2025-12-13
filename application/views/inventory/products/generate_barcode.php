<!-- Generate Barcode PDF -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Generate Barcode Labels</h2>
            <p class="text-gray-600 mt-1">Create printable barcode labels for <?php echo htmlspecialchars($product['name']); ?></p>
        </div>
        <a href="<?php echo base_url('products'); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
            <i class="fas fa-arrow-left mr-1"></i>Back to Products
        </a>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Configuration Section -->
        <div class="bg-white rounded-lg p-8 border border-gray-200 mb-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Barcode Configuration</h3>

            <form method="POST" action="<?php echo base_url('products/download_barcode_pdf/' . $product['id']); ?>" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Barcode Value -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Barcode Value</label>
                        <input
                            type="text"
                            id="barcodeValue"
                            name="barcode_value"
                            value="<?php echo htmlspecialchars($barcode); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                            required
                        >
                        <p class="text-xs text-gray-600 mt-1">The value to encode in the barcode</p>
                    </div>

                    <!-- Barcode Format -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Barcode Format</label>
                        <select
                            name="barcode_format"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                            <option value="CODE128">CODE128 (Recommended)</option>
                            <option value="CODE39">CODE39</option>
                            <option value="EAN13">EAN13</option>
                            <option value="UPCA">UPC-A</option>
                            <option value="CODE11">CODE11</option>
                        </select>
                        <p class="text-xs text-gray-600 mt-1">Select barcode encoding format</p>
                    </div>

                    <!-- Number of Pages -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Pages</label>
                        <input
                            type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            max="10"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                        <p class="text-xs text-gray-600 mt-1">Each page contains 50 labels (1-10 pages)</p>
                    </div>

                    <!-- Labels Layout Info -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Page Layout</label>
                        <div class="px-4 py-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-900">50 labels per A4 page</p>
                            <p class="text-xs text-blue-700 mt-1">5 columns × 10 rows</p>
                        </div>
                    </div>
                </div>

                <!-- Product Information Card -->
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Product Information</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">Product Name</p>
                            <p class="text-sm text-gray-900 mt-1"><?php echo htmlspecialchars($product['name']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">SKU</p>
                            <p class="text-sm text-gray-900 mt-1"><?php echo htmlspecialchars($product['sku']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">Selling Price</p>
                            <p class="text-sm text-gray-900 font-semibold mt-1"><?php echo format_price($product['selling_price']); ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 font-semibold">Current Stock</p>
                            <p class="text-sm text-gray-900 mt-1"><?php echo $product['stock']; ?> units</p>
                        </div>
                    </div>
                </div>

                <!-- Button Group -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button
                        type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-3 rounded-lg transition text-sm flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-download"></i>Generate PDF
                    </button>
                    <a
                        href="<?php echo base_url('products'); ?>"
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Label Specifications -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-ruler text-red-600"></i>Label Specifications
                </h4>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 font-bold">•</span>
                        <span><strong>Size Per Label:</strong> ~40mm × 29.7mm (standard adhesive)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 font-bold">•</span>
                        <span><strong>Per Page:</strong> 50 labels (5 columns × 10 rows)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 font-bold">•</span>
                        <span><strong>Page Size:</strong> A4 (210mm × 297mm)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-red-600 font-bold">•</span>
                        <span><strong>Content:</strong> Product code, product name, barcode image, barcode value</span>
                    </li>
                </ul>
            </div>

            <!-- Print Instructions -->
            <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-print text-blue-600"></i>Print Instructions
                </h4>
                <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside">
                    <li>Click "Generate PDF" to create the barcode labels PDF</li>
                    <li>The PDF will download to your computer</li>
                    <li>Open the PDF file in your PDF viewer</li>
                    <li>Print on A4 label sheets or regular paper</li>
                    <li>Cut along the label borders if using regular paper</li>
                    <li>Attach labels to products</li>
                </ol>
            </div>
        </div>

        <!-- Barcode Format Reference -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h4 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle"></i>Barcode Formats Reference
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">CODE128</p>
                    <p class="text-xs text-blue-800">Alphanumeric, widely used for shipping and inventory. Best general-purpose choice.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">CODE39</p>
                    <p class="text-xs text-blue-800">Simple format, supports A-Z, 0-9, and some symbols. Good for simple applications.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">EAN13</p>
                    <p class="text-xs text-blue-800">13-digit format commonly used in retail. International standard for products.</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900 mb-2">UPC-A</p>
                    <p class="text-xs text-blue-800">12-digit format primarily used in North America for retail products.</p>
                </div>
            </div>
        </div>
    </div>
</div>
