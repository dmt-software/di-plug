<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

final class IlluminateDetector implements DetectorInterface
{
    public function detect(?object $containerInstance): ?ResolverInterface
    {
        if ($containerInstance instanceof \Illuminate\Container\Container) {
            return new Resolver($containerInstance);
        }

        if (is_object($containerInstance) || !class_exists(\Illuminate\Container\Container::class)) {
            return null;
        }

        return new ClassResolver(\Illuminate\Container\Container::class);
    }
}
