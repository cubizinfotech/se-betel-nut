@extends('layouts.backend')

@section('title', 'Customer Ledgers')

@section('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 0.875rem;
}
</style>
@endsection

@section('content')
<div class="row justify-content-between mb-3">
    <div class="col-lg-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Customer Ledgers</h1>
                <p class="text-muted">View customer account balances and transaction history</p>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <!-- Search and Filter Card -->
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" action="{{ route('ledgers.index') }}" class="row g-3" id="ledgerFilterForm" autocomplete="off">
                    <div class="col-md-4">
                        <label for="customer_id" class="form-label required"><b>Customer</b></label>
                        <select class="form-select select2" id="customer" name="customer">
                            <option value="">Select a customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ request('customer') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->first_name }} {{ $customer->last_name }}
                                    @if($customer->phone)
                                        - {{ $customer->phone }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="text" class="form-control flatpickr-date" id="date_from" name="date_from" 
                            value="{{ request('date_from') }}" placeholder="Select start date">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="text" class="form-control flatpickr-date" id="date_to" name="date_to" 
                            value="{{ request('date_to') }}" placeholder="Select end date">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search me-1"></i> Find
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('ledgers.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Load Ledgers Data -->
<div id="ledgersData"></div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load ledgers data if a customer is selected
    function loadLedgers() {
        var customerId = $('#customer').val();
        var dateFrom = $('#date_from').val();
        var dateTo = $('#date_to').val();

        if (customerId) {
            $.ajax({
                url: '{{ route("ledgers.fetch") }}',
                method: 'POST',
                data: {
                    customer: customerId,
                    date_from: dateFrom,
                    date_to: dateTo
                },
                beforeSend: function() {
                    $('#ledgersData').html('<div class="text-center my-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                success: function(response) {
                    $('#ledgersData').html(response.html);
                },
                error: function (response) {
                    console.error('Error fetching ledgers:', response.responseText);

                    let errorMsg = 'An error occurred while fetching data.';

                    try {
                        let res = JSON.parse(response.responseText);

                        if (res.message) {
                            errorMsg = res.message;
                        }

                        if (res.errors) {
                            errorMsg += '<ul>';
                            $.each(res.errors, function (field, messages) {
                                $.each(messages, function (i, msg) {
                                    errorMsg += '<li>' + msg + '</li>';
                                });
                            });
                            errorMsg += '</ul>';
                        }
                    } catch (e) {
                        console.warn("Could not parse JSON error response");
                    }

                    $('#ledgersData').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            });
        } else {
            $('#ledgersData').empty();
        }
    }

    // Initial load if customer is pre-selected
    if ($('#customer').val()) {
        loadLedgers();
    }

    // onchange event for customer select
    $('#customer').on('change', function() {
        loadLedgers();
    });

    // Load ledgers when the form is submitted
    $('#ledgerFilterForm').on('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        
        // Clear previous error states
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Validate required fields
        const requiredFields = ['customer'];
        
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
        
        if (!isValid) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
            return;
        }

        loadLedgers();
    });
});
</script>
@endsection
