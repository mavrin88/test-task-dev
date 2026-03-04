<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

abstract readonly class BaseSorting
{
    protected const ALLOWED_FIELDS = [];
    public const DIRECTION_ASC = 'asc';
    public const DIRECTION_DESC = 'desc';
    public const ALLOWED_DIRECTIONS = [self::DIRECTION_ASC, self::DIRECTION_DESC];

    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        public string $field,
        public string $direction = self::DIRECTION_ASC,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    private function validate(): void
    {
        if (!in_array($this->field, static::ALLOWED_FIELDS, true)) {
            throw new ValueNotValidException(['field' => $this->field, 'allowed' => static::ALLOWED_FIELDS]);
        }

        if (!in_array($this->direction, self::ALLOWED_DIRECTIONS, true)) {
            throw new ValueNotValidException(
                ['direction' => $this->direction, 'allowed' => self::ALLOWED_DIRECTIONS],
            );
        }
    }
}
