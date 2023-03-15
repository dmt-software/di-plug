<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\Resolver;
use DMT\DependencyInjection\Resolvers\ResolverInterface;

final class PhpDiDetector implements DetectorInterface
{
    public function detect(?object $containerInstance): ?ResolverInterface
    {
        if ($containerInstance instanceof \DI\Container) {
            return new Resolver($containerInstance);
        }

        if (is_object($containerInstance) || !class_exists(\DI\Container::class)) {
            return null;
        }

        return new ClassResolver(\DI\Container::class);
    }
}
