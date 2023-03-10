<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Resolvers;

use InvalidArgumentException;

/**
 * Class PublicMethodResolver
 *
 * This resolver returns a container by calling a method on the container wrapper.
 */
final class PublicMethodResolver implements ResolverInterface
{
    public function __construct(private readonly object $containerInstance, private readonly string $methodName)
    {
    }

    public function resolve(): ?object
    {
        if (!method_exists($this->containerInstance, $this->methodName)) {
            throw new InvalidArgumentException("method `{$this->methodName}` does not exists on container wrapper");
        }

        return call_user_func([$this->containerInstance, $this->methodName]);
    }
}
