<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Adapters;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container;
use Psr\Container\ContainerInterface;

class PimpleAdapterTest extends AdapterTest
{
    /**
     * @dataProvider provideException
     *
     * @param string $method
     * @param mixed $returnValue
     * @param string $expected
     */
    public function testExceptions(string $method, mixed $returnValue, string $expected)
    {
        $this->expectException($expected);

        /** @var PimpleContainer $container */
        $container = $this->getMockedContainer(PimpleContainer::class, $method, $returnValue);

        $adapter = new PimpleAdapter($container);
        $adapter->set(Container::class, function () {
        });
        $adapter->has(Container::class);
        $adapter->get(Container::class);
    }

    public function provideException(): iterable
    {
        return [
            'set-object'    => ['offsetSet', new Exception(), UnavailableException::class],
            'get-not-found' => ['offsetGet', new Exception(), UnresolvedException::class],
            'get-error'     => ['offsetExists', false, NotFoundException::class],
        ];
    }

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
