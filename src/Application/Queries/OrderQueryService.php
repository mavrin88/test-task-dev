<?php

namespace TestTask\Application\Queries;

use DateTimeZone;
use TestTask\Application\DTO\FilteredPaginatedOrders;
use TestTask\Application\DTO\OrderData;
use TestTask\Application\Interfaces\IFirstDatabaseProvider;
use TestTask\Application\Interfaces\ISecondDatabaseProvider;
use TestTask\Application\VO\OrderId;
use TestTask\Application\VO\OrderFilterParams;
use TestTask\Application\VO\OrderPriority;
use TestTask\Application\VO\OrderSortingParams;
use TestTask\Application\VO\OrderTitle;
use TestTask\Application\VO\ProductId;
use TestTask\Application\VO\ProductName;
use TestTask\Application\VO\ProductionProgress;
use TestTask\Application\VO\ProductionQuantity;
use TestTask\System\VO\BaseSorting;
use TestTask\System\VO\PaginationParams;
use TestTask\System\VO\PaginationMetadata;

final readonly class OrderQueryService
{
    public function __construct(
        private IFirstDatabaseProvider $firstProvider,
        private ISecondDatabaseProvider $secondProvider,
    ) {
    }

    public function getFilteredPaginatedOrders(
        OrderFilterParams $filters,
        OrderSortingParams $sorting,
        PaginationParams $pagination,
        DateTimeZone $clientTimezone,
    ): FilteredPaginatedOrders {
        $dataFromFirstDataBase = $this->firstProvider->getDataForFilteredAndPaginatedOrders(
            $filters,
            $sorting,
            $pagination,
            $clientTimezone,
            $this->needsProductionBasedPostProcessing($filters, $sorting),
        );

        /** @var \TestTask\Infrastructure\FirstDataBase\EloquentOrderModel[] $allOrders */
        $allOrders = $dataFromFirstDataBase['orders'];
        $totalFromFirstDb = (int)$dataFromFirstDataBase['total'];

        $producedByOrderId = $this->secondProvider->getDataForFilteredAndPaginatedOrders(
            array_map(static fn($order) => $order->id, $allOrders),
            $filters,
        );

        $ordersWithProgress = $this->applyProductionFiltersAndProgress(
            $allOrders,
            $producedByOrderId,
            $filters,
        );

        if ($this->needsProductionBasedPostProcessing($filters, $sorting)) {
            $ordersWithProgress = $this->applySortingInMemory($ordersWithProgress, $sorting);

            $total = count($ordersWithProgress);
            $offset = ($pagination->page - 1) * $pagination->perPage;
            $ordersWithProgress = array_slice($ordersWithProgress, $offset, $pagination->perPage);
        } else {
            $total = $totalFromFirstDb;
        }

        $ordersDto = [];
        foreach ($ordersWithProgress as $item) {
            $ordersDto[] = $this->mapToOrderDataDto(
                $item['order'],
                $item['produced_quantity'],
                $item['production_progress'],
                $clientTimezone,
            );
        }

        $lastPage = (int)max(1, (int)ceil($total / $pagination->perPage));

        $paginationMetadata = new PaginationMetadata(
            currentPage: $pagination->page,
            perPage: $pagination->perPage,
            total: $total,
            lastPage: $lastPage,
        );

        return new FilteredPaginatedOrders($ordersDto, $paginationMetadata);
    }

    private function needsProductionBasedPostProcessing(
        OrderFilterParams $filters,
        OrderSortingParams $sorting,
    ): bool {
        if ($filters->isProductionStarted || $filters->isProductionCompleted) {
            return true;
        }

        return $sorting->field === OrderSortingParams::PRODUCTION_PROGRESS;
    }

    private function applyProductionFiltersAndProgress(
        array $orders,
        array $producedByOrderId,
        OrderFilterParams $filters,
    ): array {
        $result = [];

        foreach ($orders as $order) {
            $producedQuantity = $producedByOrderId[$order->id] ?? 0;
            $progress = $order->product_quantity > 0
                ? (int)round(($producedQuantity / $order->product_quantity) * 100)
                : 0;

            if ($filters->isProductionStarted && $producedQuantity <= 0) {
                continue;
            }

            if ($filters->isProductionCompleted && $producedQuantity < $order->product_quantity) {
                continue;
            }

            $result[] = [
                'order' => $order,
                'produced_quantity' => $producedQuantity,
                'production_progress' => $progress,
            ];
        }

        return $result;
    }

    private function applySortingInMemory(array $ordersWithProgress, OrderSortingParams $sorting): array
    {
        usort(
            $ordersWithProgress,
            static function (array $left, array $right) use ($sorting): int {
                $directionMultiplier = $sorting->getDirection() === BaseSorting::DIRECTION_DESC ? -1 : 1;

                return match ($sorting->getField()) {
                    OrderSortingParams::PRODUCTION_PROGRESS => $directionMultiplier * ($left['production_progress'] <=> $right['production_progress']),
                    default => 0,
                };
            },
        );

        return $ordersWithProgress;
    }

    private function mapToOrderDataDto(
        $order,
        int $producedQuantity,
        int $productionProgress,
        DateTimeZone $clientTimezone,
    ): OrderData {
        $id = new OrderId($order->id);
        $title = new OrderTitle($order->title);
        $priority = new OrderPriority($order->priority);
        $productId = new ProductId($order->product_id);
        $productName = new ProductName($order->product->name);

        $targetQuantity = new ProductionQuantity($order->product_quantity);
        $producedQuantityVo = new ProductionQuantity($producedQuantity);
        $productionProgressVo = new ProductionProgress($productionProgress);

        $deadlineAt = $order->deadline_at !== null
            ? $this->toClientDateTimeImmutable($order->deadline_at, $clientTimezone)
            : null;

        $createdAt = $this->toClientDateTimeImmutable($order->created_at, $clientTimezone);

        return new OrderData(
            id: $id,
            title: $title,
            priority: $priority,
            productId: $productId,
            productName: $productName,
            targetQuantity: $targetQuantity,
            producedQuantity: $producedQuantityVo,
            productionProgress: $productionProgressVo,
            deadlineAt: $deadlineAt,
            createdAt: $createdAt,
        );
    }

    private function toClientDateTimeImmutable(string $utcDateTime, DateTimeZone $clientTimezone): \DateTimeImmutable
    {
        $utc = new DateTimeZone('UTC');
        $dateTime = new \DateTimeImmutable($utcDateTime, $utc);

        return $dateTime->setTimezone($clientTimezone);
    }
}
