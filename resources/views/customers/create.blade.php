@extends('layouts.backend')

@section('title', 'Add New Customer')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0 text-gray-800">Add New Customer</h1>
        <p class="text-muted">Create a new customer record</p>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Customers
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST" id="customerForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label required">First Name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Enter last name">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Enter customer's full address">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Create Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>First Name</strong> is required for all customers.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Email</strong> must be unique if provided.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Phone</strong> helps in quick customer identification.
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        <strong>Address</strong> is useful for delivery purposes.
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
    // Form validation
    $('#customerForm').on('submit', function(e) {
        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate first name
        if (!$('#first_name').val().trim()) {
            $('#first_name').addClass('is-invalid');
            $('#first_name').after('<div class="invalid-feedback">First name is required.</div>');
            isValid = false;
        }
        
        // Validate email format if provided
        const email = $('#email').val().trim();
        if (email && !isValidEmail(email)) {
            $('#email').addClass('is-invalid');
            $('#email').after('<div class="invalid-feedback">Please enter a valid email address.</div>');
            isValid = false;
        }
        
        // Validate phone format if provided
        const phone = $('#phone').val().trim();
        if (phone && !isValidPhone(phone)) {
            $('#phone').addClass('is-invalid');
            $('#phone').after('<div class="invalid-feedback">Please enter a valid phone number.</div>');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
        }
    });
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }
});
</script>
@endsection
