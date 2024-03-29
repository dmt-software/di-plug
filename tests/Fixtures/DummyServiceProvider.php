<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Fixtures;

use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\ServiceProviderInterface;

class DummyServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->set(DummyContainer::class, fn() => new DummyContainer());
        $container->set(DummyAdapter::class, fn() => new DummyAdapter($container->get(DummyContainer::class)));
    }
}
