<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Exceptions;

use InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Throwable;

/**
 * Class UnavailableException
 *
 * Thrown when adding an entry to the container fails.
 */
final class UnavailableException extends InvalidArgumentException implements ContainerExceptionInterface
{
    public static function throwException(string $key, Throwable $previous = null): void
    {
        throw new self(sprintf('entry `%s` is not excepted by container', $key), 0, $previous);
    }
}
