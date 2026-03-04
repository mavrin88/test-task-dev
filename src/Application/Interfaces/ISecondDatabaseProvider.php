<?php

namespace TestTask\Application\Interfaces;

interface ISecondDatabaseProvider
{
    public function getDataForFilteredAndPaginatedOrders(): array;
}
