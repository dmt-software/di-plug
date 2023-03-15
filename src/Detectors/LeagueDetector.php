<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

class LeagueDetector implements DetectorInterface
{
    public function detect(?object $containerInstance): ?ResolverInterface
    {
        if ($containerInstance instanceof \League\Container\Container) {
            return new Resolver($containerInstance);
        }

        if (is_object($containerInstance) || !class_exists(\League\Container\Container::class)) {
            return null;
        }

        return new ClassResolver(\League\Container\Container::class);
    }
}
