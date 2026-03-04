<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

abstract readonly class Uuid
{
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
    private function validate(): void
    {
        if (strlen($this->value) !== 36) {
            throw new ValueNotValidException(['value' => $this->value]);
        }

        $validPattern = '\A[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[1-8][0-9A-Fa-f]{3}-[ABab89][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}\z';
        if (preg_match($validPattern, $this->value) !== 1) {
            throw new ValueNotValidException(['value' => $this->value]);
        }
    }

    public function equals(Uuid $uuid): bool
    {
        return $this->value === $uuid->getValue();
    }
}
