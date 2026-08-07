@extends('dasgboard.layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="page-heading"><div><h1>{{ $pageTitle }}</h1><p>{{ !empty($isFakeList) ? 'ফেক হিসেবে চিহ্নিত অর্ডারগুলো যাচাই ও পরিচালনা করুন।' : 'অর্ডারগুলো যাচাই ও পরিচালনা করুন।' }}</p></div><div style="display:flex;align-items:center;gap:14px"><strong>মোট: {{ $orders->total() }}</strong><a href="{{ route('admin.orders.create', ['return_to' => !empty($isFakeList) ? 'fake_'.$filter : $filter]) }}" style="display:inline-flex;align-items:center;gap:7px;padding:10px 14px;border-radius:9px;background:#4338ca;color:#fff;text-decoration:none;font-weight:600"><i class="fa-solid fa-plus"></i> নতুন অর্ডার করুন</a></div></div>
    @if(session('success'))<div style="margin-bottom:18px;padding:12px;border-radius:9px;background:#eef2ff;color:#3730a3">{{ session('success') }}</div>@endif
    @php($returnTo = !empty($isFakeList) ? 'fake_'.$filter : $filter)
    <section class="panel">
        <div class="table-wrap"><table>
            <thead><tr><th>অর্ডার</th><th>কাস্টমার</th><th>ফোন</th><th>ইমেইল</th><th>ঠিকানা</th><th>পরিমাণ</th><th>মোট</th><th>স্ট্যাটাস</th><th>তারিখ</th><th>অ্যাকশন</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr><td>#{{ $order->id }}</td><td><strong>{{ $order->name }}</strong></td><td>{{ $order->phone }}</td><td>{{ $order->email ?: '—' }}</td><td title="{{ $order->address }}">{{ \Illuminate\Support\Str::limit($order->address, 35) }}</td><td>{{ $order->quantity }}</td><td>৳{{ number_format($order->total) }}</td><td><form method="POST" action="{{ route('admin.orders.status', $order) }}" style="display:flex;align-items:center;gap:7px">@csrf @method('PATCH')<select name="status" style="padding:7px;border:1px solid #dbe3ee;border-radius:8px"><option value="pending" @selected($order->status==='pending')>Pending</option><option value="shipping" @selected($order->status==='shipping')>Shipping</option><option value="delivered" @selected($order->status==='delivered')>Delivered</option><option value="cancelled" @selected($order->status==='cancelled')>Cancelled</option><option value="fake" @selected($order->status==='fake')>Fake</option></select><button type="submit" style="padding:7px 11px;border:0;border-radius:8px;background:#4338ca;color:#fff;cursor:pointer;font:inherit;font-weight:600"><i class="fa-solid fa-check"></i> আপডেট</button></form></td><td>{{ $order->created_at->format('d M, Y h:i A') }}</td><td><div style="display:flex;align-items:center;gap:7px"><a href="{{ route('admin.orders.edit', ['order' => $order, 'return_to' => $returnTo]) }}" style="padding:7px 10px;border-radius:7px;background:#eef2ff;color:#4338ca;text-decoration:none"><i class="fa-solid fa-pen"></i> এডিট</a><form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('এই অর্ডারটি মুছে ফেলতে চান?')">@csrf @method('DELETE')<input type="hidden" name="return_to" value="{{ $returnTo }}"><button type="submit" style="padding:8px 10px;border:0;border-radius:7px;background:#fdebec;color:#c62828;cursor:pointer;font:inherit"><i class="fa-solid fa-trash"></i> ডিলিট</button></form></div></td></tr>
                @empty
                    <tr><td colspan="11" class="empty"><i class="fa-regular fa-folder-open"></i> এই তালিকায় কোনো অর্ডার নেই।</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="pagination-wrap">{{ $orders->links() }}</div>
    </section>
@endsection
