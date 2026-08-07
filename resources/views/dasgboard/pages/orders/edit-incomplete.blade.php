@extends('dasgboard.layouts.app')

@section('title', 'অসম্পূর্ণ অর্ডার এডিট')

@section('content')
    <div class="page-heading">
        <div><h1>অসম্পূর্ণ অর্ডার এডিট</h1><p>#{{ $order->id }} নম্বর অসম্পূর্ণ অর্ডারের তথ্য পরিবর্তন করুন।</p></div>
        <a href="{{ route('admin.incomplete-orders.index', $filter) }}" style="color:#3730a3;text-decoration:none"><i class="fa-solid fa-arrow-left"></i> তালিকায় ফিরুন</a>
    </div>

    <section class="panel" style="max-width:760px">
        <form method="POST" action="{{ route('admin.incomplete-orders.update', $order) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="list_filter" value="{{ $filter }}">
            @php($fields = [
                ['name', 'নাম', 'text', true],
                ['phone', 'ফোন নম্বর', 'tel', true],
                ['email', 'ইমেইল', 'email', false],
            ])
            @foreach($fields as [$name, $label, $type, $required])
                <label for="{{ $name }}" style="display:block;margin:0 0 6px;font-weight:600">{{ $label }} @if($required)<span style="color:#dc3545">*</span>@endif</label>
                <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $order->$name) }}" @required($required) style="width:100%;margin-bottom:4px;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">
                @error($name)<div style="margin-bottom:10px;color:#dc3545;font-size:13px">{{ $message }}</div>@else<div style="height:10px"></div>@enderror
            @endforeach

            <label for="address" style="display:block;margin:0 0 6px;font-weight:600">ঠিকানা</label>
            <textarea id="address" name="address" rows="4" style="width:100%;margin-bottom:4px;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit;resize:vertical">{{ old('address', $order->address) }}</textarea>
            @error('address')<div style="margin-bottom:10px;color:#dc3545;font-size:13px">{{ $message }}</div>@else<div style="height:10px"></div>@enderror

            <label for="quantity" style="display:block;margin:0 0 6px;font-weight:600">পরিমাণ <span style="color:#dc3545">*</span></label>
            <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', $order->quantity) }}" required style="width:100%;margin-bottom:4px;padding:11px 12px;border:1px solid #dbe3ee;border-radius:9px;font:inherit">
            @error('quantity')<div style="margin-bottom:10px;color:#dc3545;font-size:13px">{{ $message }}</div>@enderror

            <div style="display:flex;gap:10px;margin-top:20px">
                <button type="submit" style="padding:11px 18px;border:0;border-radius:9px;background:#4338ca;color:#fff;cursor:pointer;font:inherit;font-weight:600"><i class="fa-solid fa-floppy-disk"></i> আপডেট করুন</button>
                <a href="{{ route('admin.incomplete-orders.index', $filter) }}" style="padding:10px 18px;border:1px solid #dbe3ee;border-radius:9px;color:#667085;text-decoration:none">বাতিল</a>
            </div>
        </form>
    </section>
@endsection
