<?php

namespace TestTask\System\Exceptions;

final class ValueNotValidException extends BusinessException
{
    public function getErrorId(): string
    {
        return 'value_not_valid';
    }

    public function getErrorDescription(): string
    {
        return 'Получено невалидное значение';
    }

    public function getHttpCode(): int
    {
        return 400;
    }
}
