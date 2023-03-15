<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\PropertyResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

class PimpleDetector implements DetectorInterface
{
    public function detect(?object $containerInstance): ?ResolverInterface
    {
        if ($containerInstance instanceof \Pimple\Container) {
            return new Resolver($containerInstance);
        }

        if ($containerInstance instanceof \Pimple\Psr11\Container) {
            return new PropertyResolver($containerInstance, 'pimple');
        }

        if (is_object($containerInstance) || !class_exists(\Pimple\Container::class)) {
            return null;
        }

        return new ClassResolver(\Pimple\Container::class);
    }
}
