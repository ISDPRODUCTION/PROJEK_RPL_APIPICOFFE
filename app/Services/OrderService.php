<?php

namespace App\Services;

use App\DTO\OrderDTO;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private readonly OrderRepository   $orderRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function createOrder(OrderDTO $dto): Order
    {
        // Validate & decrement stock
        foreach ($dto->items as $item) {
            $product = $this->productRepository->findById($item['product_id']);

            if (!$product) {
                throw new \RuntimeException("Product ID {$item['product_id']} not found.");
            }

            if ($product->stock < $item['quantity']) {
                throw new \RuntimeException("Insufficient stock for: {$product->name}");
            }
        }

        $order = $this->orderRepository->create([
            'order_number'   => Order::generateOrderNumber(),
            'cashier_id'     => $dto->cashierId,
            'subtotal'       => $dto->subtotal,
            'tax'            => $dto->tax,
            'total'          => $dto->total,
            'payment_method' => $dto->paymentMethod,
            'order_date'     => Carbon::now(),
            'status'         => 'completed',
        ], $dto->items);

        // Decrement stock after order is persisted
        foreach ($dto->items as $item) {
            $this->productRepository->decrementStock($item['product_id'], $item['quantity']);
        }

        Log::info('Order created', ['order_number' => $order->order_number]);

        return $order;
    }

    public function getOrderByNumber(string $orderNumber): ?Order
    {
        return $this->orderRepository->findByOrderNumber($orderNumber);
    }

    public function getRecentOrders(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->orderRepository->getRecentOrders($limit);
    }

    public function getDashboardStats(): array
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayRevenue      = $this->orderRepository->getDailyRevenue($today);
        $yesterdayRevenue  = $this->orderRepository->getDailyRevenue($yesterday);
        $todayCount        = $this->orderRepository->getDailyTransactionCount($today);
        $yesterdayCount    = $this->orderRepository->getDailyTransactionCount($yesterday);

        $revenueChange = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : 0;

        $countChange = $yesterdayCount > 0
            ? round((($todayCount - $yesterdayCount) / $yesterdayCount) * 100, 1)
            : 0;

        return [
            'today_revenue'   => $todayRevenue,
            'today_count'     => $todayCount,
            'revenue_change'  => $revenueChange,
            'count_change'    => $countChange,
        ];
    }
}
