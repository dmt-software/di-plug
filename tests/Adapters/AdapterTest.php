<?php

namespace DMT\Test\DependencyInjection\Adapters;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

abstract class AdapterTest extends TestCase
{
    /**
     * Test the adapter.
     */
    public function testAdapter()
    {
        $container = $this->getContainer();

        $adapter = $this->getAdapter($container);

        $this->assertTrue($adapter->has(Adapter::class));

        $adapter->set(Container::class, function () {
            return new Container($this->get(Adapter::class));
        });

        $this->assertInstanceOf(Container::class, $adapter->get(Container::class));
    }

    /**
     * Get the container to test.
     *
     * @return ContainerInterface
     */
    abstract protected function getContainer(): ContainerInterface;

    /**
     * Get te adapter to use.
     *
     * @param object $container
     * @return Adapter
     */
    protected function getAdapter(object $container): Adapter
    {
        return $container->get(Adapter::class);
    }
}
