<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class Resolver
 *
 * This resolver holds the actual container instance to return.
 */
final class Resolver implements ResolverInterface
{
    public function __construct(private readonly object $containerInstance)
    {
    }

    public function resolve(): ?object
    {
        return $this->containerInstance;
    }
}
