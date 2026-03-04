<?php

namespace TestTask\Application\VO;

use DateTimeImmutable;

final readonly class OrderFilterParams
{
    /**
     * @param OrderPriority[] $priorities
     * @param ProductId[] $products
     */
    public function __construct(
        public array $priorities, // Приоритеты, может быть указано несколько или не указано ни одного, в случае пустого массива фильтрация по данному полю не осуществляется.
        public array $products, // Продукция, может быть указано насколько или не указано ничего, в случае пустого массива фильтрация по данному полю не осуществляется.
        public ?DateTimeImmutable $deadlineStartDate, // Начальная дата фильтра по дате дедлайна заказа, в случае null - не учитывается при фильтрации.
        public ?DateTimeImmutable $deadlineEndDate, // Конечная дата фильтра по дате дедлайна заказа, в случае null - не учитывается при фильтрации.
        public ?DateTimeImmutable $createdStartDate, // Начальная дата фильтра по дате создания заказа, в случае null - не учитывается при фильтрации.
        public ?DateTimeImmutable $createdEndDate, // Конечная дата фильтра по дате создания, в случае null - не учитывается при фильтрации.
        public bool $isProductionStarted, // true если произведена хотя бы одна единица продукции по заказу.
        public bool $isProductionCompleted, // true если произведена вся продукция по заказу.
    ) {
    }
}
