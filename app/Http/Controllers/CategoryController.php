<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display list kategori.
     * Search, filter, sort, dan pagination ditangani oleh DataTables.js (client-side).
     */
    public function index(): View
    {
        // Ambil semua kategori + jumlah relasi buku, diurutkan berdasarkan kode_kategori (KTG001, KTG002, dst)
        $categories = Category::withCount('books')
            ->orderBy('kode_kategori', 'asc')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show form tambah kategori
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru dengan validasi
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kategori'  => ['required', 'string', 'max:20', 'unique:categories,kode_kategori'],
            'nama_kategori'  => ['required', 'string', 'max:100'],
            'deskripsi'      => ['nullable', 'string', 'max:500'],
        ], [
            'kode_kategori.required' => 'Kode kategori wajib diisi.',
            'kode_kategori.unique'   => 'Kode kategori sudah dipakai, gunakan kode lain.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori "' . $validated['nama_kategori'] . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kategori beserta daftar buku di dalamnya
     */
    public function show(Category $category): View
    {
        $books = $category->books()->orderByDesc('created_at')->get();
        return view('categories.show', compact('category', 'books'));
    }

    /**
     * Show form edit
     */
    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'kode_kategori'  => ['required', 'string', 'max:20', 'unique:categories,kode_kategori,' . $category->id],
            'nama_kategori'  => ['required', 'string', 'max:100'],
            'deskripsi'      => ['nullable', 'string', 'max:500'],
        ], [
            'kode_kategori.required' => 'Kode kategori wajib diisi.',
            'kode_kategori.unique'   => 'Kode kategori sudah dipakai oleh kategori lain.',
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori (cek relasi buku dulu)
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori "' . $category->nama_kategori . '" tidak bisa dihapus karena masih punya buku terkait.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
} 