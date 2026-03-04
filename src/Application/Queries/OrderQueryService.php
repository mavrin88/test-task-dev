<?php

namespace TestTask\Application\Queries;

use DateTimeZone;
use TestTask\Application\DTO\FilteredPaginatedOrders;
use TestTask\Application\Interfaces\IFirstDatabaseProvider;
use TestTask\Application\Interfaces\ISecondDatabaseProvider;
use TestTask\Application\VO\OrderFilterParams;
use TestTask\Application\VO\OrderSortingParams;
use TestTask\System\VO\PaginationParams;

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
        $dataFromFirstDataBase = $this->firstProvider->getDataForFilteredAndPaginatedOrders();
        $dataFromSecondDataBase = $this->secondProvider->getDataForFilteredAndPaginatedOrders();

        // TODO Ваш код...

        return new FilteredPaginatedOrders();
    }
}
