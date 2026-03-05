<?php

namespace TestTask\Application\Interfaces;

use TestTask\Application\VO\OrderFilterParams;

interface ISecondDatabaseProvider
{
    /**
     * @param string[] $orderIds
     */
    public function getDataForFilteredAndPaginatedOrders(
        array $orderIds,
        OrderFilterParams $filters,
    ): array;
}
