@extends('dasgboard.layouts.app')

@section('title', 'ড্যাশবোর্ড')

@section('content')
    <div class="page-heading">
        <div><h1>স্বাগতম, {{ auth()->user()->name }}</h1><p>ল্যান্ডিং পেজের অর্ডার ও বিক্রয়ের সর্বশেষ অবস্থা দেখুন।</p></div>
    </div>

    <section class="stat-grid" aria-label="অর্ডারের সারসংক্ষেপ">
        <article class="stat-card"><span class="stat-icon"><i class="fa-solid fa-bag-shopping"></i></span><div><small>মোট অর্ডার</small><strong>{{ number_format($orderCount) }}</strong></div></article>
        <article class="stat-card"><span class="stat-icon"><i class="fa-regular fa-clock"></i></span><div><small>পেন্ডিং অর্ডার</small><strong>{{ number_format($pendingCount) }}</strong></div></article>
        <article class="stat-card"><span class="stat-icon"><i class="fa-solid fa-bangladeshi-taka-sign"></i></span><div><small>সম্পন্ন বিক্রয়</small><strong>৳{{ number_format($totalSales) }}</strong></div></article>
    </section>

    <div class="analytics-heading"><div><h2>বিক্রয় রিপোর্ট</h2><p>শুধু Delivered অর্ডারের বিক্রিত ক্যামেরা ও আয়</p></div></div>
    <section class="stat-grid sales-grid" aria-label="সময়ভিত্তিক বিক্রয়">
        @foreach([
            ['today', 'আজকের বিক্রয়', 'fa-calendar-day'],
            ['week', 'এই সপ্তাহের বিক্রয়', 'fa-calendar-week'],
            ['month', 'এই মাসের বিক্রয়', 'fa-calendar'],
        ] as [$period, $label, $icon])
            <article class="stat-card sales-card">
                <span class="stat-icon"><i class="fa-solid {{ $icon }}"></i></span>
                <div>
                    <small>{{ $label }}</small>
                    <strong>{{ number_format($sales[$period]['quantity']) }}টি ক্যামেরা</strong>
                    <span>আয়: ৳{{ number_format($sales[$period]['revenue']) }}</span>
                </div>
            </article>
        @endforeach
    </section>

    <div class="analytics-heading"><div><h2>অর্ডার স্ট্যাটাস</h2><p>আজ ও চলতি মাসের বর্তমান অবস্থা</p></div></div>
    <section class="status-summary-grid" aria-label="অর্ডার স্ট্যাটাস রিপোর্ট">
        @foreach([
            ['today', 'pending', 'আজ পেন্ডিং', 'fa-clock', 'pending'],
            ['today', 'delivered', 'আজ সম্পন্ন', 'fa-circle-check', 'delivered'],
            ['today', 'cancelled', 'আজ বাতিল', 'fa-circle-xmark', 'cancelled'],
            ['month', 'pending', 'মাসে পেন্ডিং', 'fa-clock', 'pending'],
            ['month', 'delivered', 'মাসে সম্পন্ন', 'fa-circle-check', 'delivered'],
            ['month', 'cancelled', 'মাসে বাতিল', 'fa-circle-xmark', 'cancelled'],
        ] as [$period, $status, $label, $icon, $class])
            <article class="status-summary-card {{ $class }}">
                <span><i class="fa-solid {{ $icon }}"></i></span>
                <div><small>{{ $label }}</small><strong>{{ number_format($statusCounts[$period][$status] ?? 0) }}</strong></div>
            </article>
        @endforeach
    </section>

    <section id="orders" class="panel">
        <div class="panel-head"><h2>সাম্প্রতিক অর্ডার</h2><span>সর্বশেষ {{ $orders->count() }}টি</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>অর্ডার</th><th>কাস্টমার</th><th>ফোন</th><th>ইমেইল</th><th>ঠিকানা</th><th>পরিমাণ</th><th>মোট</th><th>স্ট্যাটাস</th><th>তারিখ</th></tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td><strong>{{ $order->name }}</strong></td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ $order->email ?: '—' }}</td>
                            <td title="{{ $order->address }}">{{ \Illuminate\Support\Str::limit($order->address, 30) }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>৳{{ number_format($order->total) }}</td>
                            <td><span class="status status-{{ $order->status }}">{{ ['pending' => 'পেন্ডিং', 'shipping' => 'শিপিং', 'delivered' => 'সম্পন্ন', 'cancelled' => 'বাতিল'][$order->status] ?? ucfirst($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty"><i class="fa-regular fa-folder-open"></i> এখনও কোনো অর্ডার পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">{{ $orders->links() }}</div>
    </section>
@endsection

@push('styles')
    <style>
        .analytics-heading{display:flex;align-items:end;justify-content:space-between;margin:8px 0 14px}.analytics-heading h2{margin:0;font-size:21px}.analytics-heading p{margin:3px 0 0;color:var(--muted)}.sales-card div>span{display:block;margin-top:2px;color:var(--primary-dark);font-weight:600}.sales-card strong{font-size:23px}.status-summary-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}.status-summary-card{display:flex;align-items:center;gap:14px;padding:18px 20px;background:#fff;border:1px solid var(--line);border-radius:15px;box-shadow:0 7px 25px #090d1a0d}.status-summary-card>span{display:grid;width:44px;height:44px;place-items:center;border-radius:12px;font-size:18px}.status-summary-card small{display:block;color:var(--muted)}.status-summary-card strong{display:block;font-size:25px}.status-summary-card.pending>span{background:#fff7dd;color:#b77900}.status-summary-card.delivered>span{background:#dcfce7;color:#15803d}.status-summary-card.cancelled>span{background:#fee2e2;color:#b91c1c}.status-delivered{background:#dcfce7;color:#15803d}.status-cancelled{background:#fee2e2;color:#b91c1c}.status-shipping{background:#e0f2fe;color:#0369a1}@media(max-width:900px){.status-summary-grid{grid-template-columns:1fr}.analytics-heading{align-items:start}}
    </style>
@endpush
