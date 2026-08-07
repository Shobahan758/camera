@extends('dasgboard.layouts.app')
@section('title', $definition['label'])
@push('styles')
<style>
.edit-grid{display:grid;grid-template-columns:1fr 320px;gap:22px}.field{margin-bottom:18px}.field label{display:block;margin-bottom:7px;font-weight:600}.field input,.field textarea{width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;font:inherit}.field textarea{min-height:120px}.save,.add-review,.remove-review{padding:12px 20px;border:0;border-radius:9px;font:inherit;font-weight:600;cursor:pointer}.save,.add-review{background:var(--primary);color:#fff}.review-heading{display:flex;align-items:center;justify-content:space-between;margin:28px 0 14px}.review-heading h2{margin:0}.review-item{position:relative;margin-bottom:16px;padding:20px;border:1px solid var(--line);border-radius:12px;background:#f9fcfb}.review-item h3{margin:0 0 16px}.review-grid{display:grid;grid-template-columns:1fr 1fr 120px;gap:14px}.review-grid .review-text{grid-column:1/-1}.remove-review{position:absolute;right:16px;top:14px;padding:7px 11px;background:#fff0f0;color:var(--danger)}.alert{margin-bottom:18px;padding:12px;border-radius:9px;background:#eef2ff;color:var(--primary-dark)}.preview{max-width:100%;border-radius:10px}@media(max-width:850px){.edit-grid,.review-grid{grid-template-columns:1fr}.review-grid .review-text{grid-column:auto}}
</style>
@endpush
@section('content')
<div class="page-heading"><div><h1>{{ $definition['label'] }}</h1><p>এই বিভাগের লেখা ও ছবি পরিবর্তন করুন।</p></div></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
<form method="POST" enctype="multipart/form-data" action="{{ route('admin.landing.update', $slug) }}">@csrf @method('PUT')
<div class="edit-grid"><section class="panel">
@foreach($definition['fields'] as $key => [$label, $type])
    <div class="field">
        <label for="{{ $key }}">{{ $label }}</label>
        @if($type === 'textarea')
            <textarea id="{{ $key }}" name="{{ $key }}">{{ old($key, data_get($section->content, $key)) }}</textarea>
        @else
            <input id="{{ $key }}" name="{{ $key }}" type="{{ $type }}" value="{{ old($key, data_get($section->content, $key)) }}">
        @endif
        @error($key)<small style="color:#dc3545">{{ $message }}</small>@enderror
    </div>
@endforeach
@if($definition['dynamic_reviews'] ?? false)
    @php
        $reviewItems = old('reviews', data_get($section->content, 'reviews', []));
        $nextReviewIndex = count($reviewItems) ? max(array_map('intval', array_keys($reviewItems))) + 1 : 0;
    @endphp
    <div class="review-heading"><h2>Testimonials</h2><button class="add-review" id="addReview" type="button"><i class="fa-solid fa-plus"></i> নতুন Review</button></div>
    @error('reviews')<small style="display:block;margin-bottom:10px;color:#dc3545">{{ $message }}</small>@enderror
    <div id="reviewList">
        @foreach($reviewItems as $index => $review)
            <div class="review-item">
                <h3>Review {{ $loop->iteration }}</h3><button class="remove-review" type="button" aria-label="Review মুছুন"><i class="fa-solid fa-trash"></i></button>
                <div class="review-grid">
                    <div class="field"><label>Customer name</label><input name="reviews[{{ $index }}][name]" value="{{ $review['name'] ?? '' }}" required></div>
                    <div class="field"><label>Location</label><input name="reviews[{{ $index }}][location]" value="{{ $review['location'] ?? '' }}"></div>
                    <div class="field"><label>Avatar letter</label><input name="reviews[{{ $index }}][avatar]" value="{{ $review['avatar'] ?? '' }}"></div>
                    <div class="field"><label>Rating</label><input name="reviews[{{ $index }}][rating]" value="{{ $review['rating'] ?? '★★★★★' }}" required></div>
                    <div class="field review-text"><label>বক্তব্য</label><textarea name="reviews[{{ $index }}][text]" required>{{ $review['text'] ?? '' }}</textarea></div>
                </div>
            </div>
        @endforeach
    </div>
@endif
</section><aside class="panel"><div class="field"><label><input type="checkbox" name="is_visible" value="1" {{ $section->exists ? ($section->is_visible ? 'checked':'') : 'checked' }}> Section দেখান</label></div>
@if($definition['image']??false)<div class="field"><label>{{ $slug === 'seo' ? 'সামাজিক মাধ্যমে শেয়ারের ছবি' : 'বিভাগের ছবি' }}</label><input type="file" name="image" accept="image/*">@if(data_get($section->content,'image'))<img class="preview" src="{{ Storage::url(data_get($section->content,'image')) }}" alt="">@endif</div>@endif
@if($definition['images'] ?? false)
    @for($i = 1; $i <= $definition['images']; $i++)
        <div class="field"><label>গ্যালারির ছবি {{ $i }}</label><input type="file" name="image_{{ $i }}" accept="image/*">@if(data_get($section->content, "image_{$i}"))<img class="preview" src="{{ Storage::url(data_get($section->content, "image_{$i}")) }}" alt="গ্যালারি {{ $i }}">@endif @error("image_{$i}")<small style="color:#dc3545">{{ $message }}</small>@enderror</div>
    @endfor
@endif
<button class="save" type="submit"><i class="fa-solid fa-floppy-disk"></i> পরিবর্তন সংরক্ষণ করুন</button></aside></div></form>
@endsection
@if($definition['dynamic_reviews'] ?? false)
@push('scripts')
<script>
(() => {
    const list = document.getElementById('reviewList');
    let nextIndex = {{ $nextReviewIndex }};
    const renumber = () => list.querySelectorAll('.review-item h3').forEach((title, index) => title.textContent = `Review ${index + 1}`);
    document.getElementById('addReview').addEventListener('click', () => {
        const index = nextIndex++;
        const item = document.createElement('div');
        item.className = 'review-item';
        item.innerHTML = `<h3>Review</h3><button class="remove-review" type="button" aria-label="Review মুছুন"><i class="fa-solid fa-trash"></i></button><div class="review-grid"><div class="field"><label>Customer name</label><input name="reviews[${index}][name]" required></div><div class="field"><label>Location</label><input name="reviews[${index}][location]"></div><div class="field"><label>Avatar letter</label><input name="reviews[${index}][avatar]"></div><div class="field"><label>Rating</label><input name="reviews[${index}][rating]" value="★★★★★" required></div><div class="field review-text"><label>বক্তব্য</label><textarea name="reviews[${index}][text]" required></textarea></div></div>`;
        list.appendChild(item); renumber(); item.querySelector('input').focus();
    });
    list.addEventListener('click', event => {
        const button = event.target.closest('.remove-review');
        if (!button) return;
        if (list.children.length === 1) return alert('কমপক্ষে একটি Review রাখতে হবে।');
        button.closest('.review-item').remove(); renumber();
    });
})();
</script>
@endpush
@endif
