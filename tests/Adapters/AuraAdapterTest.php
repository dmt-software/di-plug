<?php

namespace DMT\Test\DependencyInjection\Adapters;

use Aura\Di\Container as AuraContainer;
use Aura\Di\ContainerBuilder;
use Aura\Di\Exception\ServiceNotFound;
use Aura\Di\Injection\InjectionFactory;
use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use Psr\Container\ContainerInterface;

class AuraAdapterTest extends AdapterTest
{
    /**
     * @dataProvider provideException
     *
     * @param string $method
     * @param mixed $returnValue
     * @param string $expected
     */
    public function testExceptions(string $method, $returnValue, string $expected)
    {
        $this->expectException($expected);

        $container = $this->getMockedContainer(AuraContainer::class, $method, $returnValue);

        $reflectionProperty = new \ReflectionProperty(AuraContainer::class, 'injectionFactory');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(
            $container,
            $this->getMockBuilder(InjectionFactory::class)
                ->disableOriginalConstructor()
                ->getMock()
        );

        /** @var AuraContainer $container */
        $adapter = new AuraAdapter($container);
        $adapter->set(AuraContainer::class, function () {});
        $adapter->get(AuraContainer::class);
    }

    public function provideException(): iterable
    {
        return [
            'set-when-locked' => ['set', new Exception(), UnavailableException::class],
            'get-not-found'   => ['has', new ServiceNotFound(), NotFoundException::class],
            'get-error'       => ['get', new Exception(), UnresolvedException::class],
        ];
    }

    /**
     * Get the container.
     *
     * @return ContainerInterface
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
