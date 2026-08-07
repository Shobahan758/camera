@extends('dasgboard.layouts.app')

@section('title', 'নতুন অর্ডার')

@section('content')
    @php
        $backUrl = str_starts_with($returnTo, 'fake_')
            ? route('admin.fake-orders.index', substr($returnTo, 5))
            : route('admin.orders.index', $returnTo);
    @endphp
    <div class="page-heading"><div><h1>নতুন অর্ডার করুন</h1><p>Admin panel থেকে সরাসরি একটি অর্ডার তৈরি করুন।</p></div><a href="{{ $backUrl }}" style="color:#3730a3;text-decoration:none"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a></div>
    <section class="panel" style="max-width:760px">
        <form method="POST" action="{{ route('admin.orders.store') }}">
            @csrf
            <input type="hidden" name="return_to" value="{{ $returnTo }}">
            @foreach([['name','নাম','text'],['phone','ফোন নম্বর','tel'],['email','ইমেইল','email']] as [$name,$label,$type])
                <label for="{{ $name }}" style="display:block;margin-bottom:6px;font-weight:600">{{ $label }} @if($name !== 'email')<span style="color:#dc3545">*</span>@endif</label>
                <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name) }}" @required($name !== 'email') style="width:100%;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">
                @error($name)<div style="color:#dc3545;font-size:13px">{{ $message }}</div>@enderror<div style="height:12px"></div>
            @endforeach
            <label for="address" style="display:block;margin-bottom:6px;font-weight:600">ঠিকানা *</label><textarea id="address" name="address" rows="4" required style="width:100%;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit;resize:vertical">{{ old('address') }}</textarea>@error('address')<div style="color:#dc3545;font-size:13px">{{ $message }}</div>@enderror<div style="height:12px"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px"><div><label for="quantity" style="display:block;margin-bottom:6px;font-weight:600">পরিমাণ *</label><input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required style="width:100%;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">@error('quantity')<div style="color:#dc3545;font-size:13px">{{ $message }}</div>@enderror</div><div><label for="unit_price" style="display:block;margin-bottom:6px;font-weight:600">একক মূল্য *</label><input id="unit_price" name="unit_price" type="number" min="0" value="{{ old('unit_price', $unitPrice) }}" required style="width:100%;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">@error('unit_price')<div style="color:#dc3545;font-size:13px">{{ $message }}</div>@enderror</div></div>
            <div style="height:12px"></div><label for="status" style="display:block;margin-bottom:6px;font-weight:600">স্ট্যাটাস *</label><select id="status" name="status" required style="width:100%;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">@foreach(['pending'=>'Pending','shipping'=>'Shipping','delivered'=>'Delivered','cancelled'=>'Cancelled','fake'=>'Fake'] as $value=>$label)<option value="{{ $value }}" @selected(old('status', 'pending')===$value)>{{ $label }}</option>@endforeach</select>
            <div style="display:flex;gap:10px;margin-top:22px"><button type="submit" style="padding:11px 18px;border:0;border-radius:9px;background:#4338ca;color:#fff;cursor:pointer;font:inherit;font-weight:600"><i class="fa-solid fa-plus"></i> অর্ডার তৈরি করুন</button><a href="{{ $backUrl }}" style="padding:10px 18px;border:1px solid #dbe3ee;border-radius:9px;color:#667085;text-decoration:none">বাতিল</a></div>
        </form>
    </section>
@endsection
