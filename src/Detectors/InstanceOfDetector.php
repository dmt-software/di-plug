<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use Iterator;

/**
 * Class InstanceOfDetector
 *
 * This detector requires an instance of a container to revolve.
 */
final class InstanceOfDetector implements DetectorInterface
{
    /**
     * InstanceOfDetector constructor.
     *
     * @param Iterator<ContainerConfig> $supportedContainers
     */
    public function __construct(private readonly Iterator $supportedContainers)
    {
    }

    public function detect(?object $containerInstance): ?ContainerConfig
    {
        if (!is_object($containerInstance)) {
            return null;
        }

        $containerClass = get_class($containerInstance);
        foreach ($this->supportedContainers as $supportedContainer) {
            if ($supportedContainer->className === $containerClass) {
                return $supportedContainer;
            }
        }

        return null;
    }
}
