<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Tampilkan daftar katalog buku di halaman index (Sisi Admin / Pustakawan / User).
     * Mendukung filter pencarian kata kunci dan filter kategori secara presisi.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $selectedCategory = $request->query('category_id');

        // Ambil daftar kategori untuk tombol filter
        $categories = Category::orderBy('nama_kategori')->get();

        // Query menyaring buku hanya yang sesuai dengan kategori atau pencarian
        $books = Book::with('category')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('penulis', 'like', "%{$search}%")
                      ->orWhere('isbn', 'like', "%{$search}%")
                      ->orWhere('kode_buku', 'like', "%{$search}%");
                });
            })
            ->when($selectedCategory, function ($query, $selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('books.index', compact('books', 'categories', 'search', 'selectedCategory'));
    }

    /**
     * Tampilkan katalog buku jika kamu menggunakan route khusus /books/catalog.
     */
    public function catalog(Request $request): View
    {
        return $this->index($request);
    }

    /**
     * Form tambah buku baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('nama_kategori')->get();
        return view('books.create', compact('categories'));
    }

    /**
     * Simpan buku baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_buku'    => ['required', 'string', 'max:20', 'unique:books,kode_buku'],
            'judul'        => ['required', 'string', 'max:150'],
            'penulis'      => ['required', 'string', 'max:100'],
            'penerbit'     => ['nullable', 'string', 'max:100'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1500', 'max:' . (int) date('Y')],
            'isbn'         => ['nullable', 'string', 'max:20'],
            'category_id'  => ['required', 'exists:categories,id'],
            'jumlah'       => ['required', 'integer', 'min:0'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
            'cover'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ], [
            'kode_buku.required'   => 'Kode buku wajib diisi.',
            'kode_buku.unique'     => 'Kode buku sudah digunakan.',
            'judul.required'       => 'Judul buku wajib diisi.',
            'penulis.required'     => 'Penulis wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'jumlah.required'      => 'Jumlah buku wajib diisi.',
            'jumlah.min'           => 'Jumlah minimal 0.',
            'tahun_terbit.max'     => 'Tahun terbit tidak boleh lebih dari tahun ini.',
            'cover.image'          => 'File sampul harus berupa gambar.',
            'cover.mimes'          => 'Format gambar harus JPG, JPEG, atau PNG.',
            'cover.max'            => 'Ukuran gambar maksimal 2 MB.',
        ]);

        // Simpan file gambar sampul jika di-upload
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $path;
        }

        // Stok tersedia awal sama dengan jumlah total buku
        $validated['stok_tersedia'] = $validated['jumlah'];

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku "' . $validated['judul'] . '" berhasil ditambahkan.');
    }

    /**
     * Detail informasi buku.
     */
    public function show(Book $book): View
    {
        $book->load('category', 'loans.member');
        return view('books.show', compact('book'));
    }

    /**
     * Form edit buku.
     */
    public function edit(Book $book): View
    {
        $categories = Category::orderBy('nama_kategori')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update data buku.
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'kode_buku'    => ['required', 'string', 'max:20', 'unique:books,kode_buku,' . $book->id],
            'judul'        => ['required', 'string', 'max:150'],
            'penulis'      => ['required', 'string', 'max:100'],
            'penerbit'     => ['nullable', 'string', 'max:100'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1500', 'max:' . (int) date('Y')],
            'isbn'         => ['nullable', 'string', 'max:20'],
            'category_id'  => ['required', 'exists:categories,id'],
            'jumlah'       => ['required', 'integer', 'min:0'],
            'deskripsi'    => ['nullable', 'string', 'max:1000'],
            'cover'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ], [
            'kode_buku.required'   => 'Kode buku wajib diisi.',
            'kode_buku.unique'     => 'Kode buku sudah digunakan oleh buku lain.',
            'judul.required'       => 'Judul buku wajib diisi.',
            'penulis.required'     => 'Penulis wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'jumlah.required'      => 'Jumlah buku wajib diisi.',
            'cover.image'          => 'File sampul harus berupa gambar.',
            'cover.mimes'          => 'Format gambar harus JPG, JPEG, atau PNG.',
            'cover.max'            => 'Ukuran gambar maksimal 2 MB.',
        ]);

        // Jika mengunggah cover baru, hapus foto lama
        if ($request->hasFile('cover')) {
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            $path = $request->file('cover')->store('covers', 'public');
            $validated['cover'] = $path;
        }

        // Kalkulasi ulang stok_tersedia jika jumlah buku diubah
        $diff = $validated['jumlah'] - $book->jumlah;
        $validated['stok_tersedia'] = max(0, $book->stok_tersedia + $diff);

        $book->update($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku "' . $book->judul . '" berhasil diperbarui.');
    }

    /**
     * Hapus buku dari database.
     */
    public function destroy(Book $book): RedirectResponse
    {
        if ($book->loans()->where('status', 'dipinjam')->exists()) {
            return redirect()->route('books.index')
                ->with('error', 'Buku "' . $book->judul . '" tidak bisa dihapus karena masih ada yang meminjam.');
        }

        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
} 