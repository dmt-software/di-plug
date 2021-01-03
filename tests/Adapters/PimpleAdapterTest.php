<?php


namespace DMT\Test\DependencyInjection\Adapters;


use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container;
use Psr\Container\ContainerInterface;

class PimpleAdapterTest extends AdapterTest
{
    /**
     * @inheritDoc
     */
    protected function getContainer(): ContainerInterface
    {
        $container = new PimpleContainer();
        $container[Adapter::class] = function () use ($container) {
            return new PimpleAdapter($container);
        };

        return new Container($container);
    }
}