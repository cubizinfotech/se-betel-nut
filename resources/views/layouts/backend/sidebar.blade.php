<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="d-flex flex-column">
        <!-- Sidebar Header -->
        <div class="sidebar-header tooltip-custom" data-toggle="tooltip" data-placement="right" title="Home">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-nut-fill"></i>
                </div>
                <span class="sidebar-text sidebar-brand-text">SE Betel Nut Seller</span>
            </a>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="sidebar-nav">
            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Dashboard">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </div>

            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Customers">
                <a href="{{ route('customers.index') }}" class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span class="sidebar-text">Customers</span>
                </a>
            </div>

            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Orders">
                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-check-fill"></i>
                    <span class="sidebar-text">Orders</span>
                </a>
            </div>

            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Payments">
                <a href="{{ route('payments.index') }}" class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card-fill"></i>
                    <span class="sidebar-text">Payments</span>
                </a>
            </div>

            <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Ledgers">
                <a href="{{ route('ledgers.index') }}" class="sidebar-link {{ request()->routeIs('ledgers.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span class="sidebar-text">Ledgers</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn tooltip-custom" data-toggle="tooltip" data-placement="right" title="Logout">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span class="sidebar-text">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>