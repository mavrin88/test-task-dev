<?php

namespace TestTask\Application\DTO;

use TestTask\System\VO\PaginationMetadata;

final readonly class FilteredPaginatedOrders
{
    /**
     * @param OrderData[] $orders
     */
    public function __construct(
        public array $orders,
        public PaginationMetadata $paginationMetadata,
    ) {
    }
}
