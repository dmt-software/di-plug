<?php

namespace DMT\DependencyInjection\Resolvers;

/**
 * Class Resolver
 *
 * This resolver holds the actual container instance to return.
 *
 * @package DMT\DependencyInjection\Resolvers
 */
final class Resolver implements ResolverInterface
{
    /** @var object $containerInstance */
    private object $containerInstance;

    /**
     * ContainerResolver constructor.
     * @param object $containerInstance
     */
    public function __construct(object $containerInstance)
    {
        $this->containerInstance = $containerInstance;
    }

    /**
     * @return object|null
     */
    public function resolve(): ?object
    {
        return $this->containerInstance;
    }
}
