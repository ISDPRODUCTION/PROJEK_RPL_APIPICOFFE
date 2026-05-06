@extends('layouts.app')

@section('title', 'Menu Management')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-[#1C1917]">Menu Management</h1>
            <span class="text-base text-[#78716C]">({{ $products->total() }} Items)</span>
        </div>
        <div class="flex gap-2">
            <button onclick="categoryModule.openAddModal()"
                    class="flex items-center gap-2 px-4 py-2.5 bg-stone-800 hover:bg-stone-900 text-white rounded-2xl text-sm font-semibold transition-colors">
                Tambah Kategori
            </button>
            <button onclick="menuModule.openAddModal()"
                    class="flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-[#EA580C] text-white rounded-2xl text-sm font-semibold transition-colors shadow-lg shadow-orange-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v8M8 12h8"/>
                </svg>
                Tambah Menu
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 max-w-sm relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#78716C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="menu-search" value="{{ $search }}" placeholder="Search menu items..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm bg-white rounded-2xl border border-stone-200 focus:ring-2 focus:ring-primary/30 outline-none">
        </div>

        <div class="flex items-center gap-2 ml-auto">
            <div id="category-container" class="flex gap-2"></div>
            <div class="w-px h-6 bg-stone-300 mx-1"></div>
            <div id="category-pagination" class="flex gap-1"></div>
        </div>
    </div>
    
    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px]">
                <thead>
                    <tr class="border-b border-stone-100">
                        <th class="text-left py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider w-16">Image</th>
                        <th class="text-left py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider">Product Name</th>
                        <th class="text-left py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider">Category</th>
                        <th class="text-left py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider">Price</th>
                        <th class="text-left py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider">Stock</th>
                        <th class="text-right py-4 px-4 text-xs font-bold text-[#78716C] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-stone-50 transition-colors">
                        <td class="py-3 px-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="w-11 h-11 rounded-xl object-cover bg-stone-100">
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm font-semibold text-[#1C1917]">{{ $product->name }}</p>
                            <p class="text-xs text-[#78716C]">ID: {{ $product->sku }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-1 bg-stone-100 text-[#78716C] text-xs font-medium rounded-lg capitalize">
                                {{ $product->category?->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm font-semibold text-[#1C1917]">{{ $product->formatted_price }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="flex items-center gap-1.5 text-sm font-medium
                                        {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-orange-500' : 'text-red-500') }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                                {{ $product->stock }} pcs
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="menuModule.openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $product->category?->slug }}', {{ $product->price }}, {{ $product->stock }}, '{{ $product->image_url }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-blue-200 text-blue-500 hover:bg-blue-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="menuModule.openDeleteModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-[#78716C]">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($products->total() > 0)
        <div class="px-4 md:px-6 py-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-[#78716C]">Showing {{ $products->firstItem() }} – {{ $products->lastItem() }} of {{ $products->total() }} items</p>
            <div class="flex items-center gap-1">
                @if($products->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-full text-stone-300 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                </span>
                @else
                <a href="{{ $products->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-[#78716C] hover:bg-stone-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @endif
                @for($i = 1; $i <= min($products->lastPage(), 5); $i++)
                <a href="{{ $products->url($i) }}"
                    class="w-8 h-8 flex items-center justify-center rounded-full text-sm font-semibold transition-colors
                            {{ $products->currentPage() === $i ? 'bg-primary text-white' : 'text-[#78716C] hover:bg-stone-100' }}">
                    {{ $i }}
                </a>
                @endfor
                @if(!$products->onLastPage())
                <a href="{{ $products->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full text-[#78716C] hover:bg-stone-100">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Add Menu Modal --}}
<div id="add-menu-modal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="menuModule.closeAddModal()"></div>
    <div class="relative bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full sm:max-w-lg z-10 max-h-[90vh] overflow-y-auto">
        <div class="p-5 md:p-6">
            {{-- Mobile drag indicator --}}
            <div class="flex justify-center mb-4 sm:hidden">
                <div class="w-10 h-1 bg-stone-200 rounded-full"></div>
            </div>
            <div class="flex items-start justify-between mb-1">
                <div>
                    <h2 class="text-xl font-bold text-[#1C1917]">Add New Menu</h2>
                    <p class="text-sm text-[#78716C]">Provide the details for the new product catalog.</p>
                </div>
                <button onclick="menuModule.closeAddModal()" class="text-stone-400 hover:text-[#1C1917] mt-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="add-menu-form" class="mt-5 space-y-4" novalidate>
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Product Name</label>
                    <input type="text" name="name" id="add-name" placeholder="Isi nama menu"
                            class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none transition-colors">
                    <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="err-add-name">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span></span>
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Category</label>
                        <select name="category" id="add-category-select" class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none appearance-none bg-white transition-colors">
                            <option value="">Pilih kategori</option>
                        </select>
                        <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="err-add-category">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-primary">Rp</span>
                            <input type="number" name="price" id="add-price" placeholder="25000" min="0"
                                    class="w-full pl-10 pr-4 py-3 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none transition-colors">
                        </div>
                        <p class="hidden mt-1.5 text-xs text-red-500 flex items-center gap-1" id="err-add-price">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Initial Stock</label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="add-stock-minus"
                                class="w-10 h-10 flex items-center justify-center rounded-xl border border-stone-200 text-[#78716C] hover:border-stone-300 text-lg font-light">−</button>
                        <input type="number" name="stock" id="add-stock-input" value="0" min="0"
                                class="flex-1 text-center px-4 py-3 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <button type="button" id="add-stock-plus"
                                class="w-10 h-10 flex items-center justify-center rounded-xl border border-stone-200 text-[#78716C] hover:border-stone-300 text-lg font-light">+</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Product Image</label>
                    <label id="add-image-dropzone" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-stone-200 rounded-2xl cursor-pointer hover:border-primary/50 hover:bg-orange-50/30 transition-all bg-stone-50/50">
                        <div class="flex flex-col items-center" id="add-image-placeholder">
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-[#1C1917]">Click to upload</p>
                            <p class="text-xs text-[#78716C]">JPG, PNG up to 5MB</p>
                        </div>
                        <img id="add-image-preview" src="" alt="Preview" class="hidden w-full h-full object-cover rounded-2xl">
                        <input type="file" name="image" id="add-image-input" accept="image/*" class="hidden" onchange="menuModule.previewImage(this)">
                    </label>
                </div>
                <div class="flex gap-3 pt-2 pb-2">
                    <button type="button" onclick="menuModule.closeAddModal()"
                            class="flex-1 py-3 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] border border-stone-200 rounded-2xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="add-submit-btn"
                            class="flex-1 py-3 bg-primary hover:bg-[#EA580C] text-white rounded-2xl text-sm font-semibold transition-colors shadow-lg shadow-orange-200">
                        Tambah Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Menu Modal --}}
<div id="edit-menu-modal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="menuModule.closeEditModal()"></div>
    <div class="relative bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full sm:max-w-lg z-10 max-h-[90vh] overflow-y-auto">
        <div class="p-5 md:p-6">
            <div class="flex justify-center mb-4 sm:hidden">
                <div class="w-10 h-1 bg-stone-200 rounded-full"></div>
            </div>
            <div class="flex items-start justify-between mb-1">
                <div>
                    <h2 class="text-xl font-bold text-[#1C1917]">Edit Menu</h2>
                    <p class="text-sm text-[#78716C]">Update the details for the existing product.</p>
                </div>
                <button onclick="menuModule.closeEditModal()" class="text-stone-400 hover:text-[#1C1917] mt-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="edit-menu-form" class="mt-5">
                @csrf
                <input type="hidden" name="_productId" id="edit-product-id">
                <div class="flex gap-4 mb-4">
                    <div class="flex flex-col items-center gap-2 flex-shrink-0">
                        <img id="edit-product-image" src="" alt="Product" class="w-24 h-24 rounded-xl object-cover bg-stone-100">
                        <button type="button" onclick="document.getElementById('edit-image-input').click()" class="text-xs font-semibold text-primary flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Ganti
                        </button>
                        <input type="file" id="edit-image-input" name="image" accept="image/*" class="hidden" onchange="menuModule.previewEditImage(this)">
                    </div>
                    <div class="flex-1 space-y-3 min-w-0">
                        <div>
                            <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Product Name</label>
                            <input type="text" id="edit-name" name="name"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Category</label>
                            <select id="edit-category" name="category"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none bg-white">
                                <option value="">Pilih kategori</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Price</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-[#78716C]">Rp</span>
                            <input type="number" id="edit-price" name="price"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Stock</label>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="document.getElementById('edit-stock').value=Math.max(0,+document.getElementById('edit-stock').value-1)"
                                    class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-xl border border-stone-200 text-[#78716C] text-lg font-light">−</button>
                            <input type="number" id="edit-stock" name="stock" min="0"
                                    class="flex-1 min-w-0 text-center px-2 py-2.5 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                            <button type="button" onclick="document.getElementById('edit-stock').value=+document.getElementById('edit-stock').value+1"
                                    class="w-9 h-9 flex-shrink-0 flex items-center justify-center rounded-xl border border-stone-200 text-[#78716C] text-lg font-light">+</button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 pb-2">
                    <button type="button" onclick="menuModule.closeEditModal()"
                            class="flex-1 py-3 text-sm font-semibold border border-stone-200 rounded-2xl text-[#78716C] hover:text-[#1C1917] transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-primary hover:bg-[#EA580C] text-white rounded-2xl text-sm font-semibold transition-colors shadow-lg shadow-orange-200">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="menuModule.closeDeleteModal()"></div>
    <div class="relative bg-white rounded-t-3xl sm:rounded-2xl shadow-2xl w-full sm:max-w-sm z-10 p-6 sm:p-8 text-center">
        <div class="flex justify-center mb-4 sm:hidden">
            <div class="w-10 h-1 bg-stone-200 rounded-full"></div>
        </div>
        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
            <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-[#1C1917] mb-3">Hapus Menu</h2>
        <p id="delete-confirm-text" class="text-sm text-[#78716C] mb-6"></p>
        <input type="hidden" id="delete-product-id">
        <div class="flex gap-3">
            <button onclick="menuModule.closeDeleteModal()"
                    class="flex-1 py-3 text-sm font-semibold border-2 border-stone-200 rounded-2xl text-[#78716C]">Batal</button>
            <button onclick="menuModule.confirmDelete()"
                    class="flex-1 py-3 bg-primary hover:bg-[#EA580C] text-white rounded-2xl text-sm font-semibold transition-colors">Hapus</button>
        </div>
    </div>
</div>

{{-- Modal Tambah Kategori --}}
<div id="add-category-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="categoryModule.closeAddModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10">
        <div class="p-6">
            <div class="flex items-start justify-between mb-1">
                <h2 class="text-xl font-bold text-[#1C1917]">Tambah Kategori</h2>
                <button onclick="categoryModule.closeAddModal()" class="text-stone-400 hover:text-[#1C1917]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="add-category-form" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-[#1C1917] mb-1.5">Nama Kategori</label>
                    <input type="text" name="name" placeholder="Contoh: Hot Drinks" required
                           class="w-full px-4 py-3 rounded-2xl border border-stone-200 text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="categoryModule.closeAddModal()"
                            class="flex-1 py-3 text-sm font-semibold text-[#78716C] border border-stone-200 rounded-2xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-stone-800 hover:bg-stone-900 text-white rounded-2xl text-sm font-semibold transition-colors">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const menuModule = {
    // ── Helpers validasi ─────────────────────────────────────────────────────
    _setError(fieldId, errId, msg) {
        const field = document.getElementById(fieldId);
        const err   = document.getElementById(errId);
        if (!field || !err) return;
        field.classList.add('border-red-400', 'focus:ring-red-200');
        field.classList.remove('border-stone-200');
        err.querySelector('span').textContent = msg;
        err.classList.remove('hidden');
        err.classList.add('flex');
    },
    _clearError(fieldId, errId) {
        const field = document.getElementById(fieldId);
        const err   = document.getElementById(errId);
        if (!field || !err) return;
        field.classList.remove('border-red-400', 'focus:ring-red-200');
        field.classList.add('border-stone-200');
        err.classList.add('hidden');
        err.classList.remove('flex');
    },
    _clearAllAddErrors() {
        [['add-name','err-add-name'], ['add-category-select','err-add-category'], ['add-price','err-add-price']].forEach(([f,e]) => this._clearError(f,e));
    },
    _validateAddForm() {
        let valid = true;
        const name  = document.getElementById('add-name')?.value.trim();
        const cat   = document.getElementById('add-category-select')?.value;
        const price = document.getElementById('add-price')?.value;

        this._clearAllAddErrors();

        if (!name) {
            this._setError('add-name', 'err-add-name', 'Nama menu tidak boleh kosong.');
            valid = false;
        } else if (name.length < 2) {
            this._setError('add-name', 'err-add-name', 'Nama menu minimal 2 karakter.');
            valid = false;
        }
        if (!cat) {
            this._setError('add-category-select', 'err-add-category', 'Pilih kategori terlebih dahulu.');
            valid = false;
        }
        if (!price || price === '') {
            this._setError('add-price', 'err-add-price', 'Harga tidak boleh kosong.');
            valid = false;
        } else if (parseInt(price) < 0) {
            this._setError('add-price', 'err-add-price', 'Harga tidak boleh negatif.');
            valid = false;
        }
        return valid;
    },

    // ── Modal Add ────────────────────────────────────────────────────────────
    openAddModal() {
        this._clearAllAddErrors();
        document.getElementById('add-menu-modal').classList.remove('hidden');
        setTimeout(() => document.getElementById('add-name')?.focus(), 100);
    },
    closeAddModal() {
        document.getElementById('add-menu-modal').classList.add('hidden');
        document.getElementById('add-menu-form').reset();
        this._clearAllAddErrors();
        // Reset image preview
        const preview = document.getElementById('add-image-preview');
        const placeholder = document.getElementById('add-image-placeholder');
        if (preview) { preview.src = ''; preview.classList.add('hidden'); }
        if (placeholder) placeholder.classList.remove('hidden');
    },

    // ── Modal Edit ───────────────────────────────────────────────────────────
    openEditModal(id, name, category, price, stock, image) {
        document.getElementById('edit-product-id').value = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-price').value = price;
        document.getElementById('edit-stock').value = stock;
        document.getElementById('edit-product-image').src = image;
        // Set kategori setelah DOM ready (kategori mungkin baru di-populate)
        const setCategory = () => {
            const sel = document.getElementById('edit-category');
            if (sel) sel.value = category;
        };
        setCategory();
        setTimeout(setCategory, 300); // fallback jika populate async belum selesai
        document.getElementById('edit-menu-modal').classList.remove('hidden');
    },
    closeEditModal() { document.getElementById('edit-menu-modal').classList.add('hidden'); },

    // ── Preview gambar ───────────────────────────────────────────────────────
    previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('add-image-preview');
                const placeholder = document.getElementById('add-image-placeholder');
                if (preview) { preview.src = e.target.result; preview.classList.remove('hidden'); }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    },
    previewEditImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => { document.getElementById('edit-product-image').src = e.target.result; };
            reader.readAsDataURL(input.files[0]);
        }
    },
    removeEditImage() {
        document.getElementById('edit-product-image').src = 'https://placehold.co/400x400/FFF7ED/F97316?text=No+Image';
        document.getElementById('edit-image-input').value = '';
    },

    // ── Modal Delete ─────────────────────────────────────────────────────────
    openDeleteModal(id, name) {
        document.getElementById('delete-product-id').value = id;
        document.getElementById('delete-confirm-text').innerHTML =
            `Apakah Anda yakin ingin menghapus menu <strong>'${name}'</strong>?`;
        document.getElementById('delete-modal').classList.remove('hidden');
    },
    closeDeleteModal() { document.getElementById('delete-modal').classList.add('hidden'); },
    async confirmDelete() {
        const id    = document.getElementById('delete-product-id').value;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const res   = await fetch(`/menu/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
        });
        const data = await res.json();
        if (data.success) window.location.reload();
    }
};

// ── Real-time clear error on input ───────────────────────────────────────────
['add-name', 'add-price'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', function() {
        menuModule._clearError(id, 'err-' + id.replace('add-', 'err-add-') );
    });
});
document.getElementById('add-category-select')?.addEventListener('change', function() {
    menuModule._clearError('add-category-select', 'err-add-category');
});
// Fix: map id ke error id yang benar
document.getElementById('add-name')?.addEventListener('input', () => menuModule._clearError('add-name', 'err-add-name'));
document.getElementById('add-price')?.addEventListener('input', () => menuModule._clearError('add-price', 'err-add-price'));

// Stock buttons
document.getElementById('add-stock-minus').addEventListener('click', () => {
    const inp = document.getElementById('add-stock-input');
    inp.value = Math.max(0, parseInt(inp.value || 0) - 1);
});
document.getElementById('add-stock-plus').addEventListener('click', () => {
    const inp = document.getElementById('add-stock-input');
    inp.value = parseInt(inp.value || 0) + 1;
});

// ── Add form submit ───────────────────────────────────────────────────────────
document.getElementById('add-menu-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    if (!menuModule._validateAddForm()) return;

    const btn = document.getElementById('add-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>';

    try {
        const res  = await fetch('{{ route("menu.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: new FormData(this)
        });
        const data = await res.json();
        if (data.success) {
            window.location.reload();
        } else {
            // Tampilkan error dari server (validasi backend)
            if (data.errors) {
                if (data.errors.name)     menuModule._setError('add-name', 'err-add-name', data.errors.name[0]);
                if (data.errors.category) menuModule._setError('add-category-select', 'err-add-category', data.errors.category[0]);
                if (data.errors.price)    menuModule._setError('add-price', 'err-add-price', data.errors.price[0]);
            } else {
                menuModule._setError('add-name', 'err-add-name', data.message || 'Gagal menambah menu, coba lagi.');
            }
            btn.disabled = false;
            btn.textContent = 'Tambah Menu';
        }
    } catch {
        btn.disabled = false;
        btn.textContent = 'Tambah Menu';
        menuModule._setError('add-name', 'err-add-name', 'Koneksi gagal, periksa jaringan Anda.');
    }
});

// ── Edit form submit ──────────────────────────────────────────────────────────
document.getElementById('edit-menu-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const id = document.getElementById('edit-product-id').value;
    const formData = new FormData(this);
    formData.append('_method', 'POST');
    const res = await fetch(`/menu/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: formData
    });
    const data = await res.json();
    if (data.success) window.location.reload();
    else alert('Error: ' + (data.message || 'Gagal update menu'));
});

// ── Search ────────────────────────────────────────────────────────────────────
document.getElementById('menu-search').addEventListener('input', function() {
    clearTimeout(this._timer);
    this._timer = setTimeout(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', this.value);
        url.searchParams.set('page', 1); // reset ke halaman 1
        window.location.href = url.toString();
    }, 400);
});

// ── Category Module ───────────────────────────────────────────────────────────
const categoryModule = {
    currentCategory: (new URLSearchParams(window.location.search)).get('category') || 'all',
    search: (new URLSearchParams(window.location.search)).get('search') || '',

    async loadCategories(page = 1) {
        try {
            const res = await fetch('/categories/api?page=' + page);
            const data = await res.json();
            this.renderTabs(data.data, data.prev_page_url, data.next_page_url, data.current_page);
            this.populateSelects(data.data);
        } catch (e) { console.error('Gagal load kategori', e); }
    },

    renderTabs(categories, prevUrl, nextUrl, currentPage) {
        const container = document.getElementById('category-container');
        const pagination = document.getElementById('category-pagination');
        const baseClass = 'px-4 py-2 text-sm font-semibold rounded-lg transition-colors';
        const activeClass = 'text-primary border-b-2 border-primary';
        const inactiveClass = 'text-[#78716C] hover:text-[#1C1917]';
        const isAll = this.currentCategory === 'all';
        let html = '<a href="/menu?category=all&search=' + this.search + '" class="' + baseClass + ' ' + (isAll ? activeClass : inactiveClass) + '">All</a>';
        const self = this;
        categories.forEach(function(cat) {
            const active = self.currentCategory === cat.slug;
            html += '<a href="/menu?category=' + cat.slug + '&search=' + self.search + '" class="' + baseClass + ' ' + (active ? activeClass : inactiveClass) + '">' + cat.name + '</a>';
        });
        container.innerHTML = html;
        let pHtml = '';
        if (prevUrl) pHtml += '<button onclick="categoryModule.loadCategories(' + (currentPage - 1) + ')" class="p-1 rounded bg-stone-100 hover:bg-stone-200 text-stone-600 font-bold px-2">&lsaquo;</button>';
        if (nextUrl) pHtml += '<button onclick="categoryModule.loadCategories(' + (currentPage + 1) + ')" class="p-1 rounded bg-stone-100 hover:bg-stone-200 text-stone-600 font-bold px-2">&rsaquo;</button>';
        pagination.innerHTML = pHtml;
    },

    populateSelects(categories) {
        const addSel = document.getElementById('add-category-select');
        const editSel = document.getElementById('edit-category');
        const prevAdd = addSel ? addSel.value : '';
        const prevEdit = editSel ? editSel.value : '';
        let opts = '<option value="">Pilih kategori</option>';
        categories.forEach(function(cat) { opts += '<option value="' + cat.slug + '">' + cat.name + '</option>'; });
        if (addSel) { addSel.innerHTML = opts; if (prevAdd) addSel.value = prevAdd; }
        if (editSel) { editSel.innerHTML = opts; if (prevEdit) editSel.value = prevEdit; }
    },

    openAddModal() { document.getElementById('add-category-modal').classList.remove('hidden'); },
    closeAddModal() {
        document.getElementById('add-category-modal').classList.add('hidden');
        document.getElementById('add-category-form').reset();
    }
};

document.addEventListener('DOMContentLoaded', function() { categoryModule.loadCategories(); });

const _addCatForm = document.getElementById('add-category-form');
if (_addCatForm) {
    _addCatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const token = document.querySelector('meta[name="csrf-token"]').content;
        fetch('/categories', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: new FormData(this)
        }).then(r => r.json()).then(function(data) {
            if (data.success) {
                categoryModule.closeAddModal();
                categoryModule.loadCategories();
            } else {
                alert('Error: ' + ((data.errors && data.errors.name && data.errors.name[0]) || data.message || 'Gagal menyimpan kategori'));
            }
        });
    });
}

</script>
@endpush