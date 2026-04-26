@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="px-4 py-6 sm:px-0 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Create Order</h1>
            <p class="text-sm text-gray-500">Create a new logistics order for a customer</p>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back to Orders
        </a>
    </div>

    <!-- Order Form -->
    <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                    <div class="text-sm text-red-800">
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name *
                                </label>
                                <input type="text" id="customer_name" name="customer_name" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Enter customer name"
                                       value="{{ old('customer_name') }}">
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address *
                                </label>
                                <input type="email" id="customer_email" name="customer_email" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Enter customer email"
                                       value="{{ old('customer_email') }}">
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number
                                </label>
                                <input type="tel" id="customer_phone" name="customer_phone"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="+60 12-3456789"
                                       value="{{ old('customer_phone') }}">
                            </div>

                            <div>
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Address *
                                </label>
                                <textarea id="customer_address" name="customer_address" rows="3" required
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Enter complete delivery address">{{ old('customer_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Logistics Details -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Logistics Details</h3>

                        <div class="space-y-4">
                            <div>
                                <label for="transportation_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Transportation Type *
                                </label>
                                <select id="transportation_type" name="transportation_type" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select transportation type</option>
                                    <option value="container_20ft" {{ old('transportation_type') == 'container_20ft' ? 'selected' : '' }}>Container (20ft) - RM2,500</option>
                                    <option value="container_40ft" {{ old('transportation_type') == 'container_40ft' ? 'selected' : '' }}>Container (40ft) - RM4,000</option>
                                    <option value="box_truck" {{ old('transportation_type') == 'box_truck' ? 'selected' : '' }}>Box Truck - RM1,200</option>
                                    <option value="curtain_sider" {{ old('transportation_type') == 'curtain_sider' ? 'selected' : '' }}>Curtain Sider - RM1,500</option>
                                    <option value="flatbed" {{ old('transportation_type') == 'flatbed' ? 'selected' : '' }}>Flatbed - RM1,800</option>
                                    <option value="refrigerated_truck" {{ old('transportation_type') == 'refrigerated_truck' ? 'selected' : '' }}>Refrigerated Truck - RM2,200</option>
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Quantity *
                                </label>
                                <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', '1') }}" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       onchange="calculateTotal()">
                            </div>

                            <div>
                                <label for="auto_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Transportation Price (RM) *
                                </label>
                                <input type="number" id="auto_price" name="auto_price" step="0.01" min="0.01" required readonly
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Auto-calculated based on transportation type"
                                       value="{{ old('auto_price') }}">
                            </div>

                            <div>
                                <label for="delivery_destination" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Destination (Port) *
                                </label>
                                <select id="delivery_destination" name="delivery_destination" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select delivery port</option>

                                    <optgroup label="Selangor">
                                        <option value="port_klang" {{ old('delivery_destination') == 'port_klang' ? 'selected' : '' }}>Port Klang - RM100</option>
                                        <option value="westports_port_klang" {{ old('delivery_destination') == 'westports_port_klang' ? 'selected' : '' }}>Westports Port Klang - RM150</option>
                                        <option value="northport_port_klang" {{ old('delivery_destination') == 'northport_port_klang' ? 'selected' : '' }}>Northport Port Klang - RM120</option>
                                    </optgroup>

                                    <optgroup label="Johor">
                                        <option value="tanjung_pelepas" {{ old('delivery_destination') == 'tanjung_pelepas' ? 'selected' : '' }}>Port of Tanjung Pelepas - RM200</option>
                                        <option value="johor_port" {{ old('delivery_destination') == 'johor_port' ? 'selected' : '' }}>Johor Port - RM180</option>
                                    </optgroup>

                                    <optgroup label="Pulau Pinang">
                                        <option value="penang_port" {{ old('delivery_destination') == 'penang_port' ? 'selected' : '' }}>Penang Port - RM160</option>
                                    </optgroup>

                                    <optgroup label="Pahang">
                                        <option value="kuantan_port" {{ old('delivery_destination') == 'kuantan_port' ? 'selected' : '' }}>Kuantan Port - RM140</option>
                                    </optgroup>

                                </select>
                            </div>

                            <div>
                                <label for="cargo_size" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cargo Size / Capacity Required *
                                </label>
                                <select id="cargo_size" name="cargo_size" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select cargo size</option>
                                    <option value="small" {{ old('cargo_size') == 'small' ? 'selected' : '' }}>Small (1–3 ton)</option>
                                    <option value="medium" {{ old('cargo_size') == 'medium' ? 'selected' : '' }}>Medium (5–10 ton)</option>
                                    <option value="large" {{ old('cargo_size') == 'large' ? 'selected' : '' }}>Large (10–20 ton)</option>
                                    <option value="fcl" {{ old('cargo_size') == 'fcl' ? 'selected' : '' }}>Full Container Load (FCL)</option>
                                </select>
                            </div>

                            <div>
                                <label for="type_of_goods" class="block text-sm font-medium text-gray-700 mb-2">
                                    Type of Goods *
                                </label>
                                <select id="type_of_goods" name="type_of_goods" required
                                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select type of goods</option>
                                    <option value="furniture" {{ old('type_of_goods') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="electronics" {{ old('type_of_goods') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="frozen_food" {{ old('type_of_goods') == 'frozen_food' ? 'selected' : '' }}>Frozen Food</option>
                                    <option value="construction_materials" {{ old('type_of_goods') == 'construction_materials' ? 'selected' : '' }}>Construction Materials</option>
                                    <option value="machinery" {{ old('type_of_goods') == 'machinery' ? 'selected' : '' }}>Machinery</option>
                                    <option value="vehicles" {{ old('type_of_goods') == 'vehicles' ? 'selected' : '' }}>Vehicles</option>
                                    <option value="textiles" {{ old('type_of_goods') == 'textiles' ? 'selected' : '' }}>Textiles</option>
                                    <option value="chemicals" {{ old('type_of_goods') == 'chemicals' ? 'selected' : '' }}>Chemicals</option>
                                    <option value="paper_products" {{ old('type_of_goods') == 'paper_products' ? 'selected' : '' }}>Paper Products</option>
                                    <option value="plastic_products" {{ old('type_of_goods') == 'plastic_products' ? 'selected' : '' }}>Plastic Products</option>
                                    <option value="metal_products" {{ old('type_of_goods') == 'metal_products' ? 'selected' : '' }}>Metal Products</option>
                                    <option value="agricultural_products" {{ old('type_of_goods') == 'agricultural_products' ? 'selected' : '' }}>Agricultural Products</option>
                                    <option value="medical_supplies" {{ old('type_of_goods') == 'medical_supplies' ? 'selected' : '' }}>Medical Supplies</option>
                                    <option value="general_cargo" {{ old('type_of_goods') == 'general_cargo' ? 'selected' : '' }}>General Cargo</option>
                                </select>
                            </div>

                            <div>
                                <label for="name_of_products" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name of Products *
                                </label>
                                <input type="text" id="name_of_products" name="name_of_products" required
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       placeholder="Enter product names or description"
                                       value="{{ old('name_of_products') }}">
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea id="notes" name="notes" rows="2"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                          placeholder="Any additional notes or instructions">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name of Products:</span>
                                    <span id="summary-products" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Quantity:</span>
                                    <span id="summary-quantity" class="font-medium">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Unit Price:</span>
                                    <span id="summary-price" class="font-medium">RM0.00</span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Transportation Type:</span>
                                            <span id="summary-lorry" class="font-medium">-</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Destination:</span>
                                            <span id="summary-destination" class="font-medium">-</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Cargo Size:</span>
                                            <span id="summary-cargo" class="font-medium">-</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Type of Goods:</span>
                                            <span id="summary-goods" class="font-medium">-</span>
                                        </div>
                                    </div>
                                    <div class="border-t mt-3 pt-3">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-semibold">Total Amount:</span>
                                            <span id="summary-total" class="text-lg font-bold text-indigo-600">RM0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>

                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-blue-900">Delivery Address</p>
                                        <p class="text-sm text-blue-800">Will be taken from customer information</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-blue-900">Contact Phone</p>
                                        <p class="text-sm text-blue-800">Will be taken from customer information</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.orders.index') }}"
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 border border-transparent rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>

    // Transportation type pricing
    const transportationPrices = {
        'container_20ft': 2500,
        'container_40ft': 4000,
        'box_truck': 1200,
        'curtain_sider': 1500,
        'flatbed': 1800,
        'refrigerated_truck': 2200
    };

    // Port pricing
    const portPrices = {
        'port_klang': 100,
        'westports_port_klang': 150,
        'northport_port_klang': 120,
        'tanjung_pelepas': 200,
        'johor_port': 180,
        'penang_port': 160,
        'kuantan_port': 140
    };

    // Auto-fill price when transportation type is selected
    document.getElementById('transportation_type').addEventListener('change', function() {
        const selectedType = this.value;
        const priceField = document.getElementById('auto_price');

        if (transportationPrices[selectedType]) {
            priceField.value = transportationPrices[selectedType];
        } else {
            priceField.value = '';
        }

        calculateTotal();
    });

    // Update total when port is selected
    document.getElementById('delivery_destination').addEventListener('change', calculateTotal);

    // Calculate total amount
    function calculateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const transportPrice = parseFloat(document.getElementById('auto_price').value) || 0;
        const transportType = document.getElementById('transportation_type');
        const destinationPort = document.getElementById('delivery_destination');
        const cargoSize = document.getElementById('cargo_size');
        const goodsType = document.getElementById('type_of_goods').value;
        const productsName = document.getElementById('name_of_products').value;

        // Calculate port fee
        const portFee = portPrices[destinationPort.value] || 0;

        // Calculate total: (transportation price + port fee) * quantity
        const basePrice = transportPrice + portFee;
        const total = quantity * basePrice;

        // Update summary
        document.getElementById('summary-products').textContent = productsName || '-';
        document.getElementById('summary-quantity').textContent = quantity;
        document.getElementById('summary-price').textContent = `RM${basePrice.toFixed(2)}`;
        document.getElementById('summary-total').textContent = `RM${total.toFixed(2)}`;

        // Update logistics summary
        document.getElementById('summary-lorry').textContent = transportType.options[transportType.selectedIndex]?.text || '-';
        document.getElementById('summary-destination').textContent = destinationPort.options[destinationPort.selectedIndex]?.text || '-';
        document.getElementById('summary-cargo').textContent = cargoSize.options[cargoSize.selectedIndex]?.text || '-';
        document.getElementById('summary-goods').textContent = goodsType || '-';
    }

    // Add event listeners for logistics fields to update summary
    document.getElementById('transportation_type').addEventListener('change', calculateTotal);
    document.getElementById('cargo_size').addEventListener('change', calculateTotal);
    document.getElementById('name_of_products').addEventListener('input', calculateTotal);
    document.getElementById('type_of_goods').addEventListener('change', function() {
        calculateTotal();

        const selectedGoods = this.value;
        const transportTypeField = document.getElementById('transportation_type');

        // Suggest transportation type based on selected goods
        if (selectedGoods === 'frozen_food') {
            transportTypeField.value = 'refrigerated_truck';
        } else if (selectedGoods === 'machinery' || selectedGoods === 'construction_materials') {
            transportTypeField.value = 'flatbed';
        } else if (selectedGoods === 'furniture' || selectedGoods === 'textiles') {
            transportTypeField.value = 'box_truck';
        } else if (selectedGoods === 'vehicles' || selectedGoods === 'metal_products') {
            transportTypeField.value = 'curtain_sider';
        }

        // Trigger price update when suggestion is made
        const event = new Event('change', { bubbles: true });
        transportTypeField.dispatchEvent(event);
    });

    // Initialize calculation on page load
    calculateTotal();

    // Restore calculated values from old input if validation failed
    @if(old('auto_price'))
        document.getElementById('auto_price').value = '{{ old('auto_price') }}';
    @endif

    // Trigger calculation after restoring values
    calculateTotal();
</script>
@endsection
