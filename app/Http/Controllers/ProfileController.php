<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        $member = Member::where('email', $user->email)->first();

        return view('profile.index', compact('user', 'member'));
    }

    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat'  => 'nullable|string',
            'nik_nim' => 'nullable|string|max:30',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        $user->name = $request->input('name');

        // Handling Upload Foto Profil
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        // Sync ke data member
        $member = Member::firstOrNew(['email' => $user->email]);
        
        if (!$member->exists) {
            $member->kode_anggota = 'AGT-' . date('Ymd') . rand(100, 999);
            $member->status       = 'pending';
        }

        $member->nama    = $request->input('name');
        $member->telepon = $request->input('telepon');
        $member->alamat  = $request->input('alamat');
        $member->nik_nim = $request->input('nik_nim');
        if (isset($path)) {
            $member->foto = $path;
        }
        $member->save();

        return back()->with('success', 'Profil dan foto berhasil diperbarui.');
    }
} 