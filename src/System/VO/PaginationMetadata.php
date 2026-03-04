<?php

namespace TestTask\System\VO;

use TestTask\System\Exceptions\ValueNotValidException;

final readonly class PaginationMetadata
{
    /**
     * @throws ValueNotValidException
     */
    public function __construct(
        public int $currentPage,
        public int $perPage,
        public int $total,
        public int $lastPage,
    ) {
        $this->validate();
    }

    /**
     * @throws ValueNotValidException
     */
    private function validate(): void
    {
        if ($this->currentPage < 1) {
            throw new ValueNotValidException(
                ['reason' => "Текущая страница должна быть больше 0, получено: $this->currentPage"],
            );
        }
        if ($this->perPage < 1) {
            throw new ValueNotValidException(
                ['reason' => "Количество записей на странице должно быть больше 0, получено: $this->perPage"],
            );
        }
        if ($this->total < 0) {
            throw new ValueNotValidException(
                ['reason' => "Общее количество записей не может быть отрицательным, получено: $this->total"],
            );
        }
        if ($this->lastPage < 1 || $this->lastPage < $this->currentPage) {
            throw new ValueNotValidException(
                ['reason' => "Последняя страница должна быть больше 0 и не меньше текущей, получено: $this->lastPage"],
            );
        }
    }

    public function toArray(): array
    {
        return [
            'current_page' => $this->currentPage,
            'per_page' => $this->perPage,
            'total' => $this->total,
            'last_page' => $this->lastPage,
        ];
    }
}
