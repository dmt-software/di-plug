<?php

namespace DMT\Test\DependencyInjection\Adapters;

use Aura\Di\ContainerBuilder;
use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\AuraAdapter;
use Psr\Container\ContainerInterface;

class AuraAdapterTest extends AdapterTest
{
    /**
     * Get the container.
     *
     * @return object
     */
    protected function getContainer(): ContainerInterface
    {
        $container = (new ContainerBuilder())->newInstance();
        $container->set(Adapter::class, function () use ($container) {
            return new AuraAdapter($container);
        });

        return $container;
    }
}
