<?php

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class ClassResolver
 *
 * This resolver will initiate and return a container class.
 */
final class ClassResolver implements ResolverInterface
{
    public function __construct(private readonly string $containerClass)
    {
    }

    public function resolve(): ?object
    {
        $containerClass = $this->containerClass;

        return new $containerClass();
    }
}
