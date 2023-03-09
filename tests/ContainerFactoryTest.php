<?php

namespace DMT\Test\DependencyInjection;

use DMT\DependencyInjection\Adapters\AuraAdapter;
use DMT\DependencyInjection\Adapters\PhpDiAdapter;
use DMT\DependencyInjection\Adapters\PimpleAdapter;
use DMT\DependencyInjection\Container;
use DMT\DependencyInjection\ContainerFactory;
use DMT\DependencyInjection\Detectors\InstalledClassDetector;
use DMT\DependencyInjection\Resolvers\Resolver;
use PHPUnit\Framework\TestCase;

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

        $factory = new ContainerFactory([
            'supported' => [
                \Pimple\Container::class => [
                    'adapter' => PimpleAdapter::class,
                    'resolver' => Resolver::class,
                ]
            ],
            'detectors' => [
                InstalledClassDetector::class => [
                    'supported' => [
                        \Pimple\Container::class
                    ]
                ]
            ],
        ]);
        $container = $factory->createContainer();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertTrue(class_exists(PimpleAdapter::class, false));
    }
}
