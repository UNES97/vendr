<!-- Add Expense Page -->
<div class="max-w-full px-4 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Add New Expense</h2>
            <p class="text-gray-600 mt-1">Create a new expense record</p>
        </div>
        <a href="<?php echo base_url('expense'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
        <form id="expense-form" onsubmit="submitExpenseForm(event)" class="space-y-6">
            <!-- Row 1: Category and Amount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-600">*</span>
                    </label>
                    <select id="form-category" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Amount (<?php echo get_currency(); ?>) <span class="text-red-600">*</span>
                    </label>
                    <input type="number" id="form-amount" placeholder="0.00" step="0.01" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                </div>
            </div>

            <!-- Row 2: Date and Payment Method -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Date <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="form-date" placeholder="Select date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Payment Method <span class="text-red-600">*</span>
                    </label>
                    <select id="form-payment-method" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="cheque">Cheque</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Description <span class="text-red-600">*</span>
                </label>
                <textarea id="form-description" placeholder="What is this expense for?" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"></textarea>
            </div>

            <!-- Row 4: Reference and Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Reference Number</label>
                    <input type="text" id="form-reference" placeholder="e.g., INV-001, CHQ-123" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="form-notes" placeholder="Additional notes..." rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent outline-none transition"></textarea>
                </div>
            </div>

            <!-- Row 5: Attachment -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Attachment (Receipt, Invoice, etc.)</label>
                <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-red-600 transition cursor-pointer" onclick="document.getElementById('form-attachment').click()">
                    <input type="file" id="form-attachment" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx" onchange="updateFileLabel()">
                    <div id="file-label">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2 block"></i>
                        <p class="text-gray-700 font-semibold">Click to upload or drag and drop</p>
                        <p class="text-gray-500 text-sm">PNG, JPG, PDF, DOC, XLS (Max 10MB)</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="<?php echo base_url('expense'); ?>" class="flex-1 px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded-lg transition text-center">
                    Cancel
                </a>
                <button type="submit" class="flex-1 px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Save Expense
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
// Initialize Flatpickr
flatpickr("#form-date", {
    mode: "single",
    dateFormat: "Y-m-d",
    defaultDate: new Date(),
    monthSelectorType: "dropdown",
    minDate: "2020-01-01",
    maxDate: new Date()
});

// Update file label
function updateFileLabel() {
    const fileInput = document.getElementById('form-attachment');
    const fileLabel = document.getElementById('file-label');

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);

        fileLabel.innerHTML = `
            <i class="fas fa-file text-3xl text-red-600 mb-2 block"></i>
            <p class="text-gray-700 font-semibold">${fileName}</p>
            <p class="text-gray-500 text-sm">${fileSize} MB</p>
        `;
    }
}

// Submit form
function submitExpenseForm(event) {
    event.preventDefault();

    const formData = new FormData();
    formData.append('category_id', document.getElementById('form-category').value);
    formData.append('amount', document.getElementById('form-amount').value);
    formData.append('description', document.getElementById('form-description').value);
    formData.append('payment_method', document.getElementById('form-payment-method').value);
    formData.append('reference_number', document.getElementById('form-reference').value);
    formData.append('notes', document.getElementById('form-notes').value);

    // Add attachment if exists
    const fileInput = document.getElementById('form-attachment');
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];

        // Validate file size (10MB max)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }

        formData.append('attachment', file);
    }

    // Validate amount
    if (parseFloat(formData.get('amount')) <= 0) {
        alert('Amount must be greater than 0');
        return;
    }

    fetch('<?php echo base_url('expense/create'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            window.location.href = '<?php echo base_url('expense'); ?>';
        } else {
            alert(data.error || 'Failed to save expense');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
</script>
