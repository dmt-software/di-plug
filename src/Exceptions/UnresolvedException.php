<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Throwable;

/**
 * Class UnresolvedException
 *
 * Thrown if the container can not resolve the entry given.
 */
final class UnresolvedException extends RuntimeException implements ContainerExceptionInterface
{
    public static function throwException(string $key, Throwable $previous = null): void
    {
        throw new self(sprintf('error resolving `%s` with container', $key), 0, $previous);
    }
}
