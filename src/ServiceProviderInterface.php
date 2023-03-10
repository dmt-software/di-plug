<?php

declare(strict_types=1);

namespace DMT\DependencyInjection;

interface ServiceProviderInterface
{
    /**
     * Register dependencies to the container.
     *
     * @param Container $container
     */
    public function register(Container $container): void;
}
