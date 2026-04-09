<?php

namespace App\DTO;

class OrderDTO
{
    public function __construct(
        public readonly int    $cashierId,
        public readonly array  $items,
        public readonly string $paymentMethod,
        public readonly int    $subtotal,
        public readonly int    $tax,
        public readonly int    $total,
    ) {}

    public static function fromRequest(array $data, int $cashierId): self
    {
        $subtotal = collect($data['items'])->sum(fn($item) => $item['price'] * $item['quantity']);
        $tax      = (int) round($subtotal * 0.10);
        $total    = $subtotal + $tax;

        return new self(
            cashierId:     $cashierId,
            items:         $data['items'],
            paymentMethod: $data['payment_method'],
            subtotal:      $subtotal,
            tax:           $tax,
            total:         $total,
        );
    }

    public function toArray(): array
    {
        return [
            'cashier_id'     => $this->cashierId,
            'items'          => $this->items,
            'payment_method' => $this->paymentMethod,
            'subtotal'       => $this->subtotal,
            'tax'            => $this->tax,
            'total'          => $this->total,
        ];
    }
}
