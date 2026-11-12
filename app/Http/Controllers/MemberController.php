<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Tampilkan daftar seluruh anggota (Dikelola oleh Admin & Pustakawan).
     */
    public function index(): View
    {
        $members = Member::orderByDesc('created_at')->get();
        return view('members.index', compact('members'));
    }

    /**
     * Tampilkan form pengajuan biodata anggota baru (Sisi Pustakawan).
     */
    public function create(): View
    {
        return view('members.create');
    }

    /**
     * Simpan pengajuan anggota baru dari Pustakawan.
     * Kode Anggota dan Status 'pending' dibuat otomatis oleh sistem di background.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi murni data biodata yang diinput oleh Pustakawan
        $validated = $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'nik_nim'       => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'alamat'        => ['nullable', 'string', 'max:255'],
            'telepon'       => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:100', 'unique:members,email'],
        ], [
            'nama.required' => 'Nama anggota wajib diisi.',
            'email.email'   => 'Format email tidak valid.',
            'email.unique'  => 'Email sudah terdaftar.',
        ]);

        // 1. Generate Kode Anggota Otomatis di Backend (AGT-YYYYMM-XXX)
        $nextId = (Member::withTrashed()->max('id') ?? 0) + 1;
        $validated['kode_anggota'] = 'AGT-' . date('Ym') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // 2. Set Status Pendaftaran Otomatis 'pending' (Menunggu ACC Admin)
        $validated['status'] = 'pending';

        // 3. Simpan Ke Database
        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Pengajuan anggota "' . $validated['nama'] . '" berhasil dikirim ke Admin dengan Kode: ' . $validated['kode_anggota']);
    }

    /**
     * Tampilkan detail biodata anggota beserta riwayat peminjamannya.
     */
    public function show(Member $member): View
    {
        $loans = $member->loans()->with('book')->orderByDesc('created_at')->get();
        return view('members.show', compact('member', 'loans'));
    }

    /**
     * Form edit biodata & kelola status keanggotaan (Sisi Admin).
     */
    public function edit(Member $member): View
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update data & status keanggotaan oleh Admin (ACC ke Aktif/Nonaktif).
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        $validated = $request->validate([
            'kode_anggota'  => ['required', 'string', 'max:20', 'unique:members,kode_anggota,' . $member->id],
            'nama'          => ['required', 'string', 'max:100'],
            'nik_nim'       => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'alamat'        => ['nullable', 'string', 'max:255'],
            'telepon'       => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:100', 'unique:members,email,' . $member->id],
            'status'        => ['required', 'in:aktif,pending,nonaktif'],
        ], [
            'kode_anggota.required' => 'Kode anggota wajib diisi.',
            'nama.required'         => 'Nama anggota wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah dipakai anggota lain.',
            'status.required'       => 'Status wajib dipilih.',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Data & status anggota "' . $member->nama . '" berhasil diperbarui.');
    }

    /**
     * Hapus data anggota dari database.
     */
    public function destroy(Member $member): RedirectResponse
    {
        if ($member->loans()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('members.index')
                ->with('error', 'Anggota "' . $member->nama . '" tidak bisa dihapus karena masih ada buku dipinjam.');
        }

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Data anggota berhasil dihapus.');
    }
} 