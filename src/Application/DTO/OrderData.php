<?php

namespace TestTask\Application\DTO;

use DateTimeImmutable;
use TestTask\Application\VO\OrderId;
use TestTask\Application\VO\OrderPriority;
use TestTask\Application\VO\OrderTitle;
use TestTask\Application\VO\ProductId;
use TestTask\Application\VO\ProductionQuantity;
use TestTask\Application\VO\ProductName;
use TestTask\Application\VO\ProductionProgress;

final readonly class OrderData
{
    public function __construct(
        public OrderId $id,
        public OrderTitle $title,
        public OrderPriority $priority,
        public ProductId $productId,
        public ProductName $productName,
        public ProductionQuantity $targetQuantity,
        public ProductionQuantity $producedQuantity,
        public ProductionProgress $productionProgress,
        public ?DateTimeImmutable $deadlineAt,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
