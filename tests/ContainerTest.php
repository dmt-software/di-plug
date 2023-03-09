<?php

namespace DMT\Test\DependencyInjection;

use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Container;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyFactory;
use DMT\Test\DependencyInjection\Fixtures\DummyServiceProvider;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testUsage()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(DummyFactory::class, fn() => new DummyFactory());

        $this->assertTrue($container->has(DummyFactory::class));
        $this->assertInstanceOf(DummyFactory::class, $container->get(DummyFactory::class));
    }

    public function testServiceProvider()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->register(new DummyServiceProvider());

        $this->assertTrue($container->has(DummyAdapter::class));
        $this->assertTrue($container->has(DummyContainer::class));
        $this->assertInstanceOf(DummyAdapter::class, $container->get(DummyAdapter::class));
    }
}
