<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Smart Locker')</title>

    <!-- CSRF token for AJAX/Fetch -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    <!-- Per-page styles -->
    @yield('styles')
    @stack('styles')
</head>
<body>
    <div class="app-shell" id="appShell">

        <!-- TOP NAVBAR -->
        <header class="topbar" role="banner">
            <div class="brand">
                <a href="{{ url('/dashboard') }}" class="brand-link" aria-label="Smart Locker Home">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden>
                        <rect x="2" y="4" width="20" height="16" rx="3" fill="#FFA726"></rect>
                        <rect x="7" y="9" width="10" height="6" rx="1" fill="#FFF"></rect>
                    </svg>
                    <span>Smart Locker</span>
                </a>
            </div>

            <div class="topbar-actions">
                <button class="icon-btn mobile-toggle" id="mobileToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="sidebar">
                    <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                </button>

                <div class="search" role="search">
                    <input type="search" placeholder="Cari loker atau ID..." id="globalSearch" aria-label="Cari loker atau ID"/>
                </div>

                <div class="user" aria-hidden="false">
                    @php
                        $userName = optional(Auth::user())->name ?? 'Pengguna';
                        $avatarUrl = optional(Auth::user())->email
                            ? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(optional(Auth::user())->email))) . '?s=64&d=identicon'
                            : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=ffa726&color=fff';
                    @endphp

                    <img src="{{ $avatarUrl }}" alt="{{ $userName }}" class="avatar">
                    <div class="user-name">Hi, {{ $userName }}</div>
                </div>
            </div>
        </header>

        <!-- LAYOUT: SIDEBAR + CONTENT -->
        <div class="main" role="main">
            <aside class="sidebar" id="sidebar" role="navigation" aria-label="Sidebar navigation">
                <nav>
                    <ul>
                        <li>
                            <a href="{{ url('/dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/lockers') }}" class="{{ Request::is('lockers') ? 'active' : '' }}">
                                Lockers
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/deposit') }}" class="{{ Request::is('deposit') ? 'active' : '' }}">
                                Penitipan (Deposit)
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/login') }}">
                                Logout
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="sidebar-footer">
                    <small>v1.0 • Smart Locker</small>
                </div>
            </aside>

            <section class="content">
                <div class="page-header">
                    <h1>@yield('title')</h1>
                </div>

                <div class="page-body">
                    @yield('content')
                </div>

                <footer class="footer">
                    <small>© {{ date('Y') }} Smart Locker — Dibuat dengan ❤️</small>
                </footer>
            </section>
        </div>
    </div>

    <!-- Modal and toast placeholders (populated by JS) -->
    <div id="modalRoot" aria-hidden="true"></div>
    <div id="toastRoot" aria-live="polite" aria-atomic="true"></div>

    <!-- Main JS (defer to not block rendering) -->
    <script src="{{ asset('js/frontend.js') }}" defer></script>

    <!-- Per-page scripts -->
    @yield('scripts')
    @stack('scripts')

    <!-- Small inline script to sync mobile toggle aria-expanded after script loads -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebar = document.getElementById('sidebar');
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function () {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', String(!expanded));
                    // 'frontend.js' also toggles the visual class; keep aria in sync here as well
                    if (sidebar) sidebar.classList.toggle('open');
                });
            }
        });
    </script>
</body>
</html>