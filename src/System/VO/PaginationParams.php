<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

final readonly class PaginationParams
{
    public const DEFAULT_PAGE = 1;
    public const DEFAULT_PER_PAGE = self::PER_PAGE_20;
    private const PER_PAGE_20 = 20;
    private const PER_PAGE_40 = 40;
    private const PER_PAGE_60 = 60;
    public const ALLOWED_PER_PAGE = [self::PER_PAGE_20, self::PER_PAGE_40, self::PER_PAGE_60];

    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        public int $page = self::DEFAULT_PAGE,
        public int $perPage = self::DEFAULT_PER_PAGE,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    private function validate(): void
    {
        if (in_array($this->perPage, self::ALLOWED_PER_PAGE, true) === false) {
            throw new ValueNotValidException(['per_page' => $this->perPage, 'allowed' => self::ALLOWED_PER_PAGE]);
        }

        if ($this->page < 1) {
            throw new ValueNotValidException([
                'reason' => "Номер страницы должен быть больше 0, получено: $this->page",
            ]);
        }
    }
}
