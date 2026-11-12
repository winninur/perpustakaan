<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Tampilkan daftar transaksi peminjaman.
     * Admin: Melihat semua transaksi & aksi persetujuan/pengembalian.
     * User/Anggota: Melihat riwayat pengajuan & status peminjaman milik sendiri.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = ($user && $user->role === 'admin');

        if ($isAdmin) {
            $loans = Loan::with(['member', 'book', 'user'])->orderByDesc('created_at')->get();
        } else {
            $member = Member::where('email', $user->email)->first();
            $loans = $member 
                ? Loan::with(['book'])->where('member_id', $member->id)->orderByDesc('created_at')->get()
                : collect();
        }

        return view('loans.index', compact('loans', 'isAdmin'));
    }

    /**
     * Form Pengajuan / Input Peminjaman Baru.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $isAdmin = ($user && $user->role === 'admin');

        $members = Member::where('status', 'aktif')->orderBy('nama')->get();
        $books   = Book::where('stok_tersedia', '>', 0)->orderBy('judul')->get();

        $selectedBookId = $request->query('book_id');
        $today          = Carbon::today()->format('Y-m-d');
        $dueDate        = Carbon::today()->addDays(7)->format('Y-m-d');
        $nextKode       = $this->generateKodePinjam();

        return view('loans.create', compact(
            'members', 
            'books', 
            'today', 
            'dueDate', 
            'nextKode', 
            'selectedBookId',
            'isAdmin'
        ));
    }

    /**
     * Simpan peminjaman baru (Membedakan Admin vs User).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = ($user && $user->role === 'admin');

        if (!$isAdmin) {
            $member = Member::where('email', $user->email)->first();
            if (!$member || $member->status !== 'aktif') {
                return back()->with('error', 'Status keanggotaan Anda belum aktif. Silakan lengkapi profil Anda.');
            }
            $request->merge([
                'member_id'   => $member->id,
                'kode_pinjam' => $this->generateKodePinjam()
            ]);
        }

        $validated = $request->validate([
            'kode_pinjam'         => ['required', 'string', 'max:20', 'unique:loans,kode_pinjam'],
            'member_id'           => ['required', 'exists:members,id'],
            'book_id'             => ['required', 'exists:books,id'],
            'tanggal_pinjam'      => ['required', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'catatan'             => ['nullable', 'string', 'max:500'],
        ]);

        $book   = Book::findOrFail($validated['book_id']);
        $member = Member::findOrFail($validated['member_id']);

        if ($book->stok_tersedia <= 0) {
            return back()->withErrors(['book_id' => 'Stok buku "' . $book->judul . '" tidak tersedia.'])->withInput();
        }

        if ($member->status !== 'aktif') {
            return back()->withErrors(['member_id' => 'Anggota "' . $member->nama . '" berstatus nonaktif.'])->withInput();
        }

        $validated['user_id'] = Auth::id();

        if (!$isAdmin) {
            $validated['status'] = 'pending';
        } else {
            $validated['status'] = 'dipinjam';
            $book->decrement('stok_tersedia');
        }

        $validated['denda'] = 0;

        Loan::create($validated);

        $msg = !$isAdmin 
            ? 'Pengajuan peminjaman berhasil terkirim. Silakan menunggu persetujuan Admin.' 
            : 'Peminjaman "' . $validated['kode_pinjam'] . '" berhasil dibuat.';

        return redirect()->route('loans.index')->with('success', $msg);
    }

    /**
     * Persetujuan Pengajuan (Khusus Admin).
     */
    public function approve(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'pending') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman bukan dalam status pengajuan.');
        }

        if ($loan->book->stok_tersedia <= 0) {
            return redirect()->route('loans.index')->with('error', 'Stok buku tidak mencukupi.');
        }

        $loan->update([
            'status'  => 'dipinjam',
            'user_id' => Auth::id()
        ]);

        $loan->book->decrement('stok_tersedia');

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman berhasil disetujui.');
    }

    /**
     * Penolakan Pengajuan (Khusus Admin).
     */
    public function reject(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'pending') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman bukan dalam status pengajuan.');
        }

        $loan->update([
            'status'  => 'ditolak',
            'user_id' => Auth::id()
        ]);

        return redirect()->route('loans.index')->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['member', 'book', 'user']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        if (in_array($loan->status, ['dikembalikan', 'terlambat'])) {
            return redirect()->route('loans.index')->with('error', 'Peminjaman selesai tidak bisa diedit.');
        }

        $members = Member::where('status', 'aktif')->orderBy('nama')->get();
        $books   = Book::orderBy('judul')->get();

        return view('loans.edit', compact('loan', 'members', 'books'));
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'kode_pinjam'         => ['required', 'string', 'max:20', 'unique:loans,kode_pinjam,' . $loan->id],
            'member_id'           => ['required', 'exists:members,id'],
            'book_id'             => ['required', 'exists:books,id'],
            'tanggal_pinjam'      => ['required', 'date'],
            'tanggal_jatuh_tempo' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'catatan'             => ['nullable', 'string', 'max:500'],
        ]);

        if ($loan->status === 'dipinjam' && (int) $validated['book_id'] !== $loan->book_id) {
            $oldBook = Book::find($loan->book_id);
            $oldBook?->increment('stok_tersedia');

            $newBook = Book::find($validated['book_id']);
            if ($newBook && $newBook->stok_tersedia <= 0) {
                return back()->withErrors(['book_id' => 'Stok buku baru tidak tersedia.'])->withInput();
            }
            $newBook?->decrement('stok_tersedia');
        }

        $loan->update($validated);

        return redirect()->route('loans.index')->with('success', 'Data peminjaman diperbarui.');
    }

    /**
     * Pengembalian Buku & Hitung Denda (Admin).
     */
    public function returnBook(Request $request, Loan $loan): RedirectResponse
    {
        if (in_array($loan->status, ['dikembalikan', 'terlambat'])) {
            return redirect()->route('loans.index')->with('error', 'Buku sudah dikembalikan.');
        }

        $today    = Carbon::today();
        $dueDate  = Carbon::parse($loan->tanggal_jatuh_tempo)->startOfDay();
        $isLate   = $today->gt($dueDate);
        $lateDays = $isLate ? (int) $dueDate->diffInDays($today) : 0;
        $denda    = $isLate ? ($lateDays * 1000) : 0;

        $loan->update([
            'tanggal_kembali' => $today,
            'status'          => $isLate ? 'terlambat' : 'dikembalikan',
            'denda'           => $denda,
        ]);

        $loan->book->increment('stok_tersedia');

        $msg = 'Buku "' . $loan->book->judul . '" berhasil dikembalikan.';
        if ($isLate) {
            $msg .= ' Terlambat ' . $lateDays . ' hari, denda Rp ' . number_format($denda, 0, ',', '.');
        }

        return redirect()->route('loans.index')->with('success', $msg);
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        if ($loan->status === 'dipinjam') {
            $loan->book->increment('stok_tersedia');
        }

        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Data peminjaman dihapus.');
    }

    private function generateKodePinjam(): string
    {
        $prefix = 'PJ-' . date('ymd') . '-';
        $latest = Loan::where('kode_pinjam', 'like', $prefix . '%')->latest('id')->first();

        $next = 1;
        if ($latest) {
            $lastNum = (int) substr($latest->kode_pinjam, -3);
            $next    = $lastNum + 1;
        }

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
} 