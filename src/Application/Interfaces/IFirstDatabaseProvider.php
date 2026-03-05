<?php

namespace TestTask\Application\Interfaces;

use DateTimeZone;
use TestTask\Application\VO\OrderFilterParams;
use TestTask\Application\VO\OrderSortingParams;
use TestTask\System\VO\PaginationParams;

interface IFirstDatabaseProvider
{
    public function getDataForFilteredAndPaginatedOrders(
        OrderFilterParams $filters,
        OrderSortingParams $sorting,
        PaginationParams $pagination,
        DateTimeZone $clientTimezone,
        bool $ignorePagination = false,
    ): array;
}
