@extends('layouts.backend')

@section('title', 'Customers')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Customers</h1>
            <p class="text-muted">Manage your customer database</p>
        </div>
        <div class="">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New Customer
            </a>
        </div>
    </div>

<!-- Search and Filter Card -->
<div class="card shadow mb-4 position-relative">
    <div class="card-header d-flex justify-content-center align-items-center d-md-none position-relative">
        <span class="fw-semibold">Filters</span>
        <button class="filter-toggle filter-icon" type="button" data-bs-toggle="collapse" data-bs-target="#customersFilter" aria-expanded="false" aria-controls="customersFilter" title="Toggle Filters">
            <i class="bi bi-funnel"></i>
        </button>
    </div>
    <div id="customersFilter" class="card-body collapse d-md-block">
        <form method="GET" action="{{ route('customers.index') }}" class="row g-3">
            <div class="col-md-10">
                <label for="search" class="form-label">Search Customers</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Search by name, email, or phone...">
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </div>
            <div class="col-6 col-md-1">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <div class="d-grid">
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Customers Table -->
<div class="card shadow">
    <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Customer List</h6>
        <div class="d-flex gap-2">
            <!-- Export Excel Button -->
            <a href="{{ route('customers.export', ['type' => 'excel'] + request()->all()) }}"
                class="btn btn-sm btn-outline-success tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Customers to Excel"
            >
                <i class="bi bi-file-earmark-excel me-1"></i> Excel
            </a>

            <!-- Export PDF Button -->
            <a href="{{ route('customers.export', ['type' => 'pdf'] + request()->all()) }}"
                class="btn btn-sm btn-outline-danger tooltip-custom" 
                data-toggle="tooltip" 
                data-placement="top" 
                title="Export Customers to PDF"
            >
                <i class="bi bi-file-earmark-pdf me-1"></i> PDF
            </a>
        </div>
    </div>

    <div class="card-body">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Created</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @php
                                            $name = $customer->first_name . ' ' . $customer->last_name;
                                            $nameParts = explode(' ', trim($name));
                                            $initials = '';
                                            if (count($nameParts) >= 2) {
                                                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                                            } else {
                                                $initials = strtoupper(substr($customer->first_name, 0, 2));
                                            }
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                        {{ $customer->email }}
                                    </a>
                                @else
                                    <span class="text-muted">No email</span>
                                @endif
                            </td>
                            <td>
                                @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                        {{ $customer->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">No phone</span>
                                @endif
                            </td>
                            <td>
                                @if($customer->address)
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                          title="{{ $customer->address }}">
                                        {{ $customer->address }}
                                    </span>
                                @else
                                    <span class="text-muted">No address</span>
                                @endif
                            </td>
                            <td>{{ $customer->created_at->format('M d, Y h:i A') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="View">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                           class="btn btn-sm btn-outline-info btn-action">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Edit">
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="btn btn-sm btn-outline-warning btn-action">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                    <div class="tooltip-custom" data-toggle="tooltip" data-placement="top" title="Delete">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger btn-action"
                                                onclick="confirmDelete(`{{ route('customers.destroy', $customer) }}`, 'Delete Customer', 'Are you sure you want to delete this customer? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                </div>
                <div class="">
                    {{ $customers->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">No customers found</h4>
                <p class="text-muted">Get started by adding your first customer.</p>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add First Customer
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
</style>
@endsection
