<?php

namespace TestTask\Application\VO;

use TestTask\System\VO\BaseSorting;

final readonly class OrderSortingParams extends BaseSorting
{
    public const TITLE = 'title';
    public const PRODUCT_NAME = 'product_name';
    public const DEADLINE_AT = 'deadline_at';
    public const CREATED_AT = 'created_at';
    public const PRODUCTION_PROGRESS = 'production_progress';
    public const ALLOWED_FIELDS = [
        self::PRODUCT_NAME,
        self::TITLE,
        self::DEADLINE_AT,
        self::CREATED_AT,
        self::PRODUCTION_PROGRESS,
    ];

    public function getField(): string
    {
        return $this->field;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }
}
