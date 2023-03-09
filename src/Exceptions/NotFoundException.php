<?php

namespace DMT\DependencyInjection\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Class NotFoundException
 *
 * Thrown when an entry is not found in container.
 */
final class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
    public static function throwException(string $key, Throwable $previous = null): void
    {
        throw new self(sprintf('container key `%s` not found', $key), null, $previous);
    }
}
