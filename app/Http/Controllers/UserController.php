<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar user.
     * Search, filter, sort, dan pagination ditangani oleh DataTables.js (client-side).
     */
    public function index(): View
    {
        $users = User::orderByDesc('created_at')->get();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', 'in:admin,pustakawan'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        // Hash password sebelum disimpan
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $rules = [
            'name'  => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'in:admin,pustakawan'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        // Password opsional saat update - hanya validasi bila diisi
        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $validated = $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email sudah dipakai user lain.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required'      => 'Role wajib dipilih.',
        ]);

        // Hash password bila diisi
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Data user "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Tidak boleh hapus diri sendiri (menggunakan Auth::id() agar tidak error intelephense)
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Cegah hapus admin terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak bisa menghapus akun Admin terakhir.');
        }

        // Cegah hapus user jika masih memiliki riwayat peminjaman di tabel loans
        if (Loan::where('user_id', $user->id)->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'User "' . $user->name . '" tidak dapat dihapus karena masih memiliki riwayat data peminjaman.');
        }

        try {
            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User "' . $user->name . '" berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak dapat dihapus karena datanya masih terhubung dengan tabel lain.');
        }
    }
} 