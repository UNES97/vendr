<!-- Product Checkout -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Product Checkout</h2>
            <p class="text-gray-600 mt-1">Remove stock from inventory</p>
        </div>
        <a href="<?php echo base_url('products/checkout_list'); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm">
            <i class="fas fa-history mr-1"></i>Back to History
        </a>
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

    <!-- Autocomplete CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="max-w-4xl mx-auto">
        <!-- Checkout Form -->
        <div class="bg-white rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Remove Stock (Multiple Products)</h3>

                <form method="POST" action="<?php echo base_url('products/checkout'); ?>" enctype="multipart/form-data" class="space-y-4">
                    <!-- Barcode Scanner Input -->
                    <div>
                        <label for="barcode_scanner" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-barcode mr-1"></i>Scan Barcode or Search Product
                        </label>
                        <input
                            type="text"
                            id="barcode_scanner"
                            placeholder="Scan barcode or type product name/SKU..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                            autocomplete="off"
                        >
                        <p class="text-xs text-gray-500 mt-1">Press Enter after scanning or searching</p>
                    </div>

                    <!-- Product Selection with Autocomplete -->
                    <div>
                        <label for="product_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Product *</label>
                        <select
                            id="product_id"
                            placeholder="Search and select product..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['id']; ?>" data-current-stock="<?php echo $product['stock']; ?>" data-sku="<?php echo htmlspecialchars($product['sku']); ?>" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-barcode="<?php echo htmlspecialchars($product['barcode'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($product['name']); ?> (SKU: <?php echo htmlspecialchars($product['sku']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Current Stock Display -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">Current Stock</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="currentStock">-</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase">SKU</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1" id="productSku">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity to Remove -->
                    <div>
                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Quantity to Remove *</label>
                        <input
                            type="number"
                            id="quantity"
                            min="1"
                            step="1"
                            placeholder="e.g., 10"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                        <p class="text-xs text-gray-600 mt-1">Max available: <span id="maxQuantity">-</span></p>
                    </div>

                    <!-- Reason for Checkout -->
                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason for Checkout *</label>
                        <select
                            id="reason"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        >
                            <option value="">-- Select Reason --</option>
                            <option value="sale">Sale/Order</option>
                            <option value="waste">Waste/Spoilage</option>
                            <option value="damage">Damaged</option>
                            <option value="return">Return to Supplier</option>
                            <option value="adjustment">Inventory Adjustment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Add to Cart Button -->
                    <button
                        type="button"
                        onclick="addToCart()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm"
                    >
                        <i class="fas fa-plus mr-1"></i>Add to Cart
                    </button>

                    <!-- Cart Items -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden" id="cartContainer" style="display: none;">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700">Items to Checkout</h4>
                        </div>
                        <div class="divide-y divide-gray-200" id="cartItems"></div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Additional details about this checkout..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"
                        ></textarea>
                    </div>

                    <!-- Receipt Upload -->
                    <div>
                        <label for="receipt" class="block text-sm font-semibold text-gray-700 mb-2">Upload Receipt (Optional)</label>
                        <div class="relative">
                            <input
                                type="file"
                                id="receipt"
                                name="receipt"
                                accept=".pdf,.jpg,.jpeg,.png,.gif"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            <div class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 flex items-center justify-center hover:border-red-600 hover:bg-red-50 transition cursor-pointer" id="uploadBox">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-sm font-semibold text-gray-700">Click to upload receipt</p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG or GIF (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mt-2">File name: <span id="fileName" class="font-semibold text-gray-700">No file selected</span></p>
                    </div>

                    <!-- Hidden input to store cart data -->
                    <input type="hidden" id="cartData" name="cart_data" value="[]">

                    <!-- Submit Button -->
                    <div class="flex gap-2 pt-4 border-t border-gray-200">
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled
                        >
                            <i class="fas fa-arrow-up mr-1"></i>Complete Checkout
                        </button>
                        <a
                            href="<?php echo base_url('products'); ?>"
                            class="px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-sm"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let tomSelectInstance;

// Initialize Tom Select autocomplete
tomSelectInstance = new TomSelect('#product_id', {
    create: false,
    placeholder: 'Search and select product...',
    searchField: ['text', 'value'],
    onChange: function() {
        updateProductInfo();
    }
});

function updateProductInfo() {
    const select = document.getElementById('product_id');
    const selectedValue = select.value;
    const option = Array.from(select.options).find(opt => opt.value === selectedValue);

    if (option && selectedValue) {
        const currentStock = option.getAttribute('data-current-stock');
        document.getElementById('currentStock').textContent = currentStock;
        document.getElementById('productSku').textContent = option.getAttribute('data-sku');
        document.getElementById('maxQuantity').textContent = currentStock;
    } else {
        document.getElementById('currentStock').textContent = '-';
        document.getElementById('productSku').textContent = '-';
        document.getElementById('maxQuantity').textContent = '-';
    }
}

// Barcode Scanner Handler
document.getElementById('barcode_scanner').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const scannerValue = this.value.trim();

        if (!scannerValue) {
            alert('Please scan a barcode or enter a product');
            return;
        }

        const select = document.getElementById('product_id');
        const options = Array.from(select.options);

        // Search by barcode, SKU, or product name
        let foundOption = null;

        for (let option of options) {
            const barcode = option.getAttribute('data-barcode') || '';
            const sku = option.getAttribute('data-sku') || '';
            const name = option.getAttribute('data-name') || '';

            if (barcode === scannerValue || sku.toLowerCase() === scannerValue.toLowerCase() || name.toLowerCase().includes(scannerValue.toLowerCase())) {
                foundOption = option;
                break;
            }
        }

        if (foundOption && foundOption.value) {
            // Select the product
            tomSelectInstance.setValue(foundOption.value);
            updateProductInfo();

            // Focus on quantity field
            document.getElementById('quantity').focus();

            // Clear scanner input
            this.value = '';
        } else {
            alert('Product not found. Please check the barcode/SKU.');
            this.value = '';
        }
    }
});

function addToCart() {
    const productId = document.getElementById('product_id').value;
    const quantity = parseInt(document.getElementById('quantity').value) || 0;
    const reason = document.getElementById('reason').value;
    const select = document.getElementById('product_id');
    const option = Array.from(select.options).find(opt => opt.value === productId);
    const maxQuantity = parseInt(option?.getAttribute('data-current-stock') || 0);

    if (!productId) {
        alert('Please select a product');
        return;
    }
    if (quantity <= 0) {
        alert('Please enter a valid quantity');
        return;
    }
    if (!reason) {
        alert('Please select a reason');
        return;
    }
    if (quantity > maxQuantity) {
        alert('Quantity cannot exceed available stock (' + maxQuantity + ')');
        return;
    }

    // Check if product already in cart
    const existingItem = cart.find(item => item.product_id === productId);
    if (existingItem) {
        if (existingItem.quantity + quantity > maxQuantity) {
            alert('Total quantity for this product would exceed available stock (' + maxQuantity + ')');
            return;
        }
        existingItem.quantity += quantity;
    } else {
        cart.push({
            product_id: productId,
            product_name: option.getAttribute('data-name'),
            sku: option.getAttribute('data-sku'),
            quantity: quantity,
            reason: reason
        });
    }

    // Reset form
    document.getElementById('product_id').value = '';
    document.getElementById('quantity').value = '';
    document.getElementById('reason').value = '';
    updateProductInfo();

    // Update cart display
    renderCart();
}

function renderCart() {
    const cartContainer = document.getElementById('cartContainer');
    const cartItems = document.getElementById('cartItems');
    const submitBtn = document.getElementById('submitBtn');
    const cartData = document.getElementById('cartData');

    if (cart.length === 0) {
        cartContainer.style.display = 'none';
        submitBtn.disabled = true;
        cartData.value = '[]';
        return;
    }

    cartContainer.style.display = 'block';
    submitBtn.disabled = false;
    cartData.value = JSON.stringify(cart);

    const reasonColors = {
        'sale': 'bg-blue-100 text-blue-800',
        'waste': 'bg-orange-100 text-orange-800',
        'damage': 'bg-red-100 text-red-800',
        'return': 'bg-yellow-100 text-yellow-800',
        'adjustment': 'bg-purple-100 text-purple-800',
        'other': 'bg-gray-100 text-gray-800'
    };

    cartItems.innerHTML = cart.map((item, index) => `
        <div class="px-4 py-3 flex items-center justify-between">
            <div class="flex-1">
                <p class="text-sm font-semibold text-gray-900">${escapeHtml(item.product_name)}</p>
                <p class="text-xs text-gray-500">SKU: ${escapeHtml(item.sku)}</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2 py-1 rounded text-xs font-semibold ${reasonColors[item.reason] || reasonColors['other']}">
                    ${escapeHtml(item.reason.replace('_', ' '))}
                </span>
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-lg font-semibold text-sm">
                    -${item.quantity}
                </span>
                <button
                    type="button"
                    onclick="removeFromCart(${index})"
                    class="text-red-600 hover:text-red-700 p-1"
                    title="Remove"
                >
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// File upload handler
document.getElementById('receipt').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileNameSpan = document.getElementById('fileName');
    const uploadBox = document.getElementById('uploadBox');

    if (file) {
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            this.value = '';
            fileNameSpan.textContent = 'No file selected';
            uploadBox.classList.remove('border-green-600', 'bg-green-50');
            uploadBox.classList.add('border-gray-300', 'bg-gray-50');
            return;
        }

        fileNameSpan.textContent = file.name;
        uploadBox.classList.remove('border-gray-300', 'bg-gray-50');
        uploadBox.classList.add('border-green-600', 'bg-green-50');
    } else {
        fileNameSpan.textContent = 'No file selected';
        uploadBox.classList.remove('border-green-600', 'bg-green-50');
        uploadBox.classList.add('border-gray-300', 'bg-gray-50');
    }
});

// Drag and drop
const uploadBox = document.getElementById('uploadBox');
const fileInput = document.getElementById('receipt');

uploadBox.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadBox.classList.add('border-red-600', 'bg-red-50');
});

uploadBox.addEventListener('dragleave', () => {
    if (!fileInput.files.length) {
        uploadBox.classList.remove('border-red-600', 'bg-red-50');
        uploadBox.classList.add('border-gray-300', 'bg-gray-50');
    }
});

uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
});
</script>
