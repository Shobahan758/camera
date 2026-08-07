<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View { return view('dasgboard.pages.admin-users', ['users' => User::latest()->get()]); }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name'=>['required','string','max:100'],'email'=>['required','email','max:255','unique:users'],'password'=>['required','string','min:8'],'role'=>['required','in:super_admin,admin,manager'],'permissions'=>['nullable','array'],'permissions.*'=>['in:dashboard,orders,fake_orders,incomplete_orders,site_settings']]);
        $data['permissions'] = $data['role'] === 'super_admin' ? array_keys(User::permissionOptions()) : ($data['permissions'] ?? []);
        User::create($data);
        return back()->with('success', 'নতুন ব্যবহারকারী তৈরি হয়েছে।');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['name'=>['required','string','max:100'],'email'=>['required','email','max:255',Rule::unique('users')->ignore($user)],'password'=>['nullable','string','min:8'],'role'=>['required','in:super_admin,admin,manager'],'permissions'=>['nullable','array'],'permissions.*'=>['in:dashboard,orders,fake_orders,incomplete_orders,site_settings']]);
        if ($user->isSuperAdmin() && $data['role'] !== 'super_admin') {
            return back()->withErrors(['role' => 'সুপার অ্যাডমিনের ভূমিকা পরিবর্তন করা যাবে না।']);
        }
        if (blank($data['password'] ?? null)) unset($data['password']);
        $data['permissions'] = $data['role'] === 'super_admin' ? array_keys(User::permissionOptions()) : ($data['permissions'] ?? []);
        $user->update($data);
        return back()->with('success', 'ব্যবহারকারী আপডেট হয়েছে।');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 422, 'নিজের অ্যাকাউন্ট মুছতে পারবেন না।');
        abort_if($user->isSuperAdmin(), 422, 'সুপার অ্যাডমিন মুছতে পারবেন না।');
        $user->delete();
        return back()->with('success', 'ব্যবহারকারী মুছে ফেলা হয়েছে।');
    }
}
