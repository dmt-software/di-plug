<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\FactoryResolver;
use Iterator;

/**
 * Class InstalledClassDetector
 *
 * This detector tries to find out which dependency injection container is installed.
 */
final class InstalledClassDetector implements DetectorInterface
{
    /**
     * InstalledClassDetector constructor.
     *
     * @param Iterator<ContainerConfig> $supportedContainers
     */
    public function __construct(private readonly Iterator $supportedContainers)
    {
    }

    public function detect(?object $containerInstance): ?ContainerConfig
    {
        if (is_object($containerInstance)) {
            return null;
        }

        /** @var ContainerConfig $supportedContainer */
        foreach ($this->supportedContainers as $supportedContainer) {
            if (class_exists($supportedContainer->className)) {
                if ($supportedContainer->resolver !== FactoryResolver::class) {
                    $supportedContainer = clone($supportedContainer);
                    $supportedContainer->resolver = ClassResolver::class;
                }

                return $supportedContainer;
            }
        }

        return null;
    }
}
