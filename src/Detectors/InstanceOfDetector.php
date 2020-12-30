<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Config\ContainerConfig;
use Iterator;

/**
 * Class InstanceOfDetector
 *
 * This detector requires an instance of a container to revolve.
 *
 * @package DMT\DependencyInjection\Detectors
 */
final class InstanceOfDetector implements DetectorInterface
{
    /** @var Iterator|ContainerConfig[] $supportedContainers */
    protected Iterator $supportedContainers;

    /**
     * InstanceOfDetector constructor.
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
