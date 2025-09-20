@extends('layouts.backend')

@section('title', 'Create New Order')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Create New Order</h1>
        <p class="text-muted">Add a new order to your records</p>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Orders
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="orderForm">
                    @csrf
                    
                    <!-- Customer Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label required">Customer</label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                        @if($customer->phone)
                                            - {{ $customer->phone }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="product_name" class="form-label required">Product Name</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                   id="product_name" name="product_name" value="{{ old('product_name', 'Supari Fali') }}" required>
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Product Details -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lot_number" class="form-label">Lot Number</label>
                            <input type="text" class="form-control @error('lot_number') is-invalid @enderror" 
                                   id="lot_number" name="lot_number" value="{{ old('lot_number') }}">
                            @error('lot_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="rate" class="form-label required">Rate (₹ per kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('rate') is-invalid @enderror" 
                                   id="rate" name="rate" value="{{ old('rate') }}" required>
                            @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Quantity and Weight -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label required">Quantity (Bags)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="discounted_bag_weight" class="form-label required">Weight per Bag (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('discounted_bag_weight') is-invalid @enderror" 
                                   id="discounted_bag_weight" name="discounted_bag_weight" value="{{ old('discounted_bag_weight') }}" required>
                            @error('discounted_bag_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Total Weight (kg)</label>
                            <input type="text" class="form-control" id="total_weight" readonly>
                        </div>
                    </div>
                    
                    <!-- Charges -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="packaging_charge" class="form-label required">Packaging Charge (₹)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('packaging_charge') is-invalid @enderror" 
                                   id="packaging_charge" name="packaging_charge" value="{{ old('packaging_charge', 0) }}" required>
                            @error('packaging_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="hamali_charge" class="form-label required">Hamali Charge (₹)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('hamali_charge') is-invalid @enderror" 
                                   id="hamali_charge" name="hamali_charge" value="{{ old('hamali_charge', 0) }}" required>
                            @error('hamali_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Dates -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="order_date" class="form-label required">Order Date</label>
                            <input type="date" class="form-control datepicker @error('order_date') is-invalid @enderror" 
                                   id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label required">Due Date</label>
                            <input type="date" class="form-control datepicker @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Amount Summary -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Amount Summary</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Base Amount:</span>
                                                <span id="base_amount">₹0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Packaging:</span>
                                                <span id="packaging_display">₹0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Hamali:</span>
                                                <span id="hamali_display">₹0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Grand Total:</span>
                                        <span id="grand_total">₹0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Customer</strong> selection is required.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Rate</strong> is per kilogram of product.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Total Weight</strong> is calculated automatically.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Due Date</strong> must be after order date.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Charges</strong> are added to the base amount.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Calculate amounts when inputs change
    function calculateAmounts() {
        const quantity = parseFloat($('#quantity').val()) || 0;
        const weightPerBag = parseFloat($('#discounted_bag_weight').val()) || 0;
        const rate = parseFloat($('#rate').val()) || 0;
        const packagingCharge = parseFloat($('#packaging_charge').val()) || 0;
        const hamaliCharge = parseFloat($('#hamali_charge').val()) || 0;
        
        const totalWeight = quantity * weightPerBag;
        const baseAmount = totalWeight * rate;
        const grandTotal = baseAmount + packagingCharge + hamaliCharge;
        
        $('#total_weight').val(totalWeight.toFixed(2));
        $('#base_amount').text('₹' + baseAmount.toFixed(2));
        $('#packaging_display').text('₹' + packagingCharge.toFixed(2));
        $('#hamali_display').text('₹' + hamaliCharge.toFixed(2));
        $('#grand_total').text('₹' + grandTotal.toFixed(2));
    }
    
    // Bind calculation to input changes
    $('#quantity, #discounted_bag_weight, #rate, #packaging_charge, #hamali_charge').on('input', calculateAmounts);
    
    // Set minimum due date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    $('#due_date').attr('min', tomorrow.toISOString().split('T')[0]);
    
    // Form validation
    $('#orderForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate required fields
        const requiredFields = ['customer_id', 'product_name', 'rate', 'quantity', 'discounted_bag_weight', 'packaging_charge', 'hamali_charge', 'order_date', 'due_date'];
        
        requiredFields.forEach(function(field) {
            if (!$('#' + field).val()) {
                $('#' + field).addClass('is-invalid');
                $('#' + field).after('<div class="invalid-feedback">This field is required.</div>');
                isValid = false;
            }
        });
        
        // Validate due date is after order date
        const orderDate = new Date($('#order_date').val());
        const dueDate = new Date($('#due_date').val());
        
        if (dueDate <= orderDate) {
            $('#due_date').addClass('is-invalid');
            $('#due_date').after('<div class="invalid-feedback">Due date must be after order date.</div>');
            isValid = false;
        }
        
        // Validate numeric fields
        const numericFields = ['rate', 'quantity', 'discounted_bag_weight', 'packaging_charge', 'hamali_charge'];
        
        numericFields.forEach(function(field) {
            const value = parseFloat($('#' + field).val());
            if (isNaN(value) || value < 0) {
                $('#' + field).addClass('is-invalid');
                $('#' + field).after('<div class="invalid-feedback">Please enter a valid positive number.</div>');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
        }
    });
    
    // Initial calculation
    calculateAmounts();
});
</script>
@endsection
