<?php

namespace TestTask\System\Exceptions;

use Exception;
use Throwable;

/**
 * Базовое абстрактное исключение.
 * Все исключение, порождаемые в слоях Domain и Application бизнес модулей должны быть наследниками данного класса.
 */
abstract class BusinessException extends Exception
{
    /** Дополнительные параметры, необходимые для уточнения ошибки */
    protected array $context;

    public function __construct(array $context = [], Throwable $previous = null)
    {
        $this->context = $context;
        parent::__construct($this->getErrorDescription(), 0, $previous);
    }

    public function context(): array
    {
        return $this->context;
    }

    /**
     * Возвращает строковый идентификатор ошибки
     */
    abstract public function getErrorId(): string;

    /**
     * Возвращает описание ошибки.
     */
    abstract public function getErrorDescription(): string;

    /**
     * Возвращает код HTTP ответа.
     */
    abstract public function getHttpCode(): int;
}
