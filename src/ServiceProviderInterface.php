<?php

namespace DMT\DependencyInjection;

/**
 * Interface ServiceProvider
 *
 * @package DMT\DependencyInjection
 */
interface ServiceProviderInterface
{
    /**
     * Register dependencies to the container.
     *
     * @param Container $container
     */
    public function register(Container $container): void;
}
