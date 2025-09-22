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
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="customer_id" class="form-label required">Customer</label>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-2 tooltip-custom"
                                    data-toggle="tooltip" data-placement="top" title="Add New Customer"
                                    data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                    +
                                </button>
                            </div>
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

                        <div class="col-md-4 mb-3">
                            <label for="rate" class="form-label required">Rate (₹ per kg)</label>
                            <input type="text" class="form-control decimal-input @error('rate') is-invalid @enderror"
                                   id="rate" name="rate" value="{{ old('rate') }}" placeholder="0.00"
                                   oninput="formatDecimal(this); calculateAllRows();">
                            @error('rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="discounted_bag_weight" class="form-label required">Disc. Weight per Bag (kg)</label>
                            <input type="text" class="form-control decimal-input @error('discounted_bag_weight') is-invalid @enderror"
                                   id="discounted_bag_weight" name="discounted_bag_weight" value="{{ old('discounted_bag_weight') }}"
                                   oninput="formatDecimal(this); calculateAllRows();" placeholder="0.00">
                            @error('discounted_bag_weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="lot_number" class="form-label">Lot Number</label>
                            <input type="text" class="form-control @error('lot_number') is-invalid @enderror"
                                   id="lot_number" name="lot_number" value="{{ old('lot_number') }}" placeholder="Enter lot number">
                            @error('lot_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="order_date" class="form-label required">Order Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-date @error('order_date') is-invalid @enderror"
                                       id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d h:i A')) }}"
                                       placeholder="Select date & time">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                            </div>
                            @error('order_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control flatpickr-date @error('due_date') is-invalid @enderror"
                                       id="due_date" name="due_date" value="{{ old('due_date') }}" placeholder="Select date & time">
                                <span class="input-group-text">
                                    <i class="bi bi-calendar-event"></i>
                                </span>
                            </div>
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Charges -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                   id="product_name" name="product_name" value="{{ old('product_name', 'Supari Fali') }}" placeholder="Product name">
                            @error('product_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="packaging_charge" class="form-label">Packaging Charge (₹)</label>
                            <input type="text" class="form-control decimal-input @error('packaging_charge') is-invalid @enderror"
                                   id="packaging_charge" name="packaging_charge" value="{{ old('packaging_charge') }}" placeholder="0.00"
                                   oninput="formatDecimal(this); calculateAllRows();">
                            @error('packaging_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="hamali_charge" class="form-label">Hamali Charge (₹)</label>
                            <input type="text" class="form-control decimal-input @error('hamali_charge') is-invalid @enderror"
                                   id="hamali_charge" name="hamali_charge" value="{{ old('hamali_charge') }}" placeholder="0.00"
                                   oninput="formatDecimal(this); calculateAllRows();">
                            @error('hamali_charge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Particulars -->
                    <div class="row mb-3">
                        <h5>Particulars</h5>
                        <hr>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered particulars-table" id="particularsTable">
                                    <thead class="table-header">
                                        <tr>
                                            <th width="10%">Bag No.</th>
                                            <th width="20%">Bag Weight (kg)</th>
                                            <th width="20%">Bag Dis. Weight (kg)</th>
                                            <th width="10%">Rate (₹)</th>
                                            <th width="20%">Final Weight (kg)</th>
                                            <th width="20%">Amount (₹)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="particular-row">
                                            <td class="text-center">1</td>
                                            <td>
                                                <input type="text" class="form-control text-center decimal-input bag-weight"
                                                    name="per_bag_weight[]" value="" placeholder="0.00"
                                                    oninput="formatDecimal(this); calculateRow(this);"
                                                    onkeydown="handleParticularsKeydown(event, this)">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control text-center bag-dis-weight"
                                                    name="bag_dis_weight[]" value="" readonly>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control text-center bag-rate"
                                                    name="bag_rate[]" value="" readonly>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control text-center final-weight"
                                                    name="final_weight[]" value="" readonly>
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control text-center amount"
                                                    name="amount[]" value="" readonly>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-header">
                                            <td class="text-center">
                                                <input type="hidden" id="totalBags" name="quantity" value="0">
                                                <strong>Total</strong>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <input type="text" class="form-control text-center"
                                                        id="totalWeight" name="total_weight" value="0.00" readonly>
                                                    <span class="ms-1">kg</span>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span>₹</span>
                                                    <input type="text" class="form-control text-center ms-1"
                                                        id="totalAmount" name="total_amount" value="0.00" readonly>
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Summary -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Amount Summary</h6>
                                    <div class="row">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Base Amount:</span>
                                                <span id="baseAmountDisplay">₹0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Packaging:</span>
                                                <span id="packagingDisplay">₹0.00</span>
                                            </div>
                                        </div>
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Hamali:</span>
                                                <span id="hamaliDisplay">₹0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Grand Total:</span>
                                        <input type="hidden" name="grand_amount" id="grandAmount" value="0.00">
                                        <span id="grandTotalDisplay">₹0.00</span>
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
                    <li class="mb-2">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Press <strong>Enter</strong> in particulars to move to next row.
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCustomerForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label required">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    $(document).ready(function () {
        // Handle Add Customer form submit
        $('#addCustomerForm').submit(function (e) {
            e.preventDefault();

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

            if (!isValid) {
                e.preventDefault();
                showToast('Please fix the errors below.', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('customers.store') }}", // your customer store route
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    // Close modal
                    $('#addCustomerModal').modal('hide');

                    // Reset form
                    $('#addCustomerForm')[0].reset();

                    // Add new customer to dropdown
                    let newOption = new Option(response.first_name + ' ' + (response.last_name ?? ''), response.id, true, true);
                    $('#customer_id').append(newOption).trigger('change');

                    // Optional: toast success message
                    toastr.success("Customer added successfully!");
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let message = "Error adding customer.";
                    if (errors) {
                        message = Object.values(errors).map(err => err.join('<br>')).join('<br>');
                    }
                    toastr.error(message);
                }
            });
        });
    });

    // Format decimal
    function formatDecimal(input) {
        let value = input.value.replace(/[^\d.]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) value = parts[0] + '.' + parts[1];
        if (parts[1]?.length > 2) value = parts[0] + '.' + parts[1].substring(0, 2);
        input.value = value;
    }

    // Calculate all rows individually
    function calculateAllRows() {
        const rows = document.querySelectorAll('#particularsTable tbody tr');
        rows.forEach(row => {
            calculateRow(row.querySelector('.bag-weight'));
        });

        // Ensure at least one empty row exists at bottom
        const lastRow = rows[rows.length - 1];
        const lastWeight = parseFloat(lastRow.querySelector('.bag-weight').value) || 0;
        if (lastWeight > 0) {
            addNewRow();
        }
    }

    // Calculate a single row
    function calculateRow(input) {
        const row = input.closest('tr');
        const bagWeight = parseFloat(row.querySelector('.bag-weight').value) || 0;
        const discountedWeight = parseFloat(document.getElementById('discounted_bag_weight').value) || 0;
        const rate = parseFloat(document.getElementById('rate').value) || 0;

        if (bagWeight <= 0) {
            row.querySelector('.bag-dis-weight').value = '';
            row.querySelector('.bag-rate').value = '';
            row.querySelector('.final-weight').value = '';
            row.querySelector('.amount').value = '';
            calculateTotals();
            return;
        }

        const finalWeight = bagWeight - discountedWeight;

        row.querySelector('.bag-dis-weight').value = discountedWeight.toFixed(2);
        row.querySelector('.bag-rate').value = rate.toFixed(2);
        row.querySelector('.final-weight').value = finalWeight.toFixed(2);
        row.querySelector('.amount').value = (finalWeight * rate).toFixed(2);

        calculateTotals();
    }

    // Update totals in footer and summary
    function calculateTotals() {
        let totalBags = 0, totalWeight = 0, totalAmount = 0;

        document.querySelectorAll('#particularsTable tbody tr').forEach(row => {
            const bagWeight = parseFloat(row.querySelector('.bag-weight').value) || 0;
            if (bagWeight > 0) {
                totalBags++;
                totalWeight += parseFloat(row.querySelector('.final-weight').value) || 0;
                totalAmount += parseFloat(row.querySelector('.amount').value) || 0;
            }
        });

        document.getElementById('totalBags').value = totalBags;
        document.getElementById('totalWeight').value = totalWeight.toFixed(2);
        document.getElementById('totalAmount').value = totalAmount.toFixed(2);

        const packagingCharge = parseFloat(document.getElementById('packaging_charge').value) || 0;
        const hamaliCharge = parseFloat(document.getElementById('hamali_charge').value) || 0;

        document.getElementById('baseAmountDisplay').textContent = '₹' + totalAmount.toFixed(2);
        document.getElementById('packagingDisplay').textContent = '₹' + packagingCharge.toFixed(2);
        document.getElementById('hamaliDisplay').textContent = '₹' + hamaliCharge.toFixed(2);

        const grandTotal = totalAmount + packagingCharge + hamaliCharge;
        document.getElementById('grandAmount').value = grandTotal.toFixed(2);
        document.getElementById('grandTotalDisplay').textContent = '₹' + grandTotal.toFixed(2);
    }

    // Enter key navigation
    function handleParticularsKeydown(event, input) {
        if (event.key === 'Enter') {
            event.preventDefault();
            calculateRow(input);

            let row = input.closest('tr');
            let nextRow = row.nextElementSibling;

            if (!nextRow || nextRow.tagName === 'TFOOT') {
                addNewRow();
                nextRow = row.nextElementSibling;
            }

            const nextInput = nextRow.querySelector('.bag-weight');
            if (nextInput) nextInput.focus();
        }
    }

    // Add new empty row
    function addNewRow() {
        const tbody = document.querySelector('#particularsTable tbody');
        const rowCount = tbody.querySelectorAll('tr').length;
        const newRow = document.createElement('tr');
        newRow.className = 'particular-row';
        newRow.innerHTML = `
            <td class="text-center">${rowCount + 1}</td>
            <td>
                <input type="text" class="form-control text-center decimal-input bag-weight"
                    name="per_bag_weight[]" placeholder="0.00"
                    oninput="formatDecimal(this); calculateRow(this);"
                    onkeydown="handleParticularsKeydown(event, this)">
            </td>
            <td class="text-center"><input type="text" class="form-control text-center bag-dis-weight" readonly></td>
            <td class="text-center"><input type="text" class="form-control text-center bag-rate" readonly></td>
            <td class="text-center"><input type="text" class="form-control text-center final-weight" readonly></td>
            <td class="text-center"><input type="text" class="form-control text-center amount" readonly></td>
        `;
        tbody.appendChild(newRow);
    }

    // Form validation
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous error states
        document.querySelectorAll('.form-control').forEach(input => {
            input.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });

        // Validate required fields
        const requiredFields = ['customer_id', 'rate', 'discounted_bag_weight', 'order_date'];

        requiredFields.forEach(field => {
            const element = document.getElementById(field);
            if (!element.value) {
                element.classList.add('is-invalid');

                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'This field is required.';

                element.parentNode.appendChild(feedback);
                isValid = false;
            }
        });

        // Validate numeric fields
        const numericFields = ['rate', 'discounted_bag_weight'];

        numericFields.forEach(field => {
            const element = document.getElementById(field);
            const value = parseFloat(element.value);

            if (isNaN(value) || value < 0) {
                element.classList.add('is-invalid');

                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Please enter a valid positive number.';

                element.parentNode.appendChild(feedback);
                isValid = false;
            }
        });

        // Validate at least one bag has weight
        let hasValidBags = false;
        document.querySelectorAll('.bag-weight').forEach(input => {
            if (parseFloat(input.value) > 0) {
                hasValidBags = true;
            }
        });

        if (!hasValidBags) {
            // Show error message
            const firstBagInput = document.querySelector('.bag-weight');
            firstBagInput.classList.add('is-invalid');

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'At least one bag must have weight.';

            firstBagInput.parentNode.appendChild(feedback);
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
@endsection
