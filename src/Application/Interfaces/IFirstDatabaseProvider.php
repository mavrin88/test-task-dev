<?php

namespace TestTask\Application\Interfaces;

interface IFirstDatabaseProvider
{
    public function getDataForFilteredAndPaginatedOrders(): array;
}
