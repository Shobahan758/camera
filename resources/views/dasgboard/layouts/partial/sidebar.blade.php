<aside id="adminSidebar" class="sidebar">
    <div class="brand"><span class="brand-icon"><i class="fa-solid fa-camera"></i></span><span>দৃশ্যপ্রো</span></div>
    <div class="nav-label">ম্যানেজমেন্ট</div>
    <nav>
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('dashboard'))
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i><span>ড্যাশবোর্ড</span></a>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('orders'))
        <details class="nav-group" {{ request()->routeIs('admin.orders.*') ? 'open' : '' }}>
            <summary class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fa-solid fa-bag-shopping"></i><span>অর্ডারসমূহ</span><i class="fa-solid fa-chevron-down submenu-arrow"></i></summary>
            <div class="submenu">
                <a class="submenu-link {{ request()->route('filter', 'all') === 'all' && request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index', 'all') }}">সব অর্ডার</a>
                <a class="submenu-link {{ request()->route('filter') === 'today' ? 'active' : '' }}" href="{{ route('admin.orders.index', 'today') }}">আজকের অর্ডার</a>
                <a class="submenu-link {{ request()->route('filter') === 'shipping' ? 'active' : '' }}" href="{{ route('admin.orders.index', 'shipping') }}">শিপিং</a>
                <a class="submenu-link {{ request()->route('filter') === 'delivered' ? 'active' : '' }}" href="{{ route('admin.orders.index', 'delivered') }}">ডেলিভারি</a>
                <a class="submenu-link {{ request()->route('filter') === 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders.index', 'cancelled') }}">বাতিল</a>
            </div>
        </details>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('fake_orders'))
        <details class="nav-group" {{ request()->routeIs('admin.fake-orders.*') ? 'open' : '' }}>
            <summary class="nav-link {{ request()->routeIs('admin.fake-orders.*') ? 'active' : '' }}"><i class="fa-solid fa-user-slash"></i><span>ফেক অর্ডার</span><i class="fa-solid fa-chevron-down submenu-arrow"></i></summary>
            <div class="submenu">
                <a class="submenu-link {{ request()->routeIs('admin.fake-orders.*') && request()->route('filter', 'all') === 'all' ? 'active' : '' }}" href="{{ route('admin.fake-orders.index', 'all') }}">সব ফেক অর্ডার</a>
                <a class="submenu-link {{ request()->routeIs('admin.fake-orders.*') && request()->route('filter') === 'today' ? 'active' : '' }}" href="{{ route('admin.fake-orders.index', 'today') }}">আজকের ফেক অর্ডার</a>
            </div>
        </details>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('incomplete_orders'))
        <details class="nav-group" {{ request()->routeIs('admin.incomplete-orders.*') ? 'open' : '' }}>
            <summary class="nav-link {{ request()->routeIs('admin.incomplete-orders.*') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i><span>অসম্পূর্ণ অর্ডার</span><i class="fa-solid fa-chevron-down submenu-arrow"></i></summary>
            <div class="submenu">
                <a class="submenu-link {{ request()->routeIs('admin.incomplete-orders.*') && request()->route('filter', 'all') === 'all' ? 'active' : '' }}" href="{{ route('admin.incomplete-orders.index', 'all') }}">সব অসম্পূর্ণ অর্ডার</a>
                <a class="submenu-link {{ request()->routeIs('admin.incomplete-orders.*') && request()->route('filter') === 'today' ? 'active' : '' }}" href="{{ route('admin.incomplete-orders.index', 'today') }}">আজকের অসম্পূর্ণ অর্ডার</a>
            </div>
        </details>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('site_settings'))
        <details class="nav-group" {{ request()->routeIs('admin.landing.*') ? 'open' : '' }}>
            <summary class="nav-link {{ request()->routeIs('admin.landing.*') ? 'active' : '' }}"><i class="fa-solid fa-sliders"></i><span>সাইট সেটিংস</span><i class="fa-solid fa-chevron-down submenu-arrow"></i></summary>
            <div class="submenu">
                <a class="submenu-link {{ request()->routeIs('admin.landing.index') ? 'active' : '' }}" href="{{ route('admin.landing.index') }}"><i class="fa-solid fa-toggle-on"></i> সব বিভাগ চালু/বন্ধ</a>
                @foreach(\App\Models\LandingSection::definitions() as $slug => $item)
                    <a class="submenu-link {{ request()->route('section') === $slug ? 'active' : '' }}" href="{{ route('admin.landing.edit', $slug) }}">{{ $item['label'] }}</a>
                @endforeach
            </div>
        </details>
        @endif
        @if(auth()->user()->isSuperAdmin())
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="fa-solid fa-user-shield"></i><span>অ্যাডমিন সেটিংস</span></a>
        @endif
    </nav>
    <div class="nav-label">অ্যাকাউন্ট</div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="nav-link logout" type="submit"><i class="fa-solid fa-right-from-bracket"></i><span>লগআউট</span></button>
    </form>
</aside>
