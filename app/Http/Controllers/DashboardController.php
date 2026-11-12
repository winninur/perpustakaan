<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Halaman utama dashboard - menampilkan ringkasan
     */
    public function index(): View
    {
        // Hitung total denda dari seluruh transaksi
        $totalDenda = Loan::sum('denda');

        $stats = [
            'total_buku'        => Book::count(),
            'total_kategori'    => Category::count(),
            'total_anggota'     => Member::count(),
            'total_peminjaman'  => Loan::count(),
            'dipinjam'          => Loan::where('status', 'dipinjam')->count(),
            'dikembalikan'      => Loan::where('status', 'dikembalikan')->count(),
            'terlambat'         => Loan::where('status', 'terlambat')->count(),
            'total_user'        => User::count(),
            'total_denda'       => $totalDenda,
        ];

        // Ambil peminjaman terbaru untuk ditampilkan di dashboard
        $recentLoans = Loan::with(['member', 'book'])
            ->latest()
            ->take(5)
            ->get();

        // Buku dengan stok terbanyak
        $popularBooks = Book::with('category')
            ->orderByDesc('jumlah')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentLoans', 'popularBooks', 'totalDenda'));
    }
}
