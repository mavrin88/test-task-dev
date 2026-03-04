<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

readonly class BaseIntVO
{
    public const MAX_VALUE = PHP_INT_MAX;
    public const MIN_VALUE = PHP_INT_MIN;

    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        public int $value,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    protected function validate(): void
    {
        if ($this->value < static::MIN_VALUE || $this->value > static::MAX_VALUE) {
            throw new ValueNotValidException([
                'received_value' => $this->value,
                'min_value' => static::MIN_VALUE,
                'max_value' => static::MAX_VALUE,
            ]);
        }
    }
}
