<?php

namespace TestTask\Infrastructure\FirstDataBase;

use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use TestTask\Application\Interfaces\IFirstDatabaseProvider;
use TestTask\Application\VO\OrderFilterParams;
use TestTask\Application\VO\OrderSortingParams;
use TestTask\System\VO\PaginationParams;

final class FirstDatabaseProvider implements IFirstDatabaseProvider
{
    public function getDataForFilteredAndPaginatedOrders(
        OrderFilterParams $filters,
        OrderSortingParams $sorting,
        PaginationParams $pagination,
        DateTimeZone $clientTimezone,
        bool $ignorePagination = false,
    ): array {
        $query = EloquentOrderModel::query();

        $query = $this->applyFilters($query, $filters, $clientTimezone);
        $query = $this->applySorting($query, $sorting);

        $total = (clone $query)->count('Order.id');

        if ($ignorePagination === false) {
            $offset = ($pagination->page - 1) * $pagination->perPage;
            $query->offset($offset)->limit($pagination->perPage);
        }

        $orders = $query
            ->with('product')
            ->get()
            ->all();

        return [
            'orders' => $orders,
            'total' => $total,
        ];
    }

    private function applyFilters(
        Builder $query,
        OrderFilterParams $filters,
        DateTimeZone $clientTimezone,
    ): Builder {
        if ($filters->priorities !== []) {
            $query->whereIn('priority', array_map(
                static fn($priority) => $priority->getValue(),
                $filters->priorities,
            ));
        }

        if ($filters->products !== []) {
            $query->whereIn('product_id', array_map(
                static fn($productId) => $productId->value,
                $filters->products,
            ));
        }

        if ($filters->deadlineStartDate instanceof DateTimeImmutable) {
            $query->where(
                'deadline_at',
                '>=',
                $this->toUtcString($filters->deadlineStartDate, $clientTimezone),
            );
        }

        if ($filters->deadlineEndDate instanceof DateTimeImmutable) {
            $query->where(
                'deadline_at',
                '<=',
                $this->toUtcString($filters->deadlineEndDate, $clientTimezone),
            );
        }

        if ($filters->createdStartDate instanceof DateTimeImmutable) {
            $query->where(
                'created_at',
                '>=',
                $this->toUtcString($filters->createdStartDate, $clientTimezone),
            );
        }

        if ($filters->createdEndDate instanceof DateTimeImmutable) {
            $query->where(
                'created_at',
                '<=',
                $this->toUtcString($filters->createdEndDate, $clientTimezone),
            );
        }

        return $query;
    }

    private function applySorting(Builder $query, OrderSortingParams $sorting): Builder
    {
        $direction = $sorting->getDirection();

        return match ($sorting->getField()) {
            OrderSortingParams::TITLE => $query->orderBy('title', $direction),
            OrderSortingParams::PRODUCT_NAME => $query
                ->join('Product', 'Product.id', '=', 'Order.product_id')
                ->orderBy('Product.name', $direction)
                ->select('Order.*'),
            OrderSortingParams::DEADLINE_AT => $query->orderBy('deadline_at', $direction),
            OrderSortingParams::CREATED_AT => $query->orderBy('created_at', $direction),
            default => $query,
        };
    }

    private function toUtcString(DateTimeImmutable $dateTime, DateTimeZone $clientTimezone): string
    {
        $clientTime = $dateTime->setTimezone($clientTimezone);

        return $clientTime
            ->setTimezone(new DateTimeZone('UTC'))
            ->format('Y-m-d H:i:s');
    }
}
