<?php

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class ClassResolver
 *
 * This resolver will initiate and return a container class.
 *
 * @package DMT\DependencyInjection\Resolvers
 */
final class ClassResolver implements ResolverInterface
{
    /** @var string $containerClass */
    private string $containerClass;

    /**
     * ClassResolver constructor.
     *
     * @param string $containerClass
     */
    public function __construct(string $containerClass)
    {
        $this->containerClass = $containerClass;
    }

    /**
     * @return object|null
     */
    public function resolve(): ?object
    {
        $containerClass = $this->containerClass;

        return new $containerClass();
    }
}