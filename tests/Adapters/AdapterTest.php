<?php

namespace DMT\Test\DependencyInjection\Adapters;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Container;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
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

        $adapter->set(Container::class, fn () => new Container($container->get(Adapter::class)));

        $this->assertInstanceOf(Container::class, $adapter->get(Container::class));
    }

    /**
     * Get the container to test.
     *
     * @return ContainerInterface
     */
    abstract protected function getContainer(): ContainerInterface;

    /**
     * Get a mocked container.
     *
     * @param string $container
     * @param string $method
     * @param mixed $returnValue
     *
     * @return MockObject
     */
    protected function getMockedContainer(string $container, string $method, mixed $returnValue)
    {
        $container = $this->getMockBuilder($container)
            ->disableOriginalConstructor()
            ->onlyMethods([$method])
            ->getMock();

        if ($returnValue instanceof Exception) {
            $container
                ->expects($this->any())
                ->method($method)
                ->willThrowException($returnValue);
        } elseif ($method !== 'set') {
            $container
                ->expects($this->any())
                ->method($method)
                ->willReturn($returnValue);
        } else {
            $container
                ->expects($this->any())
                ->method($method);
        }

        return $container;
    }

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
