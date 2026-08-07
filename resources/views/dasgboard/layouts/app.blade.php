<!doctype html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | দৃশ্যপ্রো</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root{--primary:#4338ca;--primary-dark:#3730a3;--accent:#0ea5e9;--ink:#090d1a;--muted:#667085;--line:#e2e8f0;--soft:#eef2ff;--white:#fff;--danger:#dc3545;--sidebar:258px}
        *{box-sizing:border-box}body{margin:0;background:linear-gradient(145deg,#f7f8fc,#eef2ff);color:var(--ink);font-family:'Hind Siliguri',sans-serif}.admin-shell{min-height:100vh}.sidebar{position:fixed;inset:0 auto 0 0;width:var(--sidebar);padding:24px 18px;background:linear-gradient(180deg,#11163b,#070b16);color:#fff;z-index:30}.brand{display:flex;align-items:center;gap:12px;padding:0 10px 25px;font-size:25px;font-weight:700}.brand-icon{display:grid;width:42px;height:42px;place-items:center;border-radius:13px;background:linear-gradient(135deg,var(--primary),var(--accent));box-shadow:0 8px 20px #4338ca50}.nav-label{margin:18px 12px 8px;color:#94a3b8;font-size:12px;letter-spacing:.08em;text-transform:uppercase}.nav-link{display:flex;align-items:center;gap:12px;margin:5px 0;padding:12px 14px;border-radius:10px;color:#cbd5e1;text-decoration:none;transition:.2s}.nav-link:hover,.nav-link.active{background:#ffffff18;color:#fff}.nav-link.active{box-shadow:inset 3px 0 var(--accent)}.nav-link i{width:19px;text-align:center}.admin-main{margin-left:var(--sidebar);min-height:100vh}.topbar{position:sticky;top:0;z-index:20;display:flex;align-items:center;justify-content:space-between;height:74px;padding:0 32px;background:#ffffffed;border-bottom:1px solid var(--line);backdrop-filter:blur(10px)}.menu-toggle{display:none;border:0;background:none;color:var(--ink);font-size:22px}.top-actions,.profile{display:flex;align-items:center;gap:14px}.site-link{padding:9px 14px;border:1px solid #c4b5fd;border-radius:9px;color:var(--primary-dark);text-decoration:none}.avatar{display:grid;width:39px;height:39px;place-items:center;border-radius:50%;background:linear-gradient(135deg,#ede9fe,#cffafe);color:var(--primary-dark);font-weight:700}.profile-copy{line-height:1.15}.profile-copy small{display:block;color:var(--muted)}.content{padding:30px 32px 50px}.page-heading{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:24px}.page-heading h1{margin:0;font-size:30px}.page-heading p{margin:5px 0 0;color:var(--muted)}.stat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:24px}.stat-card{display:flex;align-items:center;gap:17px;padding:22px;background:var(--white);border:1px solid var(--line);border-radius:15px;box-shadow:0 7px 25px #090d1a10}.stat-icon{display:grid;width:52px;height:52px;place-items:center;border-radius:14px;background:linear-gradient(135deg,#ede9fe,#cffafe);color:var(--primary);font-size:21px}.stat-card:nth-child(2) .stat-icon{background:#ecfeff;color:#0891b2}.stat-card:nth-child(3) .stat-icon{background:#eef2ff;color:#4338ca}.stat-card small{display:block;color:var(--muted)}.stat-card strong{display:block;margin-top:3px;font-size:27px}.panel{padding:22px;background:var(--white);border:1px solid var(--line);border-radius:15px;box-shadow:0 7px 25px #090d1a10}.panel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:15px}.panel-head h2{margin:0;font-size:21px}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;white-space:nowrap}th,td{padding:13px 12px;border-bottom:1px solid #edf2f7;text-align:left}th{color:var(--muted);font-size:13px;font-weight:600}td{font-size:15px}.status{display:inline-block;padding:5px 10px;border-radius:20px;background:#eef2ff;color:#3730a3;font-size:12px}.empty{text-align:center!important;color:var(--muted);padding:38px!important}.pagination-wrap{margin-top:18px}.admin-footer{padding:20px 32px;color:var(--muted);font-size:14px;text-align:center}.logout{border:0;background:none;color:var(--danger);cursor:pointer;font:inherit}.overlay{display:none}
        .nav-group summary{cursor:pointer;list-style:none}.nav-group summary::-webkit-details-marker{display:none}.submenu-arrow{margin-left:auto;transition:.2s}.nav-group[open] .submenu-arrow{transform:rotate(180deg)}.submenu{margin:4px 0 10px 23px;padding-left:14px;border-left:1px solid #ffffff2b}.submenu-link{display:block;padding:7px 10px;border-radius:7px;color:#cbd5e1;text-decoration:none;font-size:14px}.submenu-link:hover,.submenu-link.active{background:#ffffff12;color:#fff}.sidebar{overflow-y:auto}
        @media(max-width:900px){.sidebar{transform:translateX(-100%);transition:.25s}.sidebar.open{transform:none}.admin-main{margin-left:0}.menu-toggle{display:block}.overlay.show{display:block;position:fixed;inset:0;background:#0007;z-index:25}.stat-grid{grid-template-columns:1fr}.content,.topbar{padding-left:18px;padding-right:18px}.profile-copy,.site-link span{display:none}}
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-shell">
        @include('dasgboard.layouts.partial.sidebar')
        <div id="sidebarOverlay" class="overlay"></div>
        <div class="admin-main">
            @include('dasgboard.layouts.partial.header')
            <main class="content">@yield('content')</main>
            @include('dasgboard.layouts.partial.footer')
        </div>
    </div>
    <script>
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        document.getElementById('menuToggle')?.addEventListener('click', () => { sidebar.classList.toggle('open'); overlay.classList.toggle('show'); });
        overlay?.addEventListener('click', () => { sidebar.classList.remove('open'); overlay.classList.remove('show'); });
    </script>
    @stack('scripts')
</body>
</html>
