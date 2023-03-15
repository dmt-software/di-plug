<?php

declare(strict_types=1);

namespace DMT\DependencyInjection\Detectors;

use DMT\DependencyInjection\Resolvers\ResolverInterface;

interface DetectorInterface
{
    /**
     * Detect container configuration.
     *
     * @param object|null $containerInstance
     * @return ResolverInterface|null
     */
    public function detect(?object $containerInstance): ?ResolverInterface;
}
