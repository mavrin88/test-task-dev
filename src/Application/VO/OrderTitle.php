<?php

namespace TestTask\Application\VO;

use TestTask\System\VO\BaseStringVO;

final readonly class OrderTitle extends BaseStringVO
{
    public const MAX_LENGTH = 200;
    public const MIN_LENGTH = 1;
}
