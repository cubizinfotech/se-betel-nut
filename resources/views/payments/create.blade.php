@extends('layouts.backend')

@section('title', 'Record New Payment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Record New Payment</h1>
        <p class="text-muted">Add a new payment to your records</p>
    </div>
    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Payments
    </a>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('payments.store') }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Customer Selection -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label required">Customer</label>
                            <select class="form-select select2 @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id">
                                <option value="">Select a customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
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
                            <label for="payment_method" class="form-label">Pending Amount</label>
                            <input type="text" class="form-control" id="pending_amount" value="" disabled placeholder="Select a customer to view">
                        </div>
                    </div>
                    
                    <!-- Amount -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label required">Payment Method</label>
                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method">
                                <option value="">Select payment method</option>
                                <option value="bank" {{ old('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : 'selected' }}>Cash</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label required">Amount (₹)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" name="amount" value="{{ old('amount') }}" placeholder="Enter payment amount">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Payment Time -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_date" class="form-label required">Payment Date</label>
                            <input type="text" class="form-control flatpickr-date @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" placeholder="Select payment date">
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_time" class="form-label required">Payment Time</label>
                            <input type="text" class="form-control flatpickr-time @error('payment_time') is-invalid @enderror" 
                                   id="payment_time" name="payment_time" value="{{ old('payment_time', date('h:i A')) }}" placeholder="Select payment time">
                            @error('payment_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Payment Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Customer</strong> selection is required.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Amount</strong> must be a positive number.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Payment Method</strong> helps track cash vs bank payments.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Date & Time</strong> should reflect when payment was received.
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Customer Orders (if customer is selected) -->
        <div class="card shadow mt-3" id="customerOrdersCard" style="display: none;">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Orders</h6>
            </div>
            <div class="card-body overflow-auto" style="max-height: 350px;" id="customerOrdersContent">
                <!-- Orders will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load customer orders when customer is selected
    $('#customer_id').on('change', function() {
        const customerId = $(this).val();
        
        if (customerId) {
            // Show the customer orders card
            $('#customerOrdersCard').show();
            
            // Load customer orders via AJAX
            $.post(`/customer/${customerId}/orders`, function(data) {
                $('#customerOrdersContent').html(data.html);
                $("#pending_amount").val(data.pendingAmount);
            }).fail(function() {
                $('#customerOrdersContent').html('<p class="text-muted">Unable to load customer orders.</p>');
            });
        } else {
            $('#customerOrdersCard').hide();
            $("#pending_amount").val('');
        }
    });
    
    // Form validation
    $('#paymentForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate required fields
        const requiredFields = ['customer_id', 'payment_method', 'amount', 'payment_date', 'payment_time'];
        
        requiredFields.forEach(function(field) {
            if (!$('#' + field).val()) {
                if ($('#' + field).hasClass('select2')) {
                    // Target Select2 container
                    let $container = $('#'+field).next('.select2-container');
                    $container.addClass('is-invalid');

                    // Remove previous feedback if exists
                    $container.next('.invalid-feedback').remove();
                    $container.after('<div class="invalid-feedback">This field is required.</div>');
                } else {
                    $('#' + field).addClass('is-invalid');
                    $('#' + field).after('<div class="invalid-feedback">This field is required.</div>');
                }
                isValid = false;
            }
        });
        
        // Validate amount
        const amount = parseFloat($('#amount').val());
        if (isNaN(amount) || amount <= 0) {
            $('#amount').addClass('is-invalid');
            $('#amount').after('<div class="invalid-feedback">Please enter a valid positive amount.</div>');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
        }
    });
    
    // Trigger customer change if customer_id is pre-selected
    if ($('#customer_id').val()) {
        $('#customer_id').trigger('change');
    }
});
</script>
@endsection
