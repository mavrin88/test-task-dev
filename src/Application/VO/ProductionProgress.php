<?php

namespace TestTask\Application\VO;

use TestTask\System\VO\BaseIntVO;

final readonly class ProductionProgress extends BaseIntVO
{
    public const MIN_VALUE = 0;
    public const MAX_VALUE = 100;
}
