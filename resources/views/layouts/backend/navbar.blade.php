<!-- Top Navigation -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-content">
            <div class="navbar-left">
                <div class="tooltip-custom" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>

            <div class="navbar-right">
                <div class="tooltip-custom" data-toggle="tooltip" data-placement="left" title="Toggle Theme">
                    <div class="theme-toggle" id="themeToggle">
                        <i class="bi bi-sun-fill" id="themeIcon"></i>
                    </div>
                </div>

                <div class="user-dropdown tooltip-custom" data-toggle="tooltip" data-placement="bottom" title="User Menu">
                    <button class="user-dropdown-btn" type="button" data-bs-toggle="dropdown">
                        <div class="user-dropdown-avatar">
                            @php
                            $name = Auth::user()->name;
                            $nameParts = explode(' ', $name);
                            $initials = '';
                            if (count($nameParts) >= 2) {
                            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                            } else {
                            $initials = strtoupper(substr($name, 0, 2));
                            }
                            @endphp
                            {{ $initials }}
                        </div>
                        <span>{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-person me-2"></i>Profile
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>