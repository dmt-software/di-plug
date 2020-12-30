<?php

namespace DMT\DependencyInjection\Exceptions;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

/**
 * Class NotFoundException
 *
 * Thrown when an entry is not found in container.
 *
 * @package DMT\DependencyInjection\Psr\Container
 */
final class NotFoundException extends InvalidArgumentException implements NotFoundExceptionInterface
{
    /**
     * Throw the exception.
     *
     * @param string $key
     * @param Throwable|null $previous
     * @throws static
     */
    public static function throwException(string $key, Throwable $previous = null): void
    {
        throw new static(sprintf('container key `%s` not found', $key), null, $previous);
    }
}
