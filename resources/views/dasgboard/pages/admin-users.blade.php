@extends('dasgboard.layouts.app')
@section('title', 'অ্যাডমিন সেটিংস')
@push('styles')
<style>
.field{margin-bottom:14px}.field label{display:block;margin-bottom:6px;font-weight:600}.field input,.field select{width:100%;padding:11px;border:1px solid var(--line);border-radius:9px;font:inherit}.btn-save,.btn-add{padding:11px 16px;border:0;border-radius:9px;background:var(--primary);color:#fff;font:inherit;font-weight:700;cursor:pointer}.btn-add{display:inline-flex;align-items:center;gap:8px}.user-card{margin-bottom:12px;padding:16px;border:1px solid var(--line);border-radius:12px}.user-card[hidden]{display:none}.user-row{display:grid;grid-template-columns:1fr 1fr 160px 170px auto;gap:10px;align-items:end}.user-actions{display:flex;gap:8px;align-items:center;padding-bottom:14px}.user-actions .btn-save,.user-actions .delete{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:44px;white-space:nowrap}.delete{padding:9px 12px;border:0;border-radius:8px;background:#fdebec;color:#c62828;font:inherit;font-weight:700;cursor:pointer}.alert{margin-bottom:16px;padding:12px;border-radius:9px;background:#eef2ff;color:var(--primary-dark)}.modal-shell{position:fixed;inset:0;z-index:100;display:none;place-items:center;padding:20px;background:#0008}.modal-shell.show{display:grid}.modal-card{width:min(100%,520px);max-height:90vh;overflow:auto;padding:25px;background:#fff;border-radius:17px;box-shadow:0 25px 70px #0004}.modal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}.modal-head h2{margin:0}.modal-close{width:38px;height:38px;border:0;border-radius:50%;background:#eef2ff;color:var(--primary-dark);cursor:pointer;font-size:18px}.role-tabs{display:flex;gap:8px;margin:18px 0;padding:5px;border:1px solid var(--line);border-radius:12px;background:#f5f7ff}.role-tab{display:flex;flex:1;align-items:center;justify-content:center;gap:8px;padding:11px 14px;border:0;border-radius:8px;background:transparent;color:var(--muted);font:inherit;font-weight:700;cursor:pointer;transition:.2s}.role-tab:hover{color:var(--primary-dark);background:#fff}.role-tab.active{background:var(--primary);color:#fff;box-shadow:0 5px 14px #4338ca35}.role-count{display:grid;min-width:24px;height:24px;padding:0 6px;place-items:center;border-radius:20px;background:#00000012;font-size:12px}.role-tab.active .role-count{background:#ffffff30}.role-empty{display:none;padding:34px 15px;text-align:center;color:var(--muted);border:1px dashed var(--line);border-radius:12px}.role-empty.show{display:block}@media(max-width:1100px){.user-row{grid-template-columns:1fr 1fr}.user-actions{padding-bottom:14px}}@media(max-width:950px){.user-row{grid-template-columns:1fr}.page-heading{align-items:flex-start;flex-direction:column}.user-actions{padding-bottom:0}}@media(max-width:600px){.role-tabs{display:grid;grid-template-columns:1fr}.role-tab{justify-content:space-between}.user-actions{align-items:stretch;flex-direction:column}.user-actions button{width:100%}}
.access-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:9px;margin:8px 0 17px}.access-item{display:flex;align-items:center;gap:8px;padding:10px;border:1px solid var(--line);border-radius:9px;background:#f5f7ff}.access-item input{width:auto}.access-item.locked{cursor:not-allowed;opacity:.72}.access-note{display:inline-flex;align-items:center;gap:6px;margin-left:8px;color:var(--primary-dark);font-size:13px;font-weight:600}.access-toggle{display:inline-flex;grid-column:1/-1;width:max-content;align-items:center;gap:8px;padding:9px 13px;border:1px solid #c4b5fd;border-radius:8px;background:#f5f7ff;color:var(--primary-dark);font:inherit;font-weight:700;cursor:pointer}.access-toggle i{transition:transform .2s}.access-toggle[aria-expanded="true"] i{transform:rotate(180deg)}.user-access{display:none;grid-column:1/-1;padding-top:4px}.user-access.show{display:block}.user-access .access-grid{grid-template-columns:repeat(5,1fr);margin-bottom:0}@media(max-width:950px){.user-access .access-grid,.access-grid{grid-template-columns:1fr}}
</style>
@endpush
@section('content')
<div class="page-heading"><div><h1>অ্যাডমিন সেটিংস</h1><p>সুপার অ্যাডমিন, অ্যাডমিন ও ম্যানেজার পরিচালনা করুন।</p></div><button class="btn-add" id="openUserModal" type="button"><i class="fa-solid fa-user-plus"></i> অ্যাডমিন/ম্যানেজার যোগ করুন</button></div>
@if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert">{{ $errors->first() }}</div>@endif
<section class="panel"><h2>ব্যবহারকারীর তালিকা</h2>
@php
    $roleLabels = ['super_admin' => 'সুপার অ্যাডমিন', 'admin' => 'অ্যাডমিন', 'manager' => 'ম্যানেজার'];
@endphp
<div class="role-tabs" role="tablist" aria-label="ভূমিকা অনুযায়ী ব্যবহারকারী">
@foreach($roleLabels as $role => $label)
<button class="role-tab {{ $loop->first ? 'active' : '' }}" type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-role="{{ $role }}">{{ $label }} <span class="role-count">{{ $users->where('role', $role)->count() }}</span></button>
@endforeach
</div>
@foreach($users as $user)<div class="user-card" data-user-role="{{ $user->role }}" @if($user->role !== 'super_admin') hidden @endif><form id="update-user-{{ $user->id }}" class="user-row" method="POST" action="{{ route('admin.users.update',$user) }}">@csrf @method('PUT')<div class="field"><label>নাম</label><input name="name" value="{{ $user->name }}" required></div><div class="field"><label>ইমেইল</label><input name="email" type="email" value="{{ $user->email }}" required></div><div class="field"><label>ভূমিকা</label>@if($user->isSuperAdmin())<input type="hidden" name="role" value="super_admin"><select disabled aria-label="সুপার অ্যাডমিন ভূমিকা লক করা"><option>সুপার অ্যাডমিন</option></select>@else<select name="role">@foreach(['manager'=>'ম্যানেজার','admin'=>'অ্যাডমিন','super_admin'=>'সুপার অ্যাডমিন'] as $key=>$label)<option value="{{ $key }}" @selected($user->role===$key)>{{ $label }}</option>@endforeach</select>@endif</div><div class="field"><label>নতুন পাসওয়ার্ড</label><input name="password" type="password" placeholder="অপরিবর্তিত রাখুন"></div><div class="user-actions"><button class="btn-save" type="submit"><i class="fa-solid fa-pen-to-square"></i> আপডেট</button>@if(!$user->is(auth()->user()) && !$user->isSuperAdmin())<button class="delete" type="submit" form="delete-user-{{ $user->id }}"><i class="fa-solid fa-trash"></i> মুছে ফেলুন</button>@endif</div><button class="access-toggle" type="button" aria-expanded="false" aria-controls="user-access-{{ $user->id }}"><i class="fa-solid fa-chevron-down"></i><span>অ্যাক্সেস দেখুন</span></button><div class="user-access" id="user-access-{{ $user->id }}"><label><strong>অ্যাক্সেসসমূহ</strong>@if($user->isSuperAdmin())<span class="access-note"><i class="fa-solid fa-lock"></i> সব অ্যাক্সেস স্বয়ংক্রিয়</span>@endif</label><div class="access-grid">@foreach(\App\Models\User::permissionOptions() as $permission=>$permissionLabel)<label class="access-item {{ $user->isSuperAdmin() ? 'locked' : '' }}"><input type="checkbox" name="permissions[]" value="{{ $permission }}" @checked($user->isSuperAdmin() || $user->hasPermission($permission)) @disabled($user->isSuperAdmin())> {{ $permissionLabel }}</label>@endforeach</div></div></form>@if(!$user->is(auth()->user()) && !$user->isSuperAdmin())<form id="delete-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy',$user) }}" onsubmit="return confirm('ব্যবহারকারী মুছবেন?')">@csrf @method('DELETE')</form>@endif</div>@endforeach
<div class="role-empty" id="roleEmpty">এই ভূমিকায় কোনো ব্যবহারকারী নেই।</div>
</section>

<div class="modal-shell {{ $errors->hasAny(['name','email','password','role']) ? 'show' : '' }}" id="userModal" role="dialog" aria-modal="true" aria-labelledby="userModalTitle">
<div class="modal-card"><div class="modal-head"><h2 id="userModalTitle">নতুন অ্যাডমিন/ম্যানেজার</h2><button class="modal-close" id="closeUserModal" type="button" aria-label="বন্ধ করুন"><i class="fa-solid fa-xmark"></i></button></div>
<form method="POST" action="{{ route('admin.users.store') }}">@csrf
<div class="field"><label>নাম</label><input name="name" value="{{ old('name') }}" required></div><div class="field"><label>ইমেইল</label><input name="email" type="email" value="{{ old('email') }}" required></div><div class="field"><label>পাসওয়ার্ড</label><input name="password" type="password" minlength="8" required></div><div class="field"><label>ভূমিকা</label><select name="role"><option value="manager">ম্যানেজার</option><option value="admin">অ্যাডমিন</option><option value="super_admin">সুপার অ্যাডমিন</option></select></div><label><strong>কী কী অ্যাক্সেস দেবেন</strong></label><div class="access-grid">@foreach(\App\Models\User::permissionOptions() as $permission=>$permissionLabel)<label class="access-item"><input type="checkbox" name="permissions[]" value="{{ $permission }}" checked> {{ $permissionLabel }}</label>@endforeach</div><button class="btn-save"><i class="fa-solid fa-user-plus"></i> ব্যবহারকারী তৈরি করুন</button></form>
</div></div>
@endsection
@push('scripts')
<script>
const userModal=document.getElementById('userModal');
document.getElementById('openUserModal').addEventListener('click',()=>userModal.classList.add('show'));
document.getElementById('closeUserModal').addEventListener('click',()=>userModal.classList.remove('show'));
userModal.addEventListener('click',event=>{if(event.target===userModal)userModal.classList.remove('show')});
document.addEventListener('keydown',event=>{if(event.key==='Escape')userModal.classList.remove('show')});
const roleTabs=document.querySelectorAll('.role-tab');
const userCards=document.querySelectorAll('.user-card[data-user-role]');
const roleEmpty=document.getElementById('roleEmpty');

function showRole(role){
    let visibleUsers=0;
    roleTabs.forEach(tab=>{
        const isActive=tab.dataset.role===role;
        tab.classList.toggle('active',isActive);
        tab.setAttribute('aria-selected',isActive ? 'true' : 'false');
    });
    userCards.forEach(card=>{
        const isVisible=card.dataset.userRole===role;
        card.hidden=!isVisible;
        if(isVisible)visibleUsers++;
    });
    roleEmpty.classList.toggle('show',visibleUsers===0);
}

roleTabs.forEach(tab=>tab.addEventListener('click',()=>showRole(tab.dataset.role)));
showRole(document.querySelector('.role-tab.active').dataset.role);

document.querySelectorAll('.access-toggle').forEach(button=>{
    button.addEventListener('click',()=>{
        const accessPanel=document.getElementById(button.getAttribute('aria-controls'));
        const isOpen=button.getAttribute('aria-expanded')==='true';
        button.setAttribute('aria-expanded',isOpen ? 'false' : 'true');
        button.querySelector('span').textContent=isOpen ? 'অ্যাক্সেস দেখুন' : 'অ্যাক্সেস লুকান';
        accessPanel.classList.toggle('show',!isOpen);
    });
});
</script>
@endpush
