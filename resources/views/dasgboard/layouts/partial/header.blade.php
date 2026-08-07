<header class="topbar">
    <button id="menuToggle" class="menu-toggle" type="button" aria-label="মেনু খুলুন"><i class="fa-solid fa-bars"></i></button>
    <div></div>
    <div class="top-actions">
        <a class="site-link" href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> <span>ওয়েবসাইট দেখুন</span></a>
        <div class="profile">
            <span class="avatar">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
            <span class="profile-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ ['super_admin'=>'সুপার অ্যাডমিন','admin'=>'অ্যাডমিন','manager'=>'ম্যানেজার'][auth()->user()->role] ?? 'ম্যানেজার' }}</small></span>
        </div>
    </div>
</header>
