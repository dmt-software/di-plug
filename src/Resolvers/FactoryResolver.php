<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class FactoryResolver
 *
 * This resolver initiates a container factory to return a container instance.
 */
final class FactoryResolver implements ResolverInterface
{
    public function __construct(private readonly string $factoryClass, private readonly string $methodName)
    {
    }

    public function resolve(): ?object
    {
        $factory = $this->factoryClass;

        return call_user_func([new $factory(), $this->methodName]);
    }
}
