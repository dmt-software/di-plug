<?php

declare(strict_types=1);

namespace DMT\Test\DependencyInjection;

use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\ContainerFactory;
use DMT\DependencyInjection\Detectors\AuraDetector;
use DMT\DependencyInjection\Detectors\CallbackDetector;
use DMT\DependencyInjection\Detectors\PimpleDetector;
use DMT\Test\DependencyInjection\Fixtures\DummyAdapter;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ContainerFactoryTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testCreateContainerFromInstance()
    {
        $this->assertFalse(class_exists(PhpDiAdapter::class, false));

        $factory = new ContainerFactory();
        $container = $factory->createContainer(new \DI\Container());

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(PhpDiAdapter::class, false));
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutodetectContainer()
    {
        $this->assertFalse(class_exists(PimpleAdapter::class, false));

        $factory = new ContainerFactory([PimpleAdapter::class => PimpleDetector::class]);
        $container = $factory->createContainer();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(PimpleAdapter::class, false));
    }

    /**
     * @runInSeparateProcess
     */
    public function testAutodetectFromFactory()
    {
        $this->assertFalse(class_exists(AuraAdapter::class, false));

        $factory = new ContainerFactory([AuraAdapter::class => AuraDetector::class]);
        $container = $factory->createContainer();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(AuraAdapter::class, false));
    }

    /**
     * @dataProvider provideUnsupportedContainer
     */
    public function testUnsupportedContainer(?array $detectors, ?object $container = null)
    {
        $this->expectExceptionObject(new RuntimeException('Unsupported container'));

        (new ContainerFactory($detectors))->createContainer($container);
    }

    public static function provideUnsupportedContainer(): iterable
    {
        return [
            'object not in configuration' => [[], new \Pimple\Container()],
            'auto discover class not found' => [[DummyAdapter::class => new CallbackDetector(fn () => null)]],
        ];
    }
}
