<?php

namespace DMT\Test\DependencyInjection\Adapters;

use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\LeagueAdapter;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use League\Container\Container as LeagueContainer;
use League\Container\Definition\DefinitionAggregate;
use League\Container\Exception\ContainerException as LeagueContainerException;
use League\Container\Exception\NotFoundException as LeagueNotFoundException;
use League\Container\ServiceProvider\ServiceProviderAggregate;
use Psr\Container\ContainerInterface;
use ReflectionProperty;

class LeagueAdapterTest extends AdapterTest
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

        /** @var LeagueContainer $container */
        $container = $this->getMockedContainer(LeagueContainer::class, $method, $returnValue);

        $reflectionProperty = new ReflectionProperty(LeagueContainer::class, 'definitions');
        $reflectionProperty->setValue(
            $container,
            $this->getMockBuilder(DefinitionAggregate::class)
                ->getMock()
        );

        $reflectionProperty = new ReflectionProperty(LeagueContainer::class, 'providers');
        $reflectionProperty->setValue(
            $container,
            $this->getMockBuilder(ServiceProviderAggregate::class)
                ->getMock()
        );

        $adapter = new LeagueAdapter($container);
        $adapter->set(LeagueContainer::class, function () {});
        $adapter->get(LeagueContainer::class);
    }

    public function provideException(): iterable
    {
        return [
            'set-existing'  => ['has', true, UnavailableException::class],
            'get-not-found' => ['get', new LeagueNotFoundException(), NotFoundException::class],
            'get-error'     => ['get', new LeagueContainerException(), UnresolvedException::class],
        ];
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        $container = new LeagueContainer();
        $container->add(Adapter::class, function () use ($container) {
            return new LeagueAdapter($container);
        });

        return $container;
    }
}
