<?php

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Config\ContainerConfig;

/**
 * Interface DetectorInterface
 *
 * @package DMT\DependencyInjection\Detectors
 */
interface DetectorInterface
{
    /**
     * Detect container configuration.
     *
     * @param object|null $containerInstance
     * @return ContainerConfig|null
     */
    public function detect(?object $containerInstance): ?ContainerConfig;
}