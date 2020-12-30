<?php

namespace DMT\DependencyInjection\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Throwable;

/**
 * Class UnresolvedException
 *
 * Thrown if the container can not resolve the entry given.
 *
 * @package DMT\DependencyInjection\Psr\Container
 */
final class UnresolvedException extends RuntimeException implements ContainerExceptionInterface
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
        throw new static(sprintf('error resolving `%s` with container', $key), null, $previous);
    }
}