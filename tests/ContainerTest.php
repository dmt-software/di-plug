<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection;

use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\ConfigurationInterface;
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\Exceptions\NotFoundException;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use DMT\Test\DependencyInjection\Fixtures\DummyClass;
use DMT\Test\DependencyInjection\Fixtures\DummyConfigurableClass;
use DMT\Test\DependencyInjection\Fixtures\DummyContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyFactory;
use DMT\Test\DependencyInjection\Fixtures\DummyOldStyleClass;
use DMT\Test\DependencyInjection\Fixtures\DummyPsrContainer;
use DMT\Test\DependencyInjection\Fixtures\DummyServiceProvider;
use DMT\Test\DependencyInjection\Fixtures\DummySingleton;
use DMT\Test\DependencyInjection\Fixtures\DummyArgumentsClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerTest extends TestCase
{
    public function testUsage()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(DummyFactory::class, fn() => new DummyFactory());

        $this->assertTrue($container->has(DummyFactory::class));
        $this->assertInstanceOf(DummyFactory::class, $container->get(DummyFactory::class));
    }

    public function testUsageWithContainerInjection()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(DummyClass::class, fn() => new DummyClass());

        $this->assertInstanceOf(DummyClass::class, $container->get(DummyClass::class));
        $this->assertSame($container, $container->get(DummyClass::class)->getContainer());
    }

    public function testUsageWithoutRegistration()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));

        $this->assertFalse($container->has(DummyContainer::class));
        $this->assertInstanceOf(DummyContainer::class, $container->get(DummyContainer::class));
    }

    public function testUsageWithConstructorArguments()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(DummyPsrContainer::class, fn() => new DummyPsrContainer(new DummyContainer()));

        $this->assertNotSame(
            $container->get(DummyPsrContainer::class),
            $container->get(DummyPsrContainer::class, new DummyContainer())
        );
    }

    public function testUsageWithPartialConstructorArguments()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));

        $this->assertSame(
            'required',
            $container->get(DummyArgumentsClass::class, 'required')->requiredArgument
        );
    }

    public function testUsageWithPartialConstructorNamedArguments()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));

        $this->assertSame(
            'required',
            $container->get(DummyArgumentsClass::class, requiredArgument: 'required')->requiredArgument
        );
    }

    public function testUsageWithoutRegistrationWithArguments()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));

        $this->assertInstanceOf(
            DummyPsrContainer::class,
            $container->get(DummyPsrContainer::class, new DummyContainer())
        );
    }

    public function testAutoResolveConstructorArguments()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));

        $this->assertInstanceOf(
            DummyPsrContainer::class,
            $container->get(DummyPsrContainer::class)
        );
    }

    public function testConfigurableClass()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(ConfigurationInterface::class, function (): ConfigurationInterface {
            $config = $this->createMock(ConfigurationInterface::class);
            $config
                ->expects($this->any())
                ->method('get')
                ->with('foo')
                ->willReturn('bar');

            return $config;
        });

        $instance = $container->get(DummyConfigurableClass::class, bar: null);

        $this->assertSame('bar', $instance->foo);
        $this->assertNull($instance->bar);
    }

    public function testConfigurableClassWithDefaultValue()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(ConfigurationInterface::class, function (): ConfigurationInterface {
            $config = $this->createMock(ConfigurationInterface::class);
            $config
                ->expects($this->any())
                ->method('get')
                ->willReturnCallback(function (string $entry, mixed $defaultValue) {
                    if ($entry === 'foo') {
                        return 'bar';
                    }
                    return $defaultValue;
                });

            return $config;
        });

        $instance = $container->get(DummyConfigurableClass::class);

        $this->assertSame('bar', $instance->foo);
        $this->assertSame('baz', $instance->bar);
    }

    public function testOldStyleConstructors()
    {
        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->set(ConfigurationInterface::class, fn () => throw new NotFoundException());

        $this->assertInstanceOf(
            DummyOldStyleClass::class,
            $container->get(DummyOldStyleClass::class)
        );
    }

    public function testClassNotFound()
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->get('__missing_class__');
    }

    /**
     * @dataProvider provideInstantiationFailure
     */
    public function testUnableToInitiateClass(string $class, ...$args)
    {
        $this->expectException(ContainerExceptionInterface::class);

        $container = new Container(new PimpleAdapter(new \Pimple\Container()));
        $container->get($class, ...$args);
    }

    public static function provideInstantiationFailure(): iterable
    {
        return [
            'missing required constructor param' => [DummyArgumentsClass::class],
            'incorrect constructor call' => [DummyPsrContainer::class, new DummyClass()],
            'not instantiable class' => [DummySingleton::class],
        ];
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
