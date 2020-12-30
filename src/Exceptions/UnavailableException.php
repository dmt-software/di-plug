<?php

namespace DMT\DependencyInjection\Exceptions;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * Class UnavailableException
 *
 * Thrown when adding an entry to the container fails.
 *
 * @package DMT\DependencyInjection\Psr\Container
 */
final class UnavailableException extends InvalidArgumentException implements ContainerExceptionInterface
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
        throw new static(sprintf('entry `%s` is not excepted by container', $key), null, $previous);
    }
}
