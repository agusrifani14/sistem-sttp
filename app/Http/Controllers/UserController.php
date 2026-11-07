<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('dashboard', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => 'required|in:admin,petugas',
            'whatsapp'  => 'required|string|max:20',
        ]);

        $cleanWa = preg_replace('/[^0-9]/', '', (string) $request->whatsapp);
        if (str_starts_with($cleanWa, '62')) {
            $cleanWa = '0' . substr($cleanWa, 2);
        }

        User::create([
            'name'      => trim($request->name),
            'email'     => trim($request->email),
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'whatsapp'  => $cleanWa,
        ]);

        return redirect()->route('dashboard')->with('success', 'User baru berhasil dibuat.');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        
        // Prevent resetting own password
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Tidak dapat mereset password sendiri.');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Password user ' . $user->name . ' berhasil direset!');
    }

    public function user()
    {
        return view('users.index');
    }
    
    // If you need to get the authenticated user
    public function getAuthUser()
    {
        return Auth::user();
    }
}