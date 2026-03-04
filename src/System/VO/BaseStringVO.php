<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

readonly class BaseStringVO
{
    public const MAX_LENGTH = PHP_INT_MAX;
    public const MIN_LENGTH = 0;

    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        public string $value,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    protected function validate(): void
    {
        $length = mb_strlen($this->value);
        if ($length < static::MIN_LENGTH || $length > static::MAX_LENGTH) {
            throw new ValueNotValidException([
                'received_value' => $this->value,
                'value_length' => $length,
                'min_length' => static::MIN_LENGTH,
                'max_length' => static::MAX_LENGTH,
            ]);
        }
    }
}
