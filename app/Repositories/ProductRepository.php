<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(private readonly Product $model) {}

    public function getAllAvailable(?string $category = null, ?string $search = null): Collection
    {
        return $this->model
            ->with('category')
            ->available()
            ->when($category && $category != 'all', fn($q) => $q->byCategory($category))
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }

    public function getPaginated(?string $category = null, ?string $search = null, int $perPage = 8): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->when($category && $category != 'all', fn($q) => $q->byCategory($category))
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function decrementStock(int $id, int $quantity): void
    {
        $this->model->where('id', $id)->decrement('stock', $quantity);
    }

    public function count(): int
    {
        return $this->model->count();
    }
}