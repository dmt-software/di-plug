<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\FactoryResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

final class AuraDetector implements DetectorInterface
{
    public function detect(?object $containerInstance): ?ResolverInterface
    {
        if ($containerInstance instanceof \Aura\Di\Container) {
            return new Resolver($containerInstance);
        }

        if (is_object($containerInstance) || !class_exists(\Aura\Di\ContainerBuilder::class)) {
            return null;
        }

        return new FactoryResolver(\Aura\Di\ContainerBuilder::class, 'newInstance');
    }
}
