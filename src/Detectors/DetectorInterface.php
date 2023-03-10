<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Config\ContainerConfig;

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
