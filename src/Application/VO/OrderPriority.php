<?php

namespace TestTask\Application\VO;

use TestTask\System\Exceptions\ValueNotValidException;

final readonly class OrderPriority
{
    public const LOW = 'low';
    public const NORMAL = 'normal';
    public const HIGH = 'high';
    public const ALLOWED_PRIORITIES = [self::LOW, self::NORMAL, self::HIGH];

    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        private string $priority,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    private function validate(): void
    {
        if (in_array($this->priority, self::ALLOWED_PRIORITIES, true)) {
            return;
        }

        throw new ValueNotValidException(['value' => $this->priority, 'allowed' => self::ALLOWED_PRIORITIES]);
    }

    public function getValue(): string
    {
        return $this->priority;
    }
}
