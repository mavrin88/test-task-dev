<?php

namespace TestTask\Infrastructure\SecondDataBase;

use Illuminate\Support\Collection;
use TestTask\Application\Interfaces\ISecondDatabaseProvider;
use TestTask\Application\VO\OrderFilterParams;

final class SecondDatabaseProvider implements ISecondDatabaseProvider
{
    public function getDataForFilteredAndPaginatedOrders(array $orderIds, OrderFilterParams $filters): array
    {
        if ($orderIds === []) {
            return [];
        }

        /** @var Collection $rows */
        $rows = EloquentOrderProgressModel::query()
            ->whereIn('order_id', $orderIds)
            ->groupBy('order_id')
            ->selectRaw('order_id, SUM(product_quantity) as produced_quantity')
            ->get();

        $result = [];

        foreach ($rows as $row) {
            $result[$row->order_id] = (int)$row->produced_quantity;
        }

        return $result;
    }
}
