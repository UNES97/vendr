<div class="max-w-full px-4 lg:px-8">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">General Settings</h1>
                <p class="text-gray-600 mt-2">Configure application name, language, timezone, and date/time formats</p>
            </div>
            <a href="<?php echo base_url('settings'); ?>" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
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

    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <form method="POST" action="<?php echo base_url('settings/update_general'); ?>" class="space-y-6">
            <!-- Application Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cog text-blue-600 mr-3"></i> Application Settings
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- App Name -->
                    <div>
                        <label for="app_name" class="block text-sm font-semibold text-gray-700 mb-2">Application Name *</label>
                        <input
                            type="text"
                            id="app_name"
                            name="app_name"
                            required
                            value="<?php echo htmlspecialchars($app_name); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                            placeholder="Restaurant POS System"
                        >
                        <p class="text-xs text-gray-500 mt-1">Displayed in browser tabs and reports</p>
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-semibold text-gray-700 mb-2">Currency *</label>
                        <select
                            id="currency"
                            name="currency"
                            required
                            class="w-full select2-currency"
                        >
                            <!-- Asian Currencies -->
                            <optgroup label="Asian Currencies">
                                <option value="PKR" <?php echo $currency === 'PKR' ? 'selected' : ''; ?>>PKR - Pakistani Rupee</option>
                                <option value="INR" <?php echo $currency === 'INR' ? 'selected' : ''; ?>>INR - Indian Rupee</option>
                                <option value="BDT" <?php echo $currency === 'BDT' ? 'selected' : ''; ?>>BDT - Bangladeshi Taka</option>
                                <option value="LKR" <?php echo $currency === 'LKR' ? 'selected' : ''; ?>>LKR - Sri Lankan Rupee</option>
                                <option value="NPR" <?php echo $currency === 'NPR' ? 'selected' : ''; ?>>NPR - Nepalese Rupee</option>
                                <option value="AED" <?php echo $currency === 'AED' ? 'selected' : ''; ?>>AED - UAE Dirham</option>
                                <option value="SAR" <?php echo $currency === 'SAR' ? 'selected' : ''; ?>>SAR - Saudi Riyal</option>
                                <option value="KWD" <?php echo $currency === 'KWD' ? 'selected' : ''; ?>>KWD - Kuwaiti Dinar</option>
                                <option value="QAR" <?php echo $currency === 'QAR' ? 'selected' : ''; ?>>QAR - Qatari Riyal</option>
                                <option value="BHD" <?php echo $currency === 'BHD' ? 'selected' : ''; ?>>BHD - Bahraini Dinar</option>
                                <option value="OMR" <?php echo $currency === 'OMR' ? 'selected' : ''; ?>>OMR - Omani Rial</option>
                                <option value="JOD" <?php echo $currency === 'JOD' ? 'selected' : ''; ?>>JOD - Jordanian Dinar</option>
                                <option value="SGD" <?php echo $currency === 'SGD' ? 'selected' : ''; ?>>SGD - Singapore Dollar</option>
                                <option value="MYR" <?php echo $currency === 'MYR' ? 'selected' : ''; ?>>MYR - Malaysian Ringgit</option>
                                <option value="THB" <?php echo $currency === 'THB' ? 'selected' : ''; ?>>THB - Thai Baht</option>
                                <option value="IDR" <?php echo $currency === 'IDR' ? 'selected' : ''; ?>>IDR - Indonesian Rupiah</option>
                                <option value="PHP" <?php echo $currency === 'PHP' ? 'selected' : ''; ?>>PHP - Philippine Peso</option>
                                <option value="VND" <?php echo $currency === 'VND' ? 'selected' : ''; ?>>VND - Vietnamese Dong</option>
                                <option value="HKD" <?php echo $currency === 'HKD' ? 'selected' : ''; ?>>HKD - Hong Kong Dollar</option>
                                <option value="CNY" <?php echo $currency === 'CNY' ? 'selected' : ''; ?>>CNY - Chinese Yuan</option>
                                <option value="JPY" <?php echo $currency === 'JPY' ? 'selected' : ''; ?>>JPY - Japanese Yen</option>
                            </optgroup>

                            <!-- European Currencies -->
                            <optgroup label="European Currencies">
                                <option value="EUR" <?php echo $currency === 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                <option value="GBP" <?php echo $currency === 'GBP' ? 'selected' : ''; ?>>GBP - British Pound</option>
                                <option value="CHF" <?php echo $currency === 'CHF' ? 'selected' : ''; ?>>CHF - Swiss Franc</option>
                                <option value="SEK" <?php echo $currency === 'SEK' ? 'selected' : ''; ?>>SEK - Swedish Krona</option>
                                <option value="NOK" <?php echo $currency === 'NOK' ? 'selected' : ''; ?>>NOK - Norwegian Krone</option>
                                <option value="DKK" <?php echo $currency === 'DKK' ? 'selected' : ''; ?>>DKK - Danish Krone</option>
                                <option value="PLN" <?php echo $currency === 'PLN' ? 'selected' : ''; ?>>PLN - Polish Zloty</option>
                                <option value="CZK" <?php echo $currency === 'CZK' ? 'selected' : ''; ?>>CZK - Czech Koruna</option>
                                <option value="HUF" <?php echo $currency === 'HUF' ? 'selected' : ''; ?>>HUF - Hungarian Forint</option>
                                <option value="RON" <?php echo $currency === 'RON' ? 'selected' : ''; ?>>RON - Romanian Leu</option>
                            </optgroup>

                            <!-- Americas Currencies -->
                            <optgroup label="Americas Currencies">
                                <option value="USD" <?php echo $currency === 'USD' ? 'selected' : ''; ?>>USD - US Dollar</option>
                                <option value="CAD" <?php echo $currency === 'CAD' ? 'selected' : ''; ?>>CAD - Canadian Dollar</option>
                                <option value="MXN" <?php echo $currency === 'MXN' ? 'selected' : ''; ?>>MXN - Mexican Peso</option>
                                <option value="BRL" <?php echo $currency === 'BRL' ? 'selected' : ''; ?>>BRL - Brazilian Real</option>
                                <option value="ARS" <?php echo $currency === 'ARS' ? 'selected' : ''; ?>>ARS - Argentine Peso</option>
                                <option value="CLP" <?php echo $currency === 'CLP' ? 'selected' : ''; ?>>CLP - Chilean Peso</option>
                                <option value="COP" <?php echo $currency === 'COP' ? 'selected' : ''; ?>>COP - Colombian Peso</option>
                                <option value="PEN" <?php echo $currency === 'PEN' ? 'selected' : ''; ?>>PEN - Peruvian Sol</option>
                            </optgroup>

                            <!-- African & Oceania -->
                            <optgroup label="African & Oceania Currencies">
                                <option value="ZAR" <?php echo $currency === 'ZAR' ? 'selected' : ''; ?>>ZAR - South African Rand</option>
                                <option value="EGP" <?php echo $currency === 'EGP' ? 'selected' : ''; ?>>EGP - Egyptian Pound</option>
                                <option value="NGN" <?php echo $currency === 'NGN' ? 'selected' : ''; ?>>NGN - Nigerian Naira</option>
                                <option value="AUD" <?php echo $currency === 'AUD' ? 'selected' : ''; ?>>AUD - Australian Dollar</option>
                                <option value="NZD" <?php echo $currency === 'NZD' ? 'selected' : ''; ?>>NZD - New Zealand Dollar</option>
                            </optgroup>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Used throughout the application for pricing and financial reports</p>
                    </div>
                </div>
            </div>

            <!-- Regional Settings -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-globe text-blue-600 mr-3"></i> Regional Settings
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-sm font-semibold text-gray-700 mb-2">Language *</label>
                        <select
                            id="language"
                            name="language"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                        >
                            <option value="en" <?php echo $language === 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="ur" <?php echo $language === 'ur' ? 'selected' : ''; ?>>Urdu</option>
                            <option value="es" <?php echo $language === 'es' ? 'selected' : ''; ?>>Spanish</option>
                            <option value="fr" <?php echo $language === 'fr' ? 'selected' : ''; ?>>French</option>
                            <option value="ar" <?php echo $language === 'ar' ? 'selected' : ''; ?>>Arabic</option>
                            <option value="de" <?php echo $language === 'de' ? 'selected' : ''; ?>>German</option>
                        </select>
                    </div>

                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-semibold text-gray-700 mb-2">Timezone *</label>
                        <select
                            id="timezone"
                            name="timezone"
                            required
                            class="w-full select2-timezone"
                        >
                            <option value="Asia/Karachi" <?php echo $timezone === 'Asia/Karachi' ? 'selected' : ''; ?>>Asia/Karachi (PKT)</option>
                            <option value="Asia/Dubai" <?php echo $timezone === 'Asia/Dubai' ? 'selected' : ''; ?>>Asia/Dubai (GST)</option>
                            <option value="Asia/Kolkata" <?php echo $timezone === 'Asia/Kolkata' ? 'selected' : ''; ?>>Asia/Kolkata (IST)</option>
                            <option value="Europe/London" <?php echo $timezone === 'Europe/London' ? 'selected' : ''; ?>>Europe/London (GMT)</option>
                            <option value="Europe/Paris" <?php echo $timezone === 'Europe/Paris' ? 'selected' : ''; ?>>Europe/Paris (CET)</option>
                            <option value="America/New_York" <?php echo $timezone === 'America/New_York' ? 'selected' : ''; ?>>America/New_York (EST)</option>
                            <option value="America/Los_Angeles" <?php echo $timezone === 'America/Los_Angeles' ? 'selected' : ''; ?>>America/Los_Angeles (PST)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Used for all timestamps in the system</p>
                    </div>
                </div>
            </div>

            <!-- Date & Time Formats -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-3"></i> Date & Time Formats
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date Format -->
                    <div>
                        <label for="date_format" class="block text-sm font-semibold text-gray-700 mb-2">Date Format *</label>
                        <select
                            id="date_format"
                            name="date_format"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                        >
                            <option value="Y-m-d" <?php echo $date_format === 'Y-m-d' ? 'selected' : ''; ?>>2024-03-15 (YYYY-MM-DD)</option>
                            <option value="d-m-Y" <?php echo $date_format === 'd-m-Y' ? 'selected' : ''; ?>>15-03-2024 (DD-MM-YYYY)</option>
                            <option value="m/d/Y" <?php echo $date_format === 'm/d/Y' ? 'selected' : ''; ?>>03/15/2024 (MM/DD/YYYY)</option>
                            <option value="d/m/Y" <?php echo $date_format === 'd/m/Y' ? 'selected' : ''; ?>>15/03/2024 (DD/MM/YYYY)</option>
                            <option value="M d, Y" <?php echo $date_format === 'M d, Y' ? 'selected' : ''; ?>>Mar 15, 2024</option>
                            <option value="d M Y" <?php echo $date_format === 'd M Y' ? 'selected' : ''; ?>>15 Mar 2024</option>
                        </select>
                    </div>

                    <!-- Time Format -->
                    <div>
                        <label for="time_format" class="block text-sm font-semibold text-gray-700 mb-2">Time Format *</label>
                        <select
                            id="time_format"
                            name="time_format"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition"
                        >
                            <option value="H:i:s" <?php echo $time_format === 'H:i:s' ? 'selected' : ''; ?>>24-hour (14:30:45)</option>
                            <option value="h:i:s A" <?php echo $time_format === 'h:i:s A' ? 'selected' : ''; ?>>12-hour (02:30:45 PM)</option>
                            <option value="H:i" <?php echo $time_format === 'H:i' ? 'selected' : ''; ?>>24-hour Short (14:30)</option>
                            <option value="h:i A" <?php echo $time_format === 'h:i A' ? 'selected' : ''; ?>>12-hour Short (02:30 PM)</option>
                        </select>
                    </div>
                </div>

                <!-- Preview -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-2">Date Preview:</p>
                        <p class="text-lg font-semibold text-gray-900" id="date-preview"><?php echo date('Y-m-d'); ?></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-2">Time Preview:</p>
                        <p class="text-lg font-semibold text-gray-900" id="time-preview"><?php echo date('H:i:s'); ?></p>
                    </div>
                </div>
            </div>

            <!-- System Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Timezone affects:</strong> All timestamps, scheduled tasks, and report generation. Language affects the user interface. Currency is used in all pricing and financial reports.
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center space-x-4 pt-6">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition"
                >
                    <i class="fas fa-save mr-2"></i> Save General Settings
                </button>
                <a
                    href="<?php echo base_url('settings'); ?>"
                    class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 for currency dropdown
    $('.select2-currency').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select a currency...',
        width: '100%',
        allowClear: false,
        searchInputPlaceholder: 'Type to search currency code or name...',
        matcher: function(params, data) {
            // Custom matcher for better search
            var term = $.trim(params.term).toLowerCase();
            if (term === '') {
                return data;
            }

            // Search in option text and value
            if (data.text.toLowerCase().indexOf(term) > -1 ||
                data.id.toLowerCase().indexOf(term) > -1) {
                return data;
            }

            return null;
        }
    });

    // Initialize Select2 for timezone dropdown
    $('.select2-timezone').select2({
        theme: 'bootstrap-5',
        placeholder: 'Search and select a timezone...',
        width: '100%',
        allowClear: false,
        searchInputPlaceholder: 'Type to search timezone...',
        matcher: function(params, data) {
            // Custom matcher for better search
            var term = $.trim(params.term).toLowerCase();
            if (term === '') {
                return data;
            }

            // Search in option text and value
            if (data.text.toLowerCase().indexOf(term) > -1 ||
                data.id.toLowerCase().indexOf(term) > -1) {
                return data;
            }

            return null;
        }
    });

    // Style Select2 container to match Tailwind design
    $('.select2-container--bootstrap-5 .select2-selection--single').css({
        'border': '1px solid #d1d5db',
        'border-radius': '0.5rem',
        'min-height': '2.75rem',
        'padding': '0.5rem 1rem'
    });

    $('.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered').css({
        'padding': '0',
        'line-height': '1.5rem'
    });

    $('.select2-container--bootstrap-5.select2-container--focus .select2-selection--single').css({
        'border-color': '#2563eb',
        'box-shadow': '0 0 0 3px rgba(37, 99, 235, 0.1)'
    });
});

// Update date/time preview as user changes formats
document.getElementById('date_format').addEventListener('change', function() {
    // This would need PHP backend to generate proper preview
    // For now, just show the selected format
    document.getElementById('date-preview').textContent = this.options[this.selectedIndex].text;
});

document.getElementById('time_format').addEventListener('change', function() {
    document.getElementById('time-preview').textContent = this.options[this.selectedIndex].text;
});
</script>
