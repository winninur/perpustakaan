<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = Favorite::with(['book.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function store(Book $book): RedirectResponse
    {
        $userId = Auth::id();

        $favorite = Favorite::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('info', 'Buku "' . $book->judul . '" dihapus dari favorit.');
        }

        Favorite::create([
            'user_id' => $userId,
            'book_id' => $book->id,
        ]);

        return redirect()->back()->with('success', 'Buku "' . $book->judul . '" berhasil ditambahkan ke favorit!');
    }

    public function destroy($id): RedirectResponse
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where(function ($query) use ($id) {
                $query->where('book_id', $id)
                      ->orWhere('id', $id);
            })
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Buku berhasil dihapus dari daftar favorit.');
        }

        return redirect()->back()->with('error', 'Data favorit tidak ditemukan.');
    }
} 