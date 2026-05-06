<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Konversi kolom category (enum) di tabel products menjadi category_id (foreign key).
     * Langkah:
     *  1. Tambah category_id (nullable dulu)
     *  2. Seed kategori default sesuai nilai enum lama
     *  3. Update category_id berdasarkan nilai category lama
     *  4. Buat category_id NOT NULL + foreign key
     *  5. Drop kolom category lama
     */
    public function up(): void
    {
        // 1. Tambah kolom category_id (nullable sementara agar tidak konflik data lama)
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('sku');
        });

        // 2. Seed kategori default (sesuai enum lama)
        $defaultCategories = [
            ['name' => 'Food',    'slug' => 'food'],
            ['name' => 'Drinks',  'slug' => 'drinks'],
            ['name' => 'Snacks',  'slug' => 'snacks'],
            ['name' => 'Dessert', 'slug' => 'dessert'],
        ];

        foreach ($defaultCategories as $cat) {
            // Cek dulu apakah sudah ada (hindari duplikat jika dijalankan ulang)
            $existing = DB::table('categories')->where('slug', $cat['slug'])->first();
            if (!$existing) {
                DB::table('categories')->insert([
                    'name'       => $cat['name'],
                    'slug'       => $cat['slug'],
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 3. Update category_id berdasarkan nilai enum category lama
        $categoryMap = DB::table('categories')
            ->whereIn('slug', ['food', 'drinks', 'snacks', 'dessert'])
            ->pluck('id', 'slug')
            ->toArray();

        foreach ($categoryMap as $slug => $id) {
            DB::table('products')
                ->where('category', $slug)
                ->whereNull('deleted_at') // hindari update soft-deleted rows
                ->update(['category_id' => $id]);
        }

        // Produk yang category_id masih null (nilai enum tidak dikenal), assign ke Food
        $fallbackId = $categoryMap['food'] ?? DB::table('categories')->first()->id ?? null;
        if ($fallbackId) {
            DB::table('products')
                ->whereNull('category_id')
                ->update(['category_id' => $fallbackId]);
        }

        // 4. Buat category_id NOT NULL dan tambah foreign key
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->restrictOnDelete();
            $table->index('category_id');
        });

        // 5. Drop kolom enum category lama
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        // Rollback: kembalikan kolom category (enum) dan hapus category_id
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropIndex(['category_id']);
        });

        // Tambah kolom category lama (sementara nullable)
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->nullable()->after('sku');
        });

        // Restore nilai dari category_id → category slug
        $categories = DB::table('categories')->pluck('slug', 'id')->toArray();
        foreach ($categories as $id => $slug) {
            DB::table('products')
                ->where('category_id', $id)
                ->update(['category' => $slug]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
};
