<?php

namespace TestTask\Application\Exceptions;

use TestTask\System\Exceptions\BusinessException;

/**
 * Пример исключения в бизнес логике.
 */
final class ExampleBusinessException extends BusinessException
{
    public function getErrorId(): string
    {
        return 'something_not_valid';
    }

    public function getErrorDescription(): string
    {
        return 'Что-то невалидно или что-то пошло не так';
    }

    public function getHttpCode(): int
    {
        return 400;
    }
}
