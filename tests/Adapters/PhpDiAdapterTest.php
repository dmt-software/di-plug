<?php

namespace DMT\Test\DependencyInjection\Adapters;

use DI\Container as PhpDiContainer;
use DI\Definition\Source\MutableDefinitionSource;
use DI\NotFoundException as PhpDiNotFoundException;
use DMT\DependencyInjection\Adapters\Adapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\DependencyInjection\Exceptions\UnavailableException;
use DMT\DependencyInjection\Exceptions\UnresolvedException;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionProperty;

class PhpDiAdapterTest extends AdapterTest
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

        $definitionSource = $this->getMockForAbstractClass(MutableDefinitionSource::class);
        if ($method === 'set') {
            $definitionSource
                ->expects($this->any())
                ->method('getDefinitions')
                ->willReturn([Container::class => function () {}]);
        }

        /** @var PhpDiContainer $container */
        $container = $this->getMockedContainer(PhpDiContainer::class, $method, $returnValue);

        $reflectionProperty = new ReflectionProperty(PhpDiContainer::class, 'definitionSource');
        $reflectionProperty->setValue($container, $definitionSource);


        $adapter = new PhpDiAdapter($container);
        $adapter->set(Container::class, function () {});
        $adapter->get(Container::class);
    }

    public function provideException(): iterable
    {
        return [
            'set-override'  => ['set', null, UnavailableException::class],
            'get-not-found' => ['get', new PhpDiNotFoundException(), NotFoundException::class],
            'get-error'     => ['get', new Exception(), UnresolvedException::class],
        ];
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer(): ContainerInterface
    {
        $container = new PhpDiContainer();
        $container->set(Adapter::class, function () use ($container) {
            return new PhpDiAdapter($container);
        });

        return $container;
    }
}
