<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection\Adapters;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\IlluminateAdapter;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Container\EntryNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\ContainerInterface;

class IlluminateAdapterTest extends AdapterTest
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

        /** @var IlluminateContainer $container */
        $container = $this->getMockedContainer(IlluminateContainer::class, $method, $returnValue);

        $adapter = new IlluminateAdapter($container);

        $adapter->set(IlluminateContainer::class, function () {
        });
        $adapter->get(IlluminateContainer::class);
    }

    public function provideException(): iterable
    {
        return [
            'set-existing' => ['has', true, UnavailableException::class],
            'get-not-found' => ['get', new EntryNotFoundException(), NotFoundException::class],
            'get-error' => ['get', new BindingResolutionException(), UnresolvedException::class],
        ];
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        $container = new IlluminateContainer();
        $container->bind(Adapter::class, function () use ($container) {
            return new IlluminateAdapter($container);
        });

        return $container;
    }
}
