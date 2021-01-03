<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Config\ContainerConfig;
use DMT\DependencyInjection\Resolvers\ClassResolver;
use DMT\DependencyInjection\Resolvers\FactoryResolver;
use Iterator;

/**
 * Class InstalledClassDetector
 *
 * This detector tries to find out which dependency injection container is installed.
 *
 * @package DMT\DependencyInjection\Detectors
 */
final class InstalledClassDetector implements DetectorInterface
{
    /** @var Iterator|ContainerConfig[] */
    protected Iterator $supportedContainers;

    /**
     * InstalledClassDetector constructor.
     * @param Iterator $supportedContainers
     */
    public function __construct(Iterator $supportedContainers)
    {
        $this->supportedContainers = $supportedContainers;
    }

    /**
     * Detect container configuration.
     *
     * @param object|null $containerInstance
     * @return ContainerConfig|null
     */
    public function detect(?object $containerInstance): ?ContainerConfig
    {
        foreach ($this->supportedContainers as $supportedContainer) {
            if (class_exists($supportedContainer->className)) {
                if (!$supportedContainer->resolver instanceof FactoryResolver) {
                    $supportedContainer = clone($supportedContainer);
                    $supportedContainer->resolver = ClassResolver::class;
                }

                return $supportedContainer;
            }
        }

        return null;
    }
}