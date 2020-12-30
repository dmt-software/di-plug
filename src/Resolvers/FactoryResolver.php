<?php

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class FactoryResolver
 *
 * This resolver initiates a container factory to return a container instance.
 *
 * @package DMT\DependencyInjection\Resolvers
 */
final class FactoryResolver implements ResolverInterface
{
    /** @var string $factoryClass */
    private string $factoryClass;

    /** @var string $methodName */
    private string $methodName;

    /**
     * FactoryResolver constructor.
     *
     * @param string $factory
     * @param string $method
     */
    public function __construct(string $factory, string $method)
    {
        $this->factoryClass = $factory;
        $this->methodName = $method;
    }

    /**
     * @return object|null
     */
    public function resolve(): ?object
    {
        $factory = $this->factoryClass;

        return call_user_func([new $factory(), $this->methodName]);
    }
}
